<?php

use Carbon\Carbon;

class Tag extends \Eloquent {

	public static $rules = ['name' => 'required|max:128|unique:tags'];

	public function emails()
	{
		return $this->hasMany('Email');
	}

	public static function getTagList()
	{
		return static::lists('name', 'id');
	}

	public static function getTags($paginate,$orderBy)
	{
		return static::with('emails')->orderBy($orderBy[0], $orderBy[1])->paginate($paginate);
	}

	public static function getRecordsPerPage()
	{
        $tagsPerPage = Cache::rememberForever('tagsPerPage', function()
        {
            return 10;
        }); 

        return $tagsPerPage;
	}

	public static function setRecordsPerPage($num)
	{
		Cache::forget('tagsPerPage');
		Cache::forever('tagsPerPage', $num);

		return true;
	}

	public static function getOrderBy()
	{
        $tagsOrderBy = Cache::rememberForever('tagsOrderBy', function()
        {
            return ['id', 'asc'];
        }); 

        return $tagsOrderBy;
	}

	public static function setOrderBy($criterion)
	{
		Cache::forget('tagsOrderBy');
		Cache::forever('tagsOrderBy', $criterion);

		return true;
	}		
}