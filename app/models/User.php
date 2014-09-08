<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	protected $table = 'users';

	protected $hidden = array('password');

	public static $rules = [
								'first_name' => 'required|max:128',
								'last_name' => 'required|max:128',
								'email' => 'required|email|unique:users',
								'password' => 'min:6|confirmed',
								'new_password' => 'min:6|confirmed',
						   ];

	public static $editRules = [
								'first_name' => 'required|max:128',
								'last_name' => 'required|max:128',
								'password' => 'min:6|confirmed',
								'new_password' => 'min:6|confirmed',								
						   		];

	public static $passwordRules = [ 'new_password' => 'required|min:6|confirmed' ];

	public static $newUserRules = [
								'first_name' => 'required|max:128',
								'last_name' => 'required|max:128',
								'email' => 'required|email|unique:users',
								'password' => 'required|min:6|confirmed',
								'role' => 'required|integer'
						   ];

	public function getAuthIdentifier()
	{
		return $this->getKey();
	}


	public function getAuthPassword()
	{
		return $this->password;
	}


	public function getReminderEmail()
	{
		return $this->email;
	}

	public function getRememberToken()
	{
	    return $this->remember_token;
	}

	public function setRememberToken($value)
	{
	    $this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
	    return 'remember_token';
	}

	public function emails()
	{
		return $this->hasMany('Email');
	}


	public static function isAdmin( $user )
	{
		$isAdmin = false;

		if ( $user ) {
			$admin = Sentry::findGroupByName('Administrator');
			if ( $user->inGroup($admin) )
				$isAdmin = true;
		}

		return $isAdmin;
	} 

	public static function isPublisher( $user )
	{
		$isPublisher = false;

		if ( $user ) {
			$publisher = Sentry::findGroupByName('Publisher');
			if ( $user->inGroup($publisher) )
				$isPublisher = true;
		}

		return $isPublisher;
	} 

	public static function getUsersWithEmails( $paginate,$orderBy )
	{
		return static::with(['emails' => function($query) { $query->whereIsDeleted(0); }])->orderBy($orderBy[0], $orderBy[1])->paginate($paginate);
	}	

	//User specific cache-stored settings
	public static function getRecordsPerPage( $user_id )
	{
        return Cache::rememberForever('usersPerPage_' . $user_id, function()
        {
            return 10;
        }); 
	}

	public static function setRecordsPerPage( $num, $user_id )
	{
		Cache::forget('usersPerPage_' . $user_id);
		Cache::forever('usersPerPage_' . $user_id, $num);

		return true;
	}

	public static function getOrderBy( $user_id )
	{
        return Cache::rememberForever('usersOrderBy_' . $user_id, function()
        {
            return ['id', 'asc'];
        }); 
	}

	public static function setOrderBy( $criterion, $user_id )
	{
		Cache::forget('usersOrderBy_' . $user_id);
		Cache::forever('usersOrderBy_' . $user_id, $criterion);

		return true;
	}
}