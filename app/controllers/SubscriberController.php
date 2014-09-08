<?php
use Carbon\Carbon; 

class SubscriberController extends BaseController {

    public function __construct()
    {        
        $this->user = Sentry::getUser();
        $this->isAdmin = User::isAdmin( $this->user );
        $this->logged_in_for = $this->user->last_login->diffForHumans();
        $this->configs = Setting::getSiteSettings();
        $this->paginate = Subscriber::getRecordsPerPage();
        $this->orderBy = Subscriber::getOrderBy();
        $this->cacheMinutes = 30;
        $this->rules = Subscriber::$rules;
        $this->editRules = Subscriber::$editRules;
        $this->fileRules = Subscriber::$fileRules;
        $this->activeParent = 'subscribers';                   
    }

    public function setRecordsPerPage() 
    {
        $num = (int) Input::get('number') > 0 ? (int) Input::get('number') : 10;
        $this->paginate = Subscriber::setRecordsPerPage($num);
        return Redirect::to('dashboard/subscribers');
    }

    public function setOrderBy() 
    {
        $criterion = [];
        switch ( Input::get('order-by') ) {
            case 1:
                $criterion = ['id', 'asc'];
                break;
            case 2:
                $criterion = ['id', 'desc'];
                break;
            case 3:
                $criterion = ['first_name', 'asc'];
                break;
            case 4:
                $criterion = ['first_name', 'desc'];                
                break;
            case 5:
                $criterion = ['last_name', 'asc'];
                break;
            case 6:
                $criterion = ['last_name', 'desc'];                
                break;
            case 7:
                $criterion = ['email', 'asc'];
                break;
            case 8:
                $criterion = ['email', 'desc'];                
                break;                
        }

        $this->orderBy = Subscriber::setOrderBy($criterion);
        return Redirect::to('dashboard/subscribers');
    }    

	public function index()
	{
        $querystring = $_SERVER['REQUEST_URI'];
        $uppercasestring = strtoupper($querystring);

        if( strpos($querystring, '&LIST=') > 1 )
            $listId = substr($querystring, strpos($uppercasestring, '&LIST=')+6, strlen($uppercasestring));
        else 
            $listId = substr($querystring, strpos($uppercasestring, '?LIST=')+6, strlen($uppercasestring)); 

        if ( (int) $listId > 0 ) {
            $subscribers = Subscriber::getSubscribersInList( $this->paginate, $this->orderBy, $listId );
        }   
        
        else {
            $subscribers = Subscriber::getSubscribers( $this->paginate, $this->orderBy ); 
        }        

        $subscribersHtml = Jamesy\Subscribers::getSubscribersHtml( $subscribers );
        $orderBy = 1;

        switch ( $this->orderBy ) {
            case ['id', 'desc']:
                $orderBy = 2;
                break;
            case ['first_name', 'asc']:
                $orderBy = 3;
                break;
            case ['first_name', 'desc']:
                $orderBy = 4;                
                break;
            case ['last_name', 'asc']:
                $orderBy = 5;
                break;
            case ['last_name', 'desc']:
                $orderBy = 6;                
                break;
            case ['email', 'asc']:
                $orderBy = 7;
                break;
            case ['email', 'desc']:
                $orderBy = 8;                
                break;
        }
       
        return View::make('backend.subscribers.index', [
    				'user' => $this->user, 
    				'isAdmin' => $this->isAdmin, 
    				'configs' => $this->configs,
                    'subscribersHtml' => $subscribersHtml, 
    				'logged_in_for' => $this->logged_in_for, 
        			'activeParent' => $this->activeParent,
        			'active' => 'allsubscribers',
                    'records' => $this->paginate,
                    'orderBy' => $orderBy,
                    'links' => $subscribers->links('backend.pagination.nifty')                    
    			]);
	}

    public function create()
    {
        $listsList = Maillist::getMaillistList();
        return View::make('backend.subscribers.new', [
                    'user' => $this->user, 
                    'isAdmin' => $this->isAdmin, 
                    'configs' => $this->configs, 
                    'logged_in_for' => $this->logged_in_for,                
                    'activeParent' => $this->activeParent,
                    'active' => 'createsubscriber',
                    'listsList' => $listsList
                ]);
    }

