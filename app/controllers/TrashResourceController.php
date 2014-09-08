<?php
class TrashResourceController extends BaseController {

	public function index()
	{
		if ( $trash = Email::getTrashResource() )
			return $trash;
		else
			return Response::json(['flash' => 'Server error'], 500);
	}

	public function show($id) //Would do what update() below should do to fix PUT verb for now
	{
		if ( $email = Email::find($id) ) {
			$email->is_deleted = 0;
			$email->save();
			
			return Response::json(['message' => 'Email deleted']);
		}
		else
			return Response::json(['flash' => 'Server error'], 500);		
	}


	public function update($id)
	{
		if ( $email = Email::find($id) ) {
			$email->is_deleted = 0;
			$email->save();
			
			return Response::json(['message' => 'Email deleted']);
		}
		else
			return Response::json(['flash' => 'Server error'], 500);
	}


	public function destroy($id)
	{
		Email::find($id)->delete();

		return Response::json(['message' => 'Email deleted']);
	}

}
