<?php
use Carbon\Carbon;

class Maillist extends Eloquent {

	public static $rules = ['name' => 'required|max:128|unique:maillists'];

	public function subscribers()
	{
		return $this->belongsToMany('Subscriber');
	}

	public function getActiveAttribute($value)
	{
		return (boolean) $value;
	}

   // public function getCreatedAtAttribute($value) 
   // { 
   //     return $carbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('jS M, Y');
   // }

   // public function getUpdatedAtAttribute($value) 
   // { 
   //     return $carbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $value)->diffForHumans();
   // }

	public static function getMaillistList()
	{
		return static::whereNotIn('id', [1])->lists('name', 'id');
	}

	public static function getMaillists($paginate,$orderBy)
	{
		return static::with('subscribers')->whereNotIn('id', [1])->orderBy($orderBy[0], $orderBy[1])->paginate($paginate);
	}

	public static function getMaillistsForEmail()
	{
		return static::with('subscribers')->whereNotIn('id', [1])->whereActive(1)->get(['id', 'name']);
	}

	public static function getQueryListsForEmail($ids)
	{
		return static::whereIn('id', $ids)->whereActive(1)->get(['id']);
	}

	public static function getMaillistWithSubs($id)
	{
		return static::with('subscribers')->find($id);
	}

	public static function getRecordsPerPage()
	{
        $listsPerPage = Cache::rememberForever('listsPerPage', function()
        {
            return 10;
        }); 

        return $listsPerPage;
	}

	public static function setRecordsPerPage($num)
	{
		Cache::forget('listsPerPage');
		Cache::forever('listsPerPage', $num);

		return true;
	}

	public static function getOrderBy()
	{
        $listsOrderBy = Cache::rememberForever('listsOrderBy', function()
        {
            return ['id', 'asc'];
        }); 

        return $listsOrderBy;
	}

	public static function setOrderBy($criterion)
	{
		Cache::forget('listsOrderBy');
		Cache::forever('listsOrderBy', $criterion);

		return true;
	}	

}