    public function store()
    {
        $inputs = [];
        foreach(Input::all() as $key=>$input) {
            $inputs[$key] = Jamesy\Sanitiser::trimInput($input);
        }   

        $validation = Jamesy\MyValidations::validate( $inputs, $this->rules );

        if($validation != NULL) {
            return Redirect::back()->withErrors($validation)->withInput();
        }

        else {

            $maillists = Input::has('lists') ? Input::get('lists') : [Maillist::first()->id];

            $subscriber = new Subscriber;
            $subscriber->first_name = $inputs['first_name'];
            $subscriber->last_name = $inputs['last_name'];
            $subscriber->email = $inputs['email'];
            if ( Input::get('active') == '0' || Input::get('active') == '1' ) $subscriber->active = Input::get('active');
            $subscriber->save();

            $subscriber->maillists()->sync($maillists);
            
            $generalList = Maillist::find(1);
            $generalList->subscribers()->attach($subscriber->id); 

            $newMaillists = Subscriber::with('maillists')->find($subscriber->id)->maillists;

            foreach ( $newMaillists as $maillist ) {
                if ( $maillist->active == 0 ) {
                    $subscriber->active = 0;
                    $subscriber->save();
                    break;
                }
            }
            
            return Redirect::to('dashboard/subscribers')->withSuccess('New subscriber created.');
        }
    }

    public function activate($id)
    {
        $subscriber = Subscriber::with('maillists')->find($id);
        $inactiveList = false;

        foreach ( $subscriber->maillists as $maillist ) {
            if ( $maillist->active == 0 ) {
                $inactiveList = true;
                break;
            }
        }

        if ( $inactiveList )
            return Redirect::back()->withIssues('Subscriber cannot be activated since they belong to an inactive list.');

        else {
            $subscriber->active = 1;
            $subscriber->save();
            return Redirect::back()->withSuccess('Subscriber activated.');
        }
        
    }

    public function bulk_activate()
    {
        $ids = Input::get('subscribers');
        $notActivatedNum = 0;
        $activatedNum = 0;

        foreach ( $ids as $id ) {
           $subscriber = Subscriber::with('maillists')->find($id);
           $inactiveList = false;

            foreach ( $subscriber->maillists as $maillist ) {
                if ( ! $maillist->active ) {
                    $inactiveList = true;
                    $notActivatedNum++;
                    break;
                }
            } 

            if ( ! $inactiveList ) {
                $subscriber->active = 1;
                $subscriber->save();
                $activatedNum++;
            }         
        }

        $message = $activatedNum . ' ' . str_plural('subscriber', $activatedNum) . ' activated. ';
        $message .= $notActivatedNum ? $notActivatedNum . ' ' . str_plural('subscriber', $notActivatedNum) . ' not activated since they belong to an inactive list.' : '';
        
        if ( $notActivatedNum )
            return Redirect::back()->withIssues($message);
        else 
            return Redirect::back()->withSuccess($message);
        
    }

    public function deactivate($id)
    {
        $subscriber = Subscriber::find($id);
        $subscriber->active = 0;
        $subscriber->save();

        return Redirect::back()->withSuccess('Subscriber deactivated');
    }

    public function bulk_deactivate()
    {
        $ids = Input::get('subscribers');
        $deactivatedNum = count($ids);

        foreach ($ids as $id) {
            $subscriber = Subscriber::find($id);
            $subscriber->active = 0;
            $subscriber->save();
        }

        return Redirect::back()->withSuccess($deactivatedNum . ' ' . str_plural('subscriber', $deactivatedNum) . ' deactivated');
    }

    public function edit($id)
    {
        $subscriber = Subscriber::with('maillists')->find($id);
        $listsList = Maillist::getMaillistList();

        return View::make('backend.subscribers.edit', [
                    'user' => $this->user, 
                    'isAdmin' => $this->isAdmin, 
                    'configs' => $this->configs, 
                    'logged_in_for' => $this->logged_in_for,                
                    'activeParent' => $this->activeParent,
                    'active' => 'createsubscriber',
                    'subscriber' => $subscriber,
                    'listsList' => $listsList
                ]);
    }

