<?php
class DraftResourceController extends BaseController {

	public function index()
	{
		if ( $drafts = Draft::getResource() )
			return $drafts;
		else
			return Response::json(['flash' => 'Server error'], 500);
	}

	public function destroy($id)
	{
		if ( $deleted = Draft::whereId($id)->delete() )
			return Response::json(['success' => 'Draft successfully deleted'], 200);
		else
			return Response::json(['flash' => 'Server error'], 500);
	}

}
