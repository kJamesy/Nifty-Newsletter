<?php
class EmailController extends BaseController {

    public function __construct()
    {
        $this->user = Sentry::getUser();
        $this->isAdmin = User::isAdmin( $this->user );
        $this->logged_in_for = $this->user->last_login->diffForHumans();
        $this->configs = Setting::getSiteSettings();
        // $this->paginate = Subscriber::getRecordsPerPage();
        // $this->orderBy = Subscriber::getOrderBy();
        // $this->cacheMinutes = 30;
        $this->rules = Email::$rules;
        $this->draftRules = Draft::$rules;
        // $this->editRules = Subscriber::$editRules;
        $this->activeParent = 'emails';                   
    }

	public function index()
	{
        return View::make('emails.index');
	}

	public function create()
	{
		// Setting::forgetEmailSettingsCache($this->user);
		
		$selectedSubsArr = [];

		if ( isset($_GET['subs']) ) {
			$getData = explode(',', $_GET['subs']);
			$goodData = [];

			foreach ($getData as $key => $value) {
				if ( (int) $value > 0 )
					$goodData[] = (int) $value;
			}

			if ( count($goodData) ) {
				$subscribers = Subscriber::getQuerySubscribersForEmail($goodData);

				foreach ($subscribers as $key => $value) {
					$selectedSubsArr[] = $value->email;
				}				
			}
		}

		$selectedListsArr = [];

		if ( isset($_GET['lists']) ) {
			$getData = explode(',', $_GET['lists']);
			$goodData = [];

			foreach ($getData as $key => $value) {
				if ( (int) $value > 0 )
					$goodData[] = (int) $value;
			}

			if ( count($goodData) ) {
				$lists = Maillist::getQueryListsForEmail($goodData);

				foreach ($lists as $key => $value) {
					$selectedListsArr[] = $value->id;
				}				
			}			
		}

		$email_configs = [
							'from_name' => Setting::getFromName($this->user),
							'from_email' => Setting::getFromEmail($this->user),
							'reply_to_email' => Setting::getReplyToEmail($this->user),
						 ];

		$tag_list = Tag::getTagList();
		$subscribers = Subscriber::getSubscribersForEmail();
		$subsArr = [];

		foreach ($subscribers as $subscriber) {
			$subsArr[$subscriber->email] = $subscriber->first_name . ' ' . $subscriber->last_name . '(' . $subscriber->email . ')';
		}

		$maillists = Maillist::getMaillistsForEmail();
		$listsArr = [];

		foreach ($maillists as $maillist) {
			$listsArr[$maillist->id] = $maillist->name . ' (' . count($maillist->subscribers) . ' subscribers)';
		}		

        return View::make('backend.emails.new', [
    				'user' => $this->user, 
    				'isAdmin' => $this->isAdmin, 
    				'configs' => $this->configs,
    				'logged_in_for' => $this->logged_in_for, 
        			'activeParent' => $this->activeParent,
        			'active' => 'createemail',
        			'subscribers' => $subsArr,
        			'selected_subs' => $selectedSubsArr,
        			'mail_lists' => $listsArr,
        			'selected_lists' => $selectedListsArr,
        			'email_configs' => $email_configs,
        			'tag_list' => $tag_list                  
    			]);		
	}

	public function forward($id)
	{
		// Setting::forgetEmailSettingsCache($this->user);
		$email_configs = [
							'from_name' => Setting::getFromName($this->user),
							'from_email' => Setting::getFromEmail($this->user),
							'reply_to_email' => Setting::getReplyToEmail($this->user),
						 ];

		$tag_list = Tag::getTagList();
		$subscribers = Subscriber::getSubscribersForEmail();
		$subsArr = [];

		foreach ($subscribers as $subscriber) {
			$subsArr[$subscriber->email] = $subscriber->first_name . ' ' . $subscriber->last_name . '(' . $subscriber->email . ')';
		}

		$maillists = Maillist::getMaillistsForEmail();
		$listsArr = [];

		foreach ($maillists as $maillist) {
			$listsArr[$maillist->id] = $maillist->name . ' (' . count($maillist->subscribers) . ' subscribers)';
		}		

		$email = Email::find($id);

        return View::make('backend.emails.edit', [
    				'user' => $this->user, 
    				'isAdmin' => $this->isAdmin, 
    				'configs' => $this->configs,
    				'logged_in_for' => $this->logged_in_for, 
        			'activeParent' => $this->activeParent,
        			'active' => 'createemails',
        			'subscribers' => $subsArr,
        			'mail_lists' => $listsArr,
        			'email_configs' => $email_configs,
        			'tag_list' => $tag_list,
        			'email' => $email              
    			]);	
	}	

