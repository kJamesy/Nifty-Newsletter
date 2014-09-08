<?php

class SettingController extends BaseController {


	public function index()
	{
		if( ! Setting::isSetup() )
			return View::make('backend.settings.create');
		else
        	return Redirect::to('dashboard');
	}


	public function store()
	{
		$inputs = [];

		foreach(Input::all() as $key=>$input)
		{
			if($key == 'password' || $key == 'password_confirmation')
				$inputs[$key] = $input;
			else
				$inputs[$key] = Jamesy\Sanitiser::trimInput($input);
		}
		
		$validation = Jamesy\MyValidations::validate($inputs, array_merge(Setting::$rules, User::$rules));

	    if($validation != NULL)
	    {
	    	return Response::json($validation);
	    }

	    else
	    {
	    	$setup = new Setting;
	    	$setup->sitename = Input::get('sitename');
	    	$setup->save();

		    $maillist = new Maillist;
		    $maillist->name = "General";
		    $maillist->save();	

		    $tag = new Tag;
		    $tag->name = "Test Emails";
		    $tag->save();    	

			try
			{
			    $user = Sentry::register(array(
			        'email'    => Input::get('email'),
			        'password' => Input::get('password_confirmation'),
			        'first_name' => Input::get('first_name'),
			        'last_name' => Input::get('last_name')
			    ), true);

			   	$group = Sentry::createGroup([
										        'name'        => 'Administrator'
										    ]);

				$group = Sentry::createGroup([
										        'name'        => 'Publisher'
										    ]);

				$group = Sentry::findGroupByName('Administrator');
				$user->addGroup($group);

	    		return Response::json( [
    									"success" => "It's all ready! You are being redirected to the login page...",
    									"url" => URL::to('dashboard/login')
    								   ] );
			}
			catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
			{
			   return Response::json( ["email" => "Email is required."] );
			}
			catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
			{
			    return Response::json( ["password" => "Password is required."] );
			}
			catch (Cartalyst\Sentry\Users\UserExistsException $e)
			{
			    return Response::json( ["email" => "Email is already taken."] );
			}	    	
	    }
	}

	public function do_set() 
	{
		$rules = ['sitename' => 'required|max:255', 'sender_name' => 'required|max:510', 'sender_email' => 'required|email', 'reply_to_email' => 'required|email'];

		$validation = Jamesy\MyValidations::validate(Input::all(), $rules);

	    if($validation != NULL) {
	    	return Response::json(['validation' => $validation]);
	    }

	    else {		
	    	$setting = Setting::getSiteSettings();
	    	$setting->sitename = Input::get('sitename');
	    	$setting->save();

	    	$user = Sentry::getUser();
	    	Setting::setFromName($user, Input::get('sender_name'));
	    	Setting::setFromEmail($user, Input::get('sender_email'));
	    	Setting::setReplyToEmail($user, Input::get('reply_to_email'));

	    	return Response::json(['success' => 'Settings successfully saved.']);
	    }

	}

}
