<?php
use Hashids\Hashids;

class AuthenticationController extends BaseController {

	public function __construct()
	{
		$this->hashids = new Hashids( Config::get('app.key'), 32, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' );
		$this->setting = Setting::first();
		$this->admin = User::first();
	}

	public function login()
	{
		return View::make('backend.login', ['setting' => $this->setting]);
	}

	public function do_login()
	{
	
		try
		{
		    $credentials = array(
		        'email'    => Input::get('email'),
		        'password' => Input::get('password')
		    );

		    if (Input::get('remember') == 1)
		    	$user = Sentry::authenticateAndRemember($credentials);
		    else
		    	$user = Sentry::authenticate($credentials);

		    $url = Session::get( 'originalRequest' ) ? Session::get( 'originalRequest' ) : URL::to('dashboard');
		    Session::forget('originalRequest');

		    return Response::json(['success' => 'Login successful. You will now be redirected to the admin dashboard', 'url' => $url]);
		}

		catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
		{
		    return Response::json(['email' => 'Email is required']);
		}
		catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
		{		   
		    return Response::json(['password' => 'Password is required.']);
		}
		catch (Cartalyst\Sentry\Users\WrongPasswordException $e)
		{
		    return Response::json(['password' => 'Wrong password, please try again.']);
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    return Response::json(['email' => 'User not found.']);
		}
		catch (Cartalyst\Sentry\Users\UserNotActivatedException $e)
		{
		    return Response::json(['email' => 'User is not activated.']);
		}

		catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e)
		{
		    return Response::json(['email' => 'User is suspended.']);
		}
		catch (Cartalyst\Sentry\Throttling\UserBannedException $e)
		{
		    return Response::json(['email' => 'User is banned.']);
		}
		
	}


	public function send_new_pass()
	{
		try
		{
			$email = Input::get('request_pass_email');
			$user = User::whereEmail($email)->first();

			$link = URL::to('dashboard/' . $this->hashids->encrypt($user->id) . '/reset-pass-from-email');

		    $name = $user->first_name . ' ' . $user->last_name;

			$data = ['user' => $user, 'link' => $link];

			$result = 
			Mailgun::send('backend.emails.auth.click-to-reset-pass', $data, function($message) use ($user, $name)
			{
				$message->from($this->admin->email, $this->admin->first_name)
						->subject($this->setting->sitename . ' Admin Password Reset')
						->to($user->email, $name)
			    		->tag('Password Recovery');
			});	

			if ( is_object($result) ) {
		    	return Response::json(['success' => 'An email has been sent to you with instructions on how to reset your password.']);
		    }

			else {
				return Response::json(['error' => 'An error was encountered sending the email. Please try again or contact server admin.']);
			}
		}

		catch (Exception $e)
		{
		    return Response::json(array('error' => 'An error occurred. Kindly try again or contact admin.'));
		}		
	}

	public function generate_new_pass($hashedId)
	{
		try {

			$id = $this->hashids->decrypt($hashedId)[0];	
		    $user = Sentry::findUserById($id);
		    $resetCode = $user->getResetPasswordCode();
		    $user = Sentry::findUserById(1);
		    $resetCode = $user->getResetPasswordCode();		    
		    $new_password = Str::random(10);

	        if ($user->attemptResetPassword($resetCode, $new_password))
	        {
	        	return View::make('backend.generate-new-pass', ["setting" => $this->setting, "success" =>"Password successfully reset. Use password <strong>$new_password</strong> to log in"]);
	        }

	        else
	        {
	        	return View::make('backend.generate-new-pass', ["error" => "Password reset failure. Kindly try again or contact admin."]);
	        }
		}

		catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
			return View::make('backend.generate-new-pass', ["error" => "User not found. Kindly try again or contact admin."]);
		}		
			
	}

	public function logout()
	{
		Sentry::logout();
		return Redirect::to('dashboard/login');	
	}

}
