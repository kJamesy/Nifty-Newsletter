<?php

use Carbon\Carbon;

class DashboardController extends BaseController {

	public function index()
	{
		$user = Sentry::getUser();
		$isAdmin = User::isAdmin( $user ); 
		$configs = Setting::getSiteSettings();
		$logged_in_for = $user->last_login->diffForHumans();

        return View::make('backend.dashboards.index', [
    				'user' => $user, 
    				'isAdmin' => $isAdmin,
    				'configs' => $configs, 
    				'logged_in_for' => $logged_in_for, 
        			'activeParent' => 'index',
        			'active' => 'index'
    			]);
	}

}
