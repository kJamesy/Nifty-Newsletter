<?php

class PageController extends \BaseController {

	public function __construct()
	{
		$this->user = Sentry::getUser();
		$this->isAdmin = User::isAdmin( $this->user );
		$this->logged_in_for = $this->user->last_login->diffForHumans();
		$this->configs = Setting::getSiteSettings();
		$this->paginate = Page::getRecordsPerPage();
		$this->orderBy = Page::getOrderBy();
		$this->cacheMinutes = 30;
		$this->rules = Page::$rules;
		$this->activeParent = 'pages';				
	}

    public function setRecordsPerPage() 
    {
        $num = (int) Input::get('number') > 0 ? (int) Input::get('number') : 10;
        $this->paginate = Page::setRecordsPerPage($num);
        return Redirect::to('dashboard/pages');
    }

    public function setOrderBy() 
    {
        $criterion = [];
        switch ( Input::get('order-by') ) {
            case 1:
                $criterion = ['id', 'asc'];
                break;
            case 2:
                $criterion = ['id', 'desc'];
                break;
            case 3:
                $criterion = ['title', 'asc'];
                break;
            case 4:
                $criterion = ['title', 'desc'];                
                break;
        }

        $this->orderBy = Page::setOrderBy($criterion);
        return Redirect::to('dashboard/pages');
    }    


	public function index()
	{
        $pages = Page::getPages( $this->paginate, $this->orderBy ); 
        $backendPages = new \Jamesy\BackendPages($pages);
        $pagesHtml = $backendPages->getPagesHtml();      

        $orderBy = 1;

        switch ( $this->orderBy ) {
            case ['id', 'asc']:
                $orderBy = 1;
                break;
            case ['id', 'desc']:
                $orderBy = 2;
                break;
            case ['title', 'asc']:
                $orderBy = 3;
                break;
            case ['title', 'desc']:
                $orderBy = 4;                
                break;
        }

        return View::make('backend.pages.index', [
    				'user' => $this->user, 
    				'isAdmin' => $this->isAdmin, 
    				'configs' => $this->configs,
                    'pagesHtml' => $pagesHtml, 
    				'logged_in_for' => $this->logged_in_for, 
        			'activeParent' => $this->activeParent,
        			'active' => 'allpages',
                    'records' => $this->paginate,
                    'orderBy' => $orderBy,
                    'links' => $pages->links('backend.pagination.nifty'),
                    'type' => 'notdeleted'                
    			]);
	}

	public function create()
	{
		return View::make('backend.pages.new', [
					'user' => $this->user, 
					'isAdmin' => $this->isAdmin, 
					'logged_in_for' => $this->logged_in_for,
        			'activeParent' => $this->activeParent,
        			'active' => 'createpage',					
					'configs' => $this->configs
				]);
	}


	public function store()
	{
		$inputs['title'] = Jamesy\Sanitiser::trimInput( Input::get('title') );
		$inputs['content'] = Jamesy\Sanitiser::trimInput( Input::get('content') );

		$validation = Jamesy\MyValidations::validate($inputs, $this->rules); 

		if ( $validation != NULL ) {
			return Redirect::back()->withErrors($validation)->withInput();
		}

		else {		
			$anchors = [];
			$anchor_string = '';

			if ( Input::has('anchors') ) {
				$anchors = array_unique( explode( ",", Str::lower( Input::get('anchors') ) ) ) ;
				$anchors = array_filter( $anchors, function($i) { return $i != 'type in your anchors separated by a space or comma'; } );
				
				foreach ($anchors as $key => $value) {
					$anchor_string .= $value;
					$anchor_string .= $key == count($anchors) - 1 ? '' : ',';
				}
			}

			$existingSlugs = Page::lists('slug');
			$slug = Jamesy\MyValidations::makeSlug($existingSlugs, Str::slug($inputs['title']));

			$page = new Page;
			$page->user_id = $this->user->id;
			$page->title = $inputs['title'];
			$page->slug = $slug;
			$page->content = $inputs['content'];
			$page->anchors = Str::length($anchor_string) ? $anchor_string : NULL;
			$page->save();

			return Redirect::to('dashboard/pages')->withSuccess('Page created');
		}

	}


	public function edit($id)
	{
		$page = Page::find($id); 

		return View::make('backend.pages.edit', [
					'page' => $page,  
					'user' => $this->user, 
					'isAdmin' => $this->isAdmin, 
					'logged_in_for' => $this->logged_in_for,
        			'activeParent' => $this->activeParent,
        			'active' => 'allpages',
					'configs' => $this->configs
				]);
	}

