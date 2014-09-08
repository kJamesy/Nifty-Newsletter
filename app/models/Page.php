<?php

use Carbon\Carbon;

class Page extends \Eloquent {

	public static $rules = [
								'title' => 'required|max:255',
								'content' => 'required'
							];
							
	public function user()
	{
		return $this->belongsTo('User');
	}

	public static function getPages($paginate,$orderBy)
	{
		return static::with('user')->whereIsDeleted(0)->orderBy($orderBy[0], $orderBy[1])->paginate($paginate);
	}

	public static function getDeletedPages($paginate,$orderBy)
	{
		return static::with('user')->whereIsDeleted(1)->orderBy($orderBy[0], $orderBy[1])->paginate($paginate);
	}

	public static function getRecordsPerPage()
	{
        $pagesPerPage = Cache::rememberForever('pagesPerPage', function()
        {
            return 10;
        }); 

        return $pagesPerPage;
	}

	public static function setRecordsPerPage($num)
	{
		Cache::forget('pagesPerPage');
		Cache::forever('pagesPerPage', $num);

		return true;
	}

	public static function getOrderBy()
	{
        $pagesOrderBy = Cache::rememberForever('pagesOrderBy', function()
        {
            return ['id', 'asc'];
        }); 

        return $pagesOrderBy;
	}

	public static function setOrderBy($criterion)
	{
		Cache::forget('pagesOrderBy');
		Cache::forever('pagesOrderBy', $criterion);

		return true;
	}	

	public static function getFrontendPages($except, $orderBy)
	{
		return static::whereNotIn('id', $except)->whereIsDeleted(0)->orderBy($orderBy[0], $orderBy[1])->get();
	}

	public static function getFrontendPage($slug)
	{
		return static::whereSlug($slug)->whereIsDeleted(0)->first();
	}	

	public static function getFrontendPreviewPage( $slug )
	{
		return static::whereSlug($slug)->first();
	}	
}