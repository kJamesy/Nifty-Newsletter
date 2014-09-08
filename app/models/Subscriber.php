<?php

class Subscriber extends Eloquent {
	protected $guarded = array();

	public static $rules = [
								'first_name' => 'required|max:128',
								'last_name' => 'required|max:128',
								'email' => 'required|email|unique:subscribers'
						   ];

	public static $editRules = [
									'first_name' => 'required|max:128',
									'last_name' => 'required|max:128'
						   		];

	public static $fileRules = [
									'file' => 'required|max:512'
						    	];

	public static $fileSizeRules = [
									'file' => 'max:512'
						    	];						    	

	public function maillists()
	{
		return $this->belongsToMany('Maillist');
	}

	public function analytics()
	{
		return $this->hasMany('Analytic');
	}

	public function clicks()
	{
		return $this->hasMany('Click');
	}		

	public static function getSubscribers($paginate,$orderBy)
	{
		return static::with(['maillists' => function($query) { $query->whereNotIn('maillist_id', [1]) ; }])->whereIsDeleted(0)->orderBy($orderBy[0], $orderBy[1])->paginate($paginate);
	}

	public static function getDeletedSubscribers($paginate,$orderBy)
	{
		return static::with(['maillists' => function($query) { $query->whereNotIn('maillist_id', [1]) ; }])->whereIsDeleted(1)->orderBy($orderBy[0], $orderBy[1])->paginate($paginate);
	}	

	public static function getSubscribersInList($paginate,$orderBy,$listId)
	{
		return static::whereHas('maillists', function($query) use ($listId) { $query->whereIn('maillist_id', [ (int) $listId ]); })->whereIsDeleted(0)->orderBy($orderBy[0], $orderBy[1])->paginate($paginate);
	}	

	public static function getDeletedSubscribersInList($paginate,$orderBy,$listId)
	{
		return static::whereHas('maillists', function($query) use ($listId) { $query->whereIn('maillist_id', [ (int) $listId ]); })->whereIsDeleted(1)->orderBy($orderBy[0], $orderBy[1])->paginate($paginate);
	}		

	/**
	 * Get a list of all subscribers eligible to receive an email
	 * @return [array] I think we expect an array of objects back...
	 */
	public static function getSubscribersForEmail()
	{
		return static::whereIsDeleted(0)->whereActive(1)->get(['first_name', 'last_name', 'email']);
	}

	public static function getQuerySubscribersForEmail($ids)
	{
		return static::whereIn('id', $ids)->whereIsDeleted(0)->whereActive(1)->get(['email']);
	}


	public static function getSelectedSubscribersForEmail( array $maillists, array $emails )
	{
		$subscribers = [];

		if ( count($maillists) && count($emails) ) {
		   	$maillistSubs = static::join('maillist_subscriber', 'maillist_subscriber.subscriber_id', '=', 'subscribers.id')
											    ->whereIn('maillist_subscriber.maillist_id', $maillists)
											    ->whereNotIn('subscribers.email', $emails)
											    ->groupBy('maillist_subscriber.subscriber_id')
											    ->where('subscribers.active', 1)
											    ->where('subscribers.is_deleted', 0)
											    ->get(['subscribers.*'])
											    ->toArray();
			$selectedSubs = static::whereIn('email', $emails)->whereIsDeleted(0)->whereActive(1)->get()->toArray();	
			$subscribers = array_merge( (array) $maillistSubs, (array) $selectedSubs );
		}

		elseif ( count($maillists) && ! count($emails) ) {
			$subscribers = static::whereHas('maillists', function($q) use($maillists) { $q->whereIn('maillist_id', $maillists); })->whereActive(1)->whereIsDeleted(0)->groupBy('id')->get()->toArray();
		}

		elseif ( ! count($maillists) && count($emails) ) {
			$subscribers = static::whereIn('email', $emails)->whereIsDeleted(0)->whereActive(1)->get()->toArray();
		}		

		return $subscribers;
	}

	public static function getRecordsPerPage()
	{
        $subscribersPerPage = Cache::rememberForever('subscribersPerPage', function()
        {
            return 10;
        }); 

        return $subscribersPerPage;
	}

	public static function setRecordsPerPage($num)
	{
		Cache::forget('subscribersPerPage');
		Cache::forever('subscribersPerPage', $num);

		return true;
	}

	public static function getOrderBy()
	{
        $subscribersOrderBy = Cache::rememberForever('subscribersOrderBy', function()
        {
            return ['id', 'asc'];
        }); 

        return $subscribersOrderBy;
	}

	public static function setOrderBy($criterion)
	{
		Cache::forget('subscribersOrderBy');
		Cache::forever('subscribersOrderBy', $criterion);

		return true;
	}

}