    public function update($id)
    {
        $subscriber = Subscriber::find($id);
        $inputs = [];

        foreach(Input::all() as $key=>$input) {
            $inputs[$key] = Jamesy\Sanitiser::trimInput($input);
        }   

        if ( $inputs['email'] == $subscriber->email )
            $validation = Jamesy\MyValidations::validate( $inputs, $this->editRules );
        else 
            $validation = Jamesy\MyValidations::validate( $inputs, $this->rules );

        if($validation != NULL) {
            return Redirect::back()->withErrors($validation)->withInput();
        }

        else {

            $maillists = Input::has('lists') ? Input::get('lists') : [Maillist::first()->id];

            $subscriber->first_name = $inputs['first_name'];
            $subscriber->last_name = $inputs['last_name'];
            $subscriber->email = Str::lower($inputs['email']);
            $subscriber->active = $inputs['active'];
            $subscriber->save();

            $subscriber->maillists()->sync($maillists);

            $newMaillists = Subscriber::with('maillists')->find($id)->maillists;
            $deactivated = false;

            foreach ( $newMaillists as $maillist ) {
                if ( $maillist->active == 0 ) {
                    $subscriber->active = 0;
                    $subscriber->save();
                    $deactivated = true;
                    break;
                }
            }

            return Redirect::to('dashboard/subscribers')->withSuccess( $deactivated && $inputs['active'] ? 'Subscriber updated. NOTE: Subscriber belongs to an inactive list.' : 'Subscriber updated.');
        }       
    }

    public function delete($id)
    {
        $subscriber = Subscriber::find($id);
        $subscriber->is_deleted = 1;
        $subscriber->save();

        return Redirect::to('dashboard/subscribers')->withSuccess('Subscriber trashed.');
    }

    public function bulk_delete()
    {
        $subscriberIds = Input::get('subscribers'); 
        foreach ( $subscriberIds as $subscriberId ) {
            $subscriber = Subscriber::find($subscriberId);
            $subscriber->is_deleted = 1;
            $subscriber->save();
        }

        $deletedNum = count($subscriberIds);

        return Redirect::to('dashboard/subscribers')->withSuccess($deletedNum . ' ' . str_plural('subscriber', $deletedNum) . ' trashed.');
    }    

    public function trash()
    {
        $querystring = $_SERVER['REQUEST_URI'];
        $uppercasestring = strtoupper($querystring);

        if( strpos($querystring, '&LIST=') > 1 )
            $listId = substr($querystring, strpos($uppercasestring, '&LIST=')+6, strlen($uppercasestring));
        else 
            $listId = substr($querystring, strpos($uppercasestring, '?LIST=')+6, strlen($uppercasestring)); 

        if ( (int) $listId > 0 ) {
            $subscribers = Subscriber::getDeletedSubscribersInList( $this->paginate, $this->orderBy, $listId );
        }   
        
        else {
            $subscribers = Subscriber::getDeletedSubscribers( $this->paginate, $this->orderBy ); 
        }        

        $subscribersHtml = Jamesy\Subscribers::getDeletedSubscribersHtml( $subscribers );
        $orderBy = 1;

        switch ( $this->orderBy ) {
            case ['id', 'desc']:
                $orderBy = 2;
                break;
            case ['first_name', 'asc']:
                $orderBy = 3;
                break;
            case ['first_name', 'desc']:
                $orderBy = 4;                
                break;
            case ['last_name', 'asc']:
                $orderBy = 5;
                break;
            case ['last_name', 'desc']:
                $orderBy = 6;                
                break;
            case ['email', 'asc']:
                $orderBy = 7;
                break;
            case ['email', 'desc']:
                $orderBy = 8;                
                break;
        }
       
        return View::make('backend.subscribers.trash', [
                    'user' => $this->user, 
                    'isAdmin' => $this->isAdmin, 
                    'configs' => $this->configs,
                    'subscribersHtml' => $subscribersHtml, 
                    'logged_in_for' => $this->logged_in_for, 
                    'activeParent' => $this->activeParent,
                    'active' => 'trash',
                    'records' => $this->paginate,
                    'orderBy' => $orderBy,
                    'links' => $subscribers->links('backend.pagination.nifty')                    
                ]);        
    }