	public function update($id)
	{
		$inputs['title'] = Jamesy\Sanitiser::trimInput( Input::get('title') );
		$inputs['content'] = Jamesy\Sanitiser::trimInput( Input::get('content') );

		$validation = Jamesy\MyValidations::validate($inputs, $this->rules); 

		if ( $validation != NULL ) {
			return Redirect::back()->withErrors($validation)->withInput();
		}

		else {		
			$anchors = [];
			$anchor_string = '';

			if ( Input::has('anchors') ) {
				$anchors = array_unique( explode( ",", Str::lower( Input::get('anchors') ) ) ) ;
				$anchors = array_filter( $anchors, function($i) { return $i != 'type in your anchors separated by a space or comma'; } );
				
				foreach ($anchors as $key => $value) {
					$anchor_string .= $value;
					$anchor_string .= $key == count($anchors) - 1 ? '' : ',';
				}
			}

			$page = Page::find($id);
			$slug = $page->slug;

			if ( Str::slug($inputs['title']) != $slug ) {
				$existingSlugs = Page::lists('slug');
				$slug = Jamesy\MyValidations::makeSlug( $existingSlugs, Str::slug($inputs['title']) );
			}

			$page->user_id = $this->user->id;
			$page->title = $inputs['title'];
			$page->slug = $slug;
			$page->content = $inputs['content'];
			$page->anchors = Str::length($anchor_string) ? $anchor_string : NULL;
			$page->save();

			return Redirect::to('dashboard/pages')->withSuccess('Page updated');
		}
	}

	public function delete($id)
	{
		$page = Page::find($id);
		$page->is_deleted = 1;
		$page->save();

		return Redirect::to('dashboard/pages')->withSuccess('Page moved to trash');
	}

	public function bulk_delete()
	{
		$pageIds = Input::get('pages');
		$deleted = 0;

		foreach ( $pageIds as $pageId ) {
			$page = Page::find($pageId);
			if ( $page ) {
				$page->is_deleted = 1;
				$page->save();	
				$deleted++;			
			}
		}			

		return Redirect::back()->withSuccess($deleted . ' ' . str_plural('page', $deleted) . ' moved to trash.');
	}	

	public function deleted_pages()
	{
        $pages = Page::getDeletedPages( $this->paginate, $this->orderBy ); 
        $backendPages = new \Jamesy\BackendPages($pages, $type = 'deleted');
        $pagesHtml = $backendPages->getPagesHtml();      

        $orderBy = 1;

        switch ( $this->orderBy ) {
            case ['id', 'asc']:
                $orderBy = 1;
                break;
            case ['id', 'desc']:
                $orderBy = 2;
                break;
            case ['title', 'asc']:
                $orderBy = 3;
                break;
            case ['title', 'desc']:
                $orderBy = 4;                
                break;
        }

        return View::make('backend.pages.index', [
    				'user' => $this->user, 
    				'isAdmin' => $this->isAdmin, 
    				'configs' => $this->configs,
                    'pagesHtml' => $pagesHtml, 
    				'logged_in_for' => $this->logged_in_for, 
        			'activeParent' => $this->activeParent,
        			'active' => 'allpages',
                    'records' => $this->paginate,
                    'orderBy' => $orderBy,
                    'links' => $pages->links('backend.pagination.nifty'),
                    'type' => 'deleted'                    
    			]);
	}	

	public function restore($id)
	{
		$page = Page::find($id);
		$page->is_deleted = 0;
		$page->save();
		$restored = 1;

		return Redirect::back()->withSuccess($restored . ' ' . str_plural('page', $restored) . ' restored.');
	}

	public function bulk_restore()
	{
		$pageIds = Input::get('pages');
		$restored = 0;

		foreach ( $pageIds as $pageId ) {
			$page = Page::findOrFail( $pageId );
			$page->is_deleted = 0;
			$page->save();
			$restored++;
		}

		return Redirect::back()->withSuccess($restored . ' ' . str_plural('page', $restored) . ' restored.');
	}		

	public function destroy($id)
	{
		Page::find($id)->delete();

		return Redirect::to('dashboard/pages/trash')->withSuccess('Page permanently deleted');
	}

	public function bulk_destroy()
	{
		$pageIds = Input::get('pages'); 

		Page::whereIn('id', $pageIds)->delete();

		return Redirect::back()->withSuccess( count($pageIds) . ' ' . str_plural('page', count($pageIds)) . ' permanently deleted.');
	}


}