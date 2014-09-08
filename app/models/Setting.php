<?php

class Setting extends Eloquent {
	protected $guarded = array();

    public static $rules = [
                            'sitename' => 'required|max:255'
                           ];


  	public static function getSiteSettings()
  	{
  		return static::first();
  	}


   	public static function isSetup()
  	{
  		if ( static::first() && count( User::all() ) > 0 ) {
  			return true;
  		}

  		else {
  			return false;
  		}
  	} 


    public static function setFromName($user, $name)
    {
        Cache::forget('fromName_'.$user->id);

        return Cache::rememberForever('fromName_'.$user->id, function() use($name) {
            return $name;
        });
    }


    public static function getFromName($user)
    {
        return Cache::rememberForever('fromName_'.$user->id, function() use($user) {
            return $user->first_name . ' ' . $user->last_name;
        });
    }


    public static function setFromEmail($user,$email)
    {  
        Cache::forget('fromEmail_'.$user->id);

        return Cache::rememberForever('fromEmail_'.$user->id,  function() use($email) {
            return Str::lower($email);
        });
    }


    public static function getFromEmail($user)
    {  
        return Cache::rememberForever('fromEmail_'.$user->id, function() use($user) {
            return Str::lower($user->first_name) . '@' . Config::get('mailgun::domain');
        });
    }


    public static function setReplyToEmail($user,$email)
    {  
        Cache::forget('replyToEmail_'.$user->id);
        return Cache::rememberForever('replyToEmail_'.$user->id, function() use($email) {
            return Str::lower($email);
        });
    }


    public static function getReplyToEmail($user)
    {  
        return Cache::rememberForever('replyToEmail_'.$user->id, function() use($user) {
            return $user->email;
        });
    }


    public static function forgetEmailSettingsCache($user)
    {
        Cache::forget('fromName_'.$user->id);
        Cache::forget('fromEmail_'.$user->id);
        Cache::forget('replyToEmail_'.$user->id);
    }



}