    public function restore($id)
    {
        $subscriber = Subscriber::find($id);
        $subscriber->is_deleted = 0;
        $subscriber->save();

        return Redirect::to('dashboard/subscribers/trash')->withSuccess('Subscriber restored.');
    }    

    public function bulk_restore()
    {
        $subscribers = Subscriber::whereIn('id', Input::get('subscribers'))->get();
        foreach ( $subscribers as $subscriber ) {
            $subscriber->is_deleted = 0;
            $subscriber->save();
        }

        $restoredNum = count($subscribers);

        return Redirect::to('dashboard/subscribers/trash')->withSuccess($restoredNum . ' ' . str_plural('subscriber', $restoredNum) . ' restored.');
    }

    public function destroy($id)
    {
        Subscriber::whereId($id)->delete();

        return Redirect::to('dashboard/subscribers/trash')->withSuccess('Subscriber permanently deleted.');
    }

    public function bulk_destroy()
    {
        Subscriber::whereIn('id', Input::get('subscribers'))->delete();
        $destroyedNum = count(Input::get('subscribers'));

        return Redirect::to('dashboard/subscribers/trash')->withSuccess($destroyedNum . ' ' . str_plural('subscriber', $destroyedNum) . ' permanently deleted.');
    }

    public function bulk_email()
    {
        $subs =  Input::get('subscribers');

        $string = '?subs=';

        foreach ($subs as $key => $value) {
            $string .= $value;
            $string .= $key + 1 == count($subs) ? '' : ',';
        }

        return Redirect::to('dashboard/emails/create' . $string);
    }

    public function import()
    {
        $validation = Jamesy\MyValidations::validate( Input::all(), $this->fileRules );

        if($validation != NULL) {
            return Redirect::back()->withErrors($validation);
        }

        else {
            $input = Input::file('file');
            $ext = pathinfo($input->getClientOriginalName(), PATHINFO_EXTENSION);

            if ( $ext != 'xlt' && $ext != 'xls' && $ext != 'csv' ) {
                return Redirect::back()->withIssues('You attempted the import with an invalid file. File must be Excel or CSV');
            }

            else {
                $import = new Jamesy\Imports( $input, $this->rules ); 
                $importResult = $import->getInsertArray();
                
                if ( is_array($importResult) ) {
                    $totalNum = $importResult[0];
                    $duplicatesNum = $importResult[1];
                    $passedArr = $importResult[2];
                    $passedNum = count($passedArr);
                    $failedNum = (int) $totalNum - (int) $duplicatesNum - (int) $passedNum;
                    $timestamp = $importResult[3];
                    
                    if ( count($passedArr) ) {
                        Subscriber::insert($passedArr);
                        $list = Maillist::find(1);
                        $list->touch();

                        $subscribers = Subscriber::where('created_at', $timestamp)->where('updated_at', $timestamp)->get();

                        foreach ($subscribers as $key => $subscriber) {
                            $list->subscribers()->attach($subscriber->id);        
                        }
                    }

                    $message = "<b>$totalNum</b> " . str_plural('row', $totalNum) . " found in excel file.";
                    if ( $duplicatesNum )
                        $message .= "<br /><b>$duplicatesNum</b> had duplicate email addresses.";
                    if ( $passedNum == 1 )             
                        $message .= "<br /><b>1</b> out of the <b>" . ($totalNum - $duplicatesNum) . "</b> with unique emails passed validation and was stored.";
                    else
                        $message .= "<br /><b>$passedNum</b> out of the <b>" . ($totalNum - $duplicatesNum) . "</b> with unique emails passed validation and were stored.";
                    if ( $failedNum == 1 )             
                        $message .= "<br /><b>1</b> out of the <b>" . ($totalNum - $duplicatesNum) . "</b> with unique emails failed validation (no first name, last name or bad email) and was NOT stored.";
                    elseif ( $failedNum > 1 ) 
                        $message .= "<br /><b>$failedNum</b> out of the <b>" . ($totalNum - $duplicatesNum) . "</b> with unique emails failed validation (no first name, last name or bad email) and were NOT stored.";

                    return Redirect::back()->withSuccess($message);
                }
                else {
                    return Redirect::back()->withIssues($importResult);
                }
            }
        }
    }

}