	public function send()
	{ 
        $inputs = [];
        foreach(Input::all() as $key=>$input) {
           	$inputs[$key] = Jamesy\Sanitiser::trimInput($input);
        }   

        $rules = Input::has('save_draft') ? $this->draftRules : $this->rules;

        $validation = Jamesy\MyValidations::validate( $inputs, $rules );

        if($validation != NULL) {
            return Redirect::back()->withErrors($validation)->withInput();
        }

        else {		
        	
        	if ( Input::has('save_draft') ) {
        		
        		$draft = Input::has('was_draft') ? Draft::find( Input::get('was_draft') ) : new Draft;

        		$draft->user_id = $this->user->id;
        		$draft->tag_id = $inputs['tag_id'];
        		$draft->subject = $inputs['subject'];
        		$draft->email_body = $inputs['email_body'];
        		$draft->save();

        		return Redirect::to('dashboard/emails/drafts');	
        	}

        	else {

        		$maxSendNum = 999;
	        	
				$email = new Email;
				$email->user_id = $this->user->id;
				$email->tag_id = $inputs['tag_id'];
				$email->from = Setting::getFromName($this->user) . " (" . Setting::getFromEmail($this->user) . ")";
				$email->reply_to = Setting::getFromName($this->user) . " (" . Setting::getReplyToEmail($this->user) . ")";
				$email->subject = $inputs['subject'];
				$email->email_body = $inputs['email_body'];
				$email->save();

	        	$maillists = Input::has('mail_lists') ? $inputs['mail_lists'] : [];
	        	$emails = Input::has('subscribers') ? $inputs['subscribers'] : [];

		   		$selectedSubs = Subscriber::getSelectedSubscribersForEmail( $maillists, $emails );

				while ( count($selectedSubs) ) {
					$data = ['email_body' => $inputs['email_body'], 'email_id' => \Jamesy\Miscellaneous::encryptId($email->id)];
					$variables = [];

					$tag = Tag::find($inputs['tag_id']);
					$emailId = $email->id;

					$numToSlice = count($selectedSubs) > $maxSendNum ? $maxSendNum : count($selectedSubs);

					$subscribers = $numToSlice < count($selectedSubs) ? array_slice($selectedSubs, 0, $numToSlice) : $selectedSubs;

					foreach ($subscribers as $subscriber) {
						$variables[ $subscriber['email'] ] = [ 'id' => $subscriber['id'], 'first_name' => $subscriber['first_name'], 'last_name' => $subscriber['last_name'] ];
					}

					$result = 
					Mailgun::send('backend.emails.send_templates.main', $data, function($message) use ($subscribers,$inputs,$variables,$emailId,$tag)
					{ 
						$message->from(Setting::getFromEmail($this->user), Setting::getFromName($this->user))
								->replyTo(Setting::getReplyToEmail($this->user), Setting::getFromName($this->user))
								->subject($inputs['subject']);
						        foreach ($subscribers as $subscriber) {
						            $message->to($subscriber['email'], $subscriber['first_name'] . ' ' . $subscriber['last_name']);
						        }
					    $message->recipientVariables($variables);
					    $message->tag($tag->name);
					});			

					if ( is_object($result) ) {
						if ( $result->http_response_code == 200 ) {
							foreach ($subscribers as $subscriber) {
								$apicall_id = explode('<', explode('@', $result->http_response_body->id)[0])[1];

								$analytic = new Analytic;
								$analytic->subscriber_id = $subscriber['id'];
								$analytic->email_id = $email->id;
								$analytic->recipient = $subscriber['email'];
								$analytic->apicall_id = $apicall_id;
								$analytic->status = 'queued';
								$analytic->save();
							}
						}
					}

					$selectedSubs = array_slice( (array) $selectedSubs, $numToSlice);					
				
				}

				if ( Input::has('was_draft')  )
					Draft::find( Input::get('was_draft') )->delete();

				return Redirect::to('dashboard/emails/sent');
			}
		}
		
	}

	public function sent()
	{
        return View::make('backend.emails.sent', [
    				'user' => $this->user, 
    				'isAdmin' => $this->isAdmin, 
    				'configs' => $this->configs,
    				'logged_in_for' => $this->logged_in_for, 
        			'activeParent' => $this->activeParent,
        			'active' => 'sentemails'               
    			]);	

	}

	public function drafts()
	{
        return View::make('backend.emails.drafts', [
    				'user' => $this->user, 
    				'isAdmin' => $this->isAdmin, 
    				'configs' => $this->configs,
    				'logged_in_for' => $this->logged_in_for, 
        			'activeParent' => $this->activeParent,
        			'active' => 'draftemails'               
    			]);			
	}

	public function edit_draft($id)
	{
		$email_configs = [
							'from_name' => Setting::getFromName($this->user),
							'from_email' => Setting::getFromEmail($this->user),
							'reply_to_email' => Setting::getReplyToEmail($this->user),
						 ];

		$tag_list = Tag::getTagList();
		$subscribers = Subscriber::getSubscribersForEmail();
		$subsArr = [];

		foreach ($subscribers as $subscriber) {
			$subsArr[$subscriber->email] = $subscriber->first_name . ' ' . $subscriber->last_name . '(' . $subscriber->email . ')';
		}

		$maillists = Maillist::getMaillistsForEmail();
		$listsArr = [];

		foreach ($maillists as $maillist) {
			$listsArr[$maillist->id] = $maillist->name . ' (' . count($maillist->subscribers) . ' subscribers)';
		}		

		$email = Draft::find($id);

        return View::make('backend.emails.edit-draft', [
    				'user' => $this->user, 
    				'isAdmin' => $this->isAdmin, 
    				'configs' => $this->configs,
    				'logged_in_for' => $this->logged_in_for, 
        			'activeParent' => $this->activeParent,
        			'active' => 'createemails',
        			'subscribers' => $subsArr,
        			'mail_lists' => $listsArr,
        			'email_configs' => $email_configs,
        			'tag_list' => $tag_list,
        			'email' => $email              
    			]);	
	}

	public function trash()
	{
        return View::make('backend.emails.trash', [
    				'user' => $this->user, 
    				'isAdmin' => $this->isAdmin, 
    				'configs' => $this->configs,
    				'logged_in_for' => $this->logged_in_for, 
        			'activeParent' => $this->activeParent,
        			'active' => 'trash'               
    			]);			
	}


	public function destroy($id)
	{
		//
	}

}
