<?php

class Email extends \Eloquent {
	public static $rules = [
								'subscribers' => 'required_without:mail_lists',
								'mail_lists' => 'required_without:subscribers',
								'subject' => 'required|max:128',
								'email_body' => 'required',
								'tag_id' => 'required'
						   ];

	public function analytics()
	{
		return $this->hasMany('Analytic');
	}

	public function clicks()
	{
		return $this->hasMany('Click');
	}	

	public function tag()
	{
		return $this->belongsTo('Tag');
	}

	public function user()
	{
		return $this->belongsTo('User');
	}

	public static function getResource()
	{
		return static::with('analytics.subscriber')
						->with('tag')
						->with('clicks')
						->with('user')
						->where('is_deleted', 0)
						->orderBy('created_at', 'desc')
						->get();
	}	

	public static function getTrashResource()
	{
		return static::with('analytics.subscriber')
						->with('tag')
						->with('user')
						->where('is_deleted', 1)
						->orderBy('created_at', 'desc')
						->get();
	}	
}