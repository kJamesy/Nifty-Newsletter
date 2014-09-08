<?php
class SentEmailResourceController extends BaseController {

	public function index()
	{
		if ( $sent_emails = Email::getResource() )
			return $sent_emails;
		else
			return Response::json(['flash' => 'Server error'], 500);
	}

	public function create()
	{

	}

	public function store()
	{
		
	}

	public function show()
	{
		
	}

	public function edit()
	{
		
	}

	public function update($id)
	{
		if ( $email = Email::find($id) ) {
			$email->is_deleted = 1;
			$email->save();
			
			return Response::json(['message' => 'Email deleted']);
		}
		else
			return Response::json(['flash' => 'Server error'], 500);
	}


	public function destroy($id) //Will do soft-delete (What update() should do) to fix bug for now
	{
		if ( $email = Email::find($id) ) {
			$email->is_deleted = 1;
			$email->save();
			
			return Response::json(['message' => 'Email deleted']);
		}
		else
			return Response::json(['flash' => 'Server error'], 500);		
	}

	public function missingMethod($parameters = [])
	{
	    var_dump($parameters);
	}	

}
