<?php

class FrontendController extends \BaseController {

	public function __construct() 
	{
		$this->orderBy = ['id', 'desc'];
	}

	public function hello()
	{
		if ( Setting::isSetup() ) {
			$configs = Setting::getSiteSettings();
			return View::make('frontend.hello', ['configs' => $configs]);
		}
		else 
			var_dump('By hooks and crooks you\'re here. Call somebody named Jamesy and tell him bad things!');	
	}	

	public function show($slug)
	{
		$page = Page::getFrontendPage( $slug );
		$pages = Page::getFrontendPages( $except = [$page->id], $this->orderBy );

		$anchorsHtml = \Jamesy\FrontendHelpers::getAnchorsHtml( $page->anchors );
		$previousHtml = \Jamesy\FrontendHelpers::getPreviousHtml( $pages );

		return View::make('frontend.page', ['page' => $page, 'pages' => $pages, 'anchorsHtml' => $anchorsHtml, 'previousHtml' => $previousHtml]);
	}

	public function preview($slug)
	{
		//Deleted = true
	}

	public function unsubscribe($id)
	{
		$subscriber = Subscriber::find( (int) $id);

		if ( $subscriber ) {
			$subscriber->active = 0;
			$subscriber->save();
			
			$configs = Setting::getSiteSettings();
			return View::make('frontend.unsubscribe', ['subscriber' => $subscriber, 'configs' => $configs]);
		}

		else {
			echo 'User not found.';
		}
	}

}