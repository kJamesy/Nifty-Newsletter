<?php
use Carbon\Carbon;

Route::get('phpinfo', function() {
	phpinfo();
});

Route::get('setup', 'SettingController@index');

Route::group(['before' => 'csrf'], function()
{
	Route::post('setup', 'SettingController@store');
});

Route::group(['before' => 'checkIsSetup'], function() {

	Route::get('/', 'FrontendController@hello');

	Route::group(['before' => 'redirectIfLoggedIn'], function()
	{
		Route::get('dashboard/login', 'AuthenticationController@login');
		Route::get('dashboard/{hashid}/reset-pass-from-email', 'AuthenticationController@generate_new_pass');
	});

	Route::group(['before' => 'csrf'], function()
	{
		Route::post('dashboard/login', 'AuthenticationController@do_login');
		Route::post('dashboard/request-pass', 'AuthenticationController@send_new_pass');
	});

	Route::group(['before' => 'checkLoggedIn'], function() {

		Route::get('dashboard', 'DashboardController@index');	

		/*****************EMAILS************************************/
		Route::get('dashboard/emails/create', 'EmailController@create');
		Route::get('dashboard/emails/sent', 'EmailController@sent');
		Route::get('dashboard/emails/{id}/forward', 'EmailController@forward');
		Route::resource('dashboard/emails-resource/sent', 'SentEmailResourceController');
		Route::get('dashboard/emails-custom/share/{id}', 'SentEmailCustomController@share');
		Route::get('dashboard/emails-custom/pdf/{id}', 'SentEmailCustomController@pdf');
		Route::get('dashboard/emails/drafts', 'EmailController@drafts');
		Route::get('dashboard/emails/drafts/{id}/edit', 'EmailController@edit_draft');
		Route::resource('dashboard/emails-resource/drafts', 'DraftResourceController');
		Route::get('dashboard/emails/trash', 'EmailController@trash');
		Route::resource('dashboard/emails-resource/trash', 'TrashResourceController');
		Route::get('email/backend-show/{id}', function($id) {
			if ( $email = Email::find($id) );
	        	return View::make('backend.emails.send_templates.backend-show', ['email_body' => $email->email_body, 'email_id' => $id]);
		});	
		Route::get('draft/backend-show/{id}', function($id) {
			if ( $email = Draft::find($id) );
	        	return View::make('backend.emails.send_templates.backend-draft-show', ['email_body' => $email->email_body, 'email_id' => $id]);
		});	

		/*****************PAGES*******************/
		Route::get('dashboard/pages', 'PageController@index');
		Route::get('dashboard/pages/trash', 'PageController@deleted_pages');
		Route::get('dashboard/pages/create', 'PageController@create');
		Route::get('dashboard/pages/{id}/edit', 'PageController@edit');
		Route::get('dashboard/pages/{id}/delete', 'PageController@delete');
		Route::get('dashboard/pages/{id}/restore', 'PageController@restore');
		Route::get('dashboard/pages/{id}/destroy', 'PageController@destroy');	

		/*****************TAGS************************************/
		Route::get('dashboard/tags', 'TagController@index');
		Route::get('dashboard/tags/create', 'TagController@create');
		Route::get('dashboard/tags/{id}/edit', 'TagController@edit');
		Route::get('dashboard/tags/{id}/destroy', 'TagController@destroy');		

		/*****************LISTS************************************/
		Route::get('dashboard/lists', 'MaillistController@index');
		Route::get('dashboard/lists/create', 'MaillistController@create');
		Route::get('dashboard/lists/{id}/edit', 'MaillistController@edit');
		Route::get('dashboard/lists/{id}/activate', 'MaillistController@activate');
		Route::get('dashboard/lists/{id}/deactivate', 'MaillistController@deactivate');
		Route::get('dashboard/lists/{id}/destroy', 'MaillistController@destroy');


		/*****************SUBSCRIBERS************************************/
		Route::get('dashboard/subscribers', 'SubscriberController@index');
		Route::get('dashboard/subscribers/create', 'SubscriberController@create');
		Route::get('dashboard/subscribers/{id}/activate', 'SubscriberController@activate');
		Route::get('dashboard/subscribers/{id}/deactivate', 'SubscriberController@deactivate');
		Route::get('dashboard/subscribers/{id}/edit', 'SubscriberController@edit');
		Route::get('dashboard/subscribers/{id}/delete', 'SubscriberController@delete');
		Route::get('dashboard/subscribers/trash', 'SubscriberController@trash');
		Route::get('dashboard/subscribers/{id}/restore', 'SubscriberController@restore');
		Route::get('dashboard/subscribers/{id}/destroy', 'SubscriberController@destroy');			
		Route::get('dashboard/subscribers/{id}/email', 'SubscriberController@email');	

		Route::group(['before' => 'csrf'], function()
		{

			/*****************EMAILS************************************/
			Route::post('dashboard/emails/send', 'EmailController@send');

			/*****************PAGES*******************/
			Route::post('dashboard/pages/create', 'PageController@store');
			Route::post('dashboard/pages/{id}/update', 'PageController@update');
			Route::post('dashboard/pages/bulk-delete', 'PageController@bulk_delete');
			Route::post('dashboard/pages/bulk-restore', 'PageController@bulk_restore');
			Route::post('dashboard/pages/bulk-destroy', 'PageController@bulk_destroy');		
			Route::post('dashboard/pages/set-records-per-page', 'PageController@setRecordsPerPage');
			Route::post('dashboard/pages/set-order-by', 'PageController@setOrderBy');					

			/*****************TAGS************************************/
			Route::post('dashboard/tags/store', 'TagController@store');
			Route::post('dashboard/tags/{id}/update', 'TagController@update');
			Route::post('dashboard/tags/bulk-destroy', 'TagController@bulk_destroy');
			Route::post('dashboard/tags/set-records-per-page', 'TagController@setRecordsPerPage');
			Route::post('dashboard/tags/set-order-by', 'TagController@setOrderBy');				

			/*****************LISTS************************************/
			Route::post('dashboard/lists/store', 'MaillistController@store');
			Route::post('dashboard/lists/{id}/update', 'MaillistController@update');
			Route::post('dashboard/lists/bulk-destroy', 'MaillistController@bulk_destroy');
			Route::post('dashboard/lists/set-records-per-page', 'MaillistController@setRecordsPerPage');
			Route::post('dashboard/lists/set-order-by', 'MaillistController@setOrderBy');
			Route::post('dashboard/lists/bulk-email', 'MaillistController@bulk_email');

			/*****************SUBSCRIBERS************************************/
			Route::post('dashboard/subscribers/store', 'SubscriberController@store');
			Route::post('dashboard/subscribers/{id}/update', 'SubscriberController@update');
			Route::post('dashboard/subscribers/bulk-destroy', 'SubscriberController@bulk_destroy');
			Route::post('dashboard/subscribers/set-records-per-page', 'SubscriberController@setRecordsPerPage');
			Route::post('dashboard/subscribers/set-order-by', 'SubscriberController@setOrderBy');	
			Route::post('dashboard/subscribers/bulk-activate', 'SubscriberController@bulk_activate');	
			Route::post('dashboard/subscribers/bulk-deactivate', 'SubscriberController@bulk_deactivate');
			Route::post('dashboard/subscribers/bulk-email', 'SubscriberController@bulk_email');
			Route::post('dashboard/subscribers/bulk-delete', 'SubscriberController@bulk_delete');
			Route::post('dashboard/subscribers/bulk-restore', 'SubscriberController@bulk_restore');	
			Route::post('dashboard/subscribers/import', 'SubscriberController@import');

			/*****************USERS*******************/
			Route::post('dashboard/users/profile', 'UserController@update_profile');	
			Route::post('dashboard/users/profile/password', 'UserController@update_password');
			Route::post('dashboard/users/set-records-per-page', 'UserController@set_records_per_page');
			Route::post('dashboard/users/set-order-by', 'UserController@set_order_by');		

			/**************************SETTINGS***************************/
			Route::post('dashboard/settings/set', 'SettingController@do_set');			

		});

		/*****************USERS******************************/
		Route::get('dashboard/users/profile', 'UserController@profile');
		Route::get('dashboard/users/profile/password', 'UserController@password');
		Route::group(['before' => 'checkIsAdmin'], function() {
			Route::get('dashboard/users', 'UserController@index');
			Route::get('dashboard/users/create', 'UserController@create');
			Route::get('dashboard/users/{id}/edit', 'UserController@edit');
			Route::get('dashboard/users/{id}/ban', 'UserController@ban');
			Route::get('dashboard/users/{id}/unban', 'UserController@un_ban');
			Route::get('dashboard/users/{id}/destroy', 'UserController@destroy');
			
			Route::group(['before' => 'csrf'], function()
			{
				Route::post('dashboard/users/store', 'UserController@store');
				Route::post('dashboard/users/update', 'UserController@update');
				Route::post('dashboard/users/bulk-destroy', 'UserController@bulk_destroy');
			});			
		});

		Route::get('dashboard/logout', 'AuthenticationController@logout');
	});

	//Email Pages Frontend Stuff
	Route::get('email/{slug}/preview', 'FrontendController@preview');
	Route::get('email/{slug}', 'FrontendController@show');
	Route::get('email/show/{hashId}', function($hashId) {
		$id = \Jamesy\Miscellaneous::decryptId($hashId);
		if ( $email = Email::find($id) );
        	return View::make('backend.emails.send_templates.view-in-browser', ['email_body' => $email->email_body]);
	});	

	//Mailgun API Endpoint
	Route::post('VjwombzKYGxeAKLB', 'AnalyticController@delivered');
	Route::post('YlpnbJyeXGPORABk', 'AnalyticController@failed');
	Route::post('bQKgqoyPXyORMZeN', 'AnalyticController@opened');
	Route::post('EWOvPbGNPznDdJek', 'AnalyticController@clicked');
	Route::post('BLNlanrLvyAXgVkp', 'AnalyticController@bounced');
	Route::post('XxLMnpzjmyJDNPmZ', 'AnalyticController@complained');

	//Unsubscribe
	Route::get('unsubscribe/{id}', 'FrontendController@unsubscribe');

	//Etc
	Route::get('dashboard/pdf/test', 'SentEmailCustomController@test');
});
	