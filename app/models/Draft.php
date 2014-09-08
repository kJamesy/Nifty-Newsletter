<?php

class Draft extends \Eloquent {
	public static $rules = [
								'subject' => 'required|max:128',
								'email_body' => 'required',
								'tag_id' => 'required'
						   ];

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
		return static::with('tag')
						->with('user')
						->orderBy('created_at', 'desc')
						->get();
	}

}