<?php
use Carbon\Carbon;

class UserController extends BaseController {

	public function __construct()
	{
		$this->user = Sentry::getUser();
		$this->isAdmin = User::isAdmin( $this->user );
		$this->logged_in_for = $this->user->last_login->diffForHumans();
		$this->configs = Setting::getSiteSettings();
		$this->paginate = User::getRecordsPerPage($this->user->id);
        $this->orderBy = User::getOrderBy($this->user->id);
		$this->rules = User::$rules;
		$this->editRules = User::$editRules;
		$this->newUserRules = User::$newUserRules;
		$this->activeParent = 'users';					
	}

    public function set_records_per_page() 
    {
        $num = (int) Input::get('number') > 0 ? (int) Input::get('number') : 10;
        $this->paginate = User::setRecordsPerPage( $num, $this->user->id );
        return Redirect::to('dashboard/users');
    }

    public function set_order_by() 
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

        $this->orderBy = User::setOrderBy( $criterion, $this->user->id );
        return Redirect::to('dashboard/users');
    } 

	public function index()
	{
		$users = User::getUsersWithEmails( $this->paginate, $this->orderBy );
		$usersHtml = Jamesy\BackendUsers::getUsersHtml( $users );

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

        return View::make('backend.users.index', [
        			'user' => $this->user, 
        			'isAdmin' => $this->isAdmin, 
        			'configs' => $this->configs, 
        			'logged_in_for' => $this->logged_in_for,     			
        			'activeParent' => $this->activeParent,
        			'active' => 'allusers',
        			'usersHtml' => $usersHtml,
                    'records' => $this->paginate,
                    'orderBy' => $orderBy,        			
        			'links' => $users->links('backend.pagination.nifty')
        		]);
		
	}

	public function create()
	{
		$groups = Sentry::findAllGroups();

        return View::make('backend.users.new', [
        			'user' => $this->user, 
        			'isAdmin' => $this->isAdmin, 
        			'configs' => $this->configs, 
        			'logged_in_for' => $this->logged_in_for,     			
        			'activeParent' => $this->activeParent,
        			'active' => 'createuser',
        			'groups' => $groups
        		]);
	}

	public function store()
	{
		$validation = Jamesy\MyValidations::validate( Input::all(), $this->newUserRules );

		if($validation != NULL)
			return Redirect::back()->withErrors($validation)->withInput();

		else {
			$firstName = Jamesy\Sanitiser::trimInput( Input::get('first_name') );
			$lastName = Jamesy\Sanitiser::trimInput( Input::get('last_name') );
			$email = Jamesy\Sanitiser::trimInput( Input::get('email') );

		    $newUser = Sentry::register([
								        'email'    => $email,
								        'password' => Input::get('password_confirmation'),
								        'first_name' => $firstName,
								        'last_name' => $lastName
								    ], true);

		    $group = Sentry::findGroupById( Input::get('role') );
		    $newUser->addGroup($group);

			return Redirect::to('dashboard/users')->withSuccess('New user created.');
		}
	}

	public function edit($id)
	{
		$thisUser = Sentry::findUserById( $id );
		$userGroup = $thisUser->getGroups()[0]; 

        return View::make('backend.users.edit', [
        			'user' => $this->user, 
        			'isAdmin' => $this->isAdmin, 
        			'configs' => $this->configs, 
        			'logged_in_for' => $this->logged_in_for,     			
        			'activeParent' => $this->activeParent,
        			'active' => 'profile',
        			'thisUser' => $thisUser,
        			'userGroup' => $userGroup,
        			'allGroups' => Sentry::findAllGroups(),
        		]);
	}

	public function update()
	{
		$theUser = Sentry::findUserById( Input::get('id') );
		$firstName = Jamesy\Sanitiser::trimInput( Input::get('first_name') );
		$lastName = Jamesy\Sanitiser::trimInput( Input::get('last_name') );
		$email = Jamesy\Sanitiser::trimInput( Input::get('email') );

		if ( $email == $theUser->email )
			$validation = Jamesy\MyValidations::validate( Input::all(), $this->editRules );
		else
			$validation = Jamesy\MyValidations::validate( Input::all(), $this->rules );

		if($validation != NULL) {
			return Redirect::back()->withErrors($validation)->withInput();
		}

		else {
			if ( $email != $theUser->email || $firstName != $theUser->first_name || $lastName != $theUser->last_name  || Input::has('new_password') ) {
				$theUser->first_name = $firstName;
				$theUser->last_name = $lastName;
				$theUser->email = $email;
				if ( Input::has('new_password') )
					$theUser->password = Input::get('new_password'); ///I can't explain this!!
				$theUser->save();
			}

			$currentGroup = Sentry::findGroupById( Input::get('currentGroup') );
			$group = Sentry::findGroupById( Input::get('group') );

			if ( $theUser->removeGroup( $currentGroup ) ) {
				$theUser->addGroup($group);	
			}				
		    
			return Redirect::back()->withSuccess('User updated.');
		}	
	}

	public function ban($id)
	{
		$user = Sentry::findThrottlerByUserId($id);
		
		$user->ban();

		return Redirect::back()->withSuccess('User banned');
	}

	public function un_ban($id)
	{
		$user = Sentry::findThrottlerByUserId($id);
		
		$user->unBan();

		return Redirect::back()->withSuccess('User un-banned');
	}

	public function destroy($id)
	{
		$user = Sentry::findUserById( $id );
		$user->delete();

		Cache::forget('frontendCategories');
		Cache::forget('frontendLatestPosts');		

		return Redirect::back()->withSuccess('User deleted.');
	}

	public function bulk_destroy()
	{
		$users = Input::get('users');
		$deleted = 0;

		foreach ( $users as $id ) {
			$user = Sentry::findUserById( $id  );
			if ( $user->id != Sentry::getUser()->id ) {
    			$user->delete();
    			$deleted++;
			}
    	}

		Cache::forget('frontendCategories');
		Cache::forget('frontendLatestPosts');

    	return Redirect::back()->withSuccess( $deleted . ' ' . str_plural('user', $deleted) . ' destroyed.' );

	}


	public function profile()
	{
        return View::make('backend.users.profile', [
        			'user' => $this->user, 
        			'isAdmin' => $this->isAdmin, 
        			'configs' => $this->configs, 
        			'logged_in_for' => $this->logged_in_for,     			
        			'activeParent' => $this->activeParent,
        			'active' => 'profile',
        			'roles' => $this->user->getGroups(),
        			'userSince' => $this->user->created_at->diffForHumans(),
        			'userCreatedAt' => $this->user->created_at->format('D jS \\of M, Y H:i'),
        			'loggedInAt' => $this->user->last_login->format('D jS \\of M, Y H:i')
        		]);
	}

	public function update_profile()
	{
		$theUser = User::find( Input::get('id') );
		$firstName = Jamesy\Sanitiser::trimInput( Input::get('first_name') );
		$lastName = Jamesy\Sanitiser::trimInput( Input::get('last_name') );
		$email = Jamesy\Sanitiser::trimInput( Input::get('email') );

		if ( $email == $theUser->email )
			$validation = Jamesy\MyValidations::validate( Input::all(), $this->editRules );
		else
			$validation = Jamesy\MyValidations::validate( Input::all(), $this->rules );

		if($validation != NULL) {
			return Redirect::back()->withErrors($validation)->withInput();
		}

		else {
			if ( $email != $theUser->email || $firstName != $theUser->first_name || $lastName != $theUser->last_name ) {
				$theUser->first_name = $firstName;
				$theUser->last_name = $lastName;
				$theUser->email = $email;
				$theUser->save();
			}			

			return Redirect::back()->withSuccess('Profile updated.');
		}		
	}

	public function password()
	{
        return View::make('backend.users.password', [
        			'user' => $this->user, 
        			'isAdmin' => $this->isAdmin, 
        			'configs' => $this->configs, 
        			'logged_in_for' => $this->logged_in_for,     			
        			'activeParent' => $this->activeParent,
        			'active' => 'profile'
        		]);
	}

	public function update_password()
	{
		$theUser = User::find( Input::get('id') );

		if ( ! Hash::check( Input::get('existing_password'), $this->user->password ) ) 
			return Redirect::back()->withExistingPassError('Wrong password.');
		
		$validation = Jamesy\MyValidations::validate( Input::all(), User::$passwordRules );

		if($validation != NULL)
			return Redirect::back()->withErrors($validation);

		else {
			$theUser->password = Hash::make( Input::get('new_password') );
			$theUser->save();

			return Redirect::back()->withSuccess('Password updated.');
		}		
	}

}
