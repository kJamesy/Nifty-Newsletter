<?php
use Carbon\Carbon; 

class TagController extends BaseController {

    public function __construct()
    {
        $this->user = Sentry::getUser();
        $this->isAdmin = User::isAdmin( $this->user );
        $this->logged_in_for = $this->user->last_login->diffForHumans();
        $this->configs = Setting::getSiteSettings();
        $this->paginate = Tag::getRecordsPerPage();
        $this->orderBy = Tag::getOrderBy();
        $this->cacheMinutes = 30;
        $this->rules = Tag::$rules;
        $this->activeParent = 'tags';                   
    }

    public function setRecordsPerPage() 
    {
        $num = (int) Input::get('number') > 0 ? (int) Input::get('number') : 10;
        $this->paginate = Tag::setRecordsPerPage($num);
        return Redirect::to('dashboard/tags');
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
                $criterion = ['name', 'asc'];
                break;
            case 4:
                $criterion = ['name', 'desc'];                
                break;
        }

        $this->orderBy = Tag::setOrderBy($criterion);
        return Redirect::to('dashboard/tags');
    }    

	public function index()
	{
        $tags = Tag::getTags( $this->paginate, $this->orderBy ); 
        $tagsHtml = Jamesy\Tags::getTagsHtml( $tags );      

        $orderBy = 1;

        switch ( $this->orderBy ) {
            case ['id', 'asc']:
                $orderBy = 1;
                break;
            case ['id', 'desc']:
                $orderBy = 2;
                break;
            case ['name', 'asc']:
                $orderBy = 3;
                break;
            case ['name', 'desc']:
                $orderBy = 4;                
                break;
        }

        return View::make('backend.tags.index', [
    				'user' => $this->user, 
    				'isAdmin' => $this->isAdmin, 
    				'configs' => $this->configs,
                    'tagsHtml' => $tagsHtml, 
    				'logged_in_for' => $this->logged_in_for, 
        			'activeParent' => $this->activeParent,
        			'active' => 'alltags',
                    'records' => $this->paginate,
                    'orderBy' => $orderBy,
                    'links' => $tags->links('backend.pagination.nifty')                    
    			]);
	}

    public function create()
    {
        return View::make('backend.tags.new', [
                    'user' => $this->user, 
                    'isAdmin' => $this->isAdmin, 
                    'configs' => $this->configs, 
                    'logged_in_for' => $this->logged_in_for,                
                    'activeParent' => $this->activeParent,
                    'active' => 'createtag'
                ]);
    }

    public function store()
    {
        $inputs = [];
        foreach(Input::all() as $key=>$input) {
            $inputs[$key] = Jamesy\Sanitiser::trimInput($input);
        }   

        $validation = Jamesy\MyValidations::validate( $inputs, $this->rules );

        if($validation != NULL) {
            return Redirect::back()->withErrors($validation)->withInput();
        }

        else {

            $tag = new Tag;
            $tag->name = $inputs['name'];
            $tag->save();

            return Redirect::to('dashboard/tags')->withSuccess('New tag created.');
        }
    }

    public function edit($id)
    {
        $tag = Tag::find($id);
        return View::make('backend.tags.edit', [
                    'user' => $this->user, 
                    'isAdmin' => $this->isAdmin, 
                    'configs' => $this->configs, 
                    'logged_in_for' => $this->logged_in_for,                
                    'activeParent' => $this->activeParent,
                    'active' => 'createtag',
                    'tag' => $tag
                ]);
    }

    public function update($id)
    {
        $tag = Tag::find($id);
        $inputs = [];

        foreach(Input::all() as $key=>$input) {
            $inputs[$key] = Jamesy\Sanitiser::trimInput($input);
        }   

        if ( $inputs['name'] == $tag->name )
            $validation = NULL;
        else 
            $validation = Jamesy\MyValidations::validate( $inputs, $this->rules );

        if($validation != NULL) {
            return Redirect::back()->withErrors($validation)->withInput();
        }

        else {

            $tag->name = $inputs['name'];
            $tag->save();

            return Redirect::to('dashboard/tags')->withSuccess('Tag updated.');
        }       
    }

    public function destroy($id)
    {
        Tag::whereId($id)->delete();

        return Redirect::to('dashboard/tags')->withSuccess('Tag destroyed.');
    }

    public function bulk_destroy()
    {
        $tagIds = Input::get('Tags'); 
        Tag::whereIn('id', $tagIds)->delete();

        return Redirect::to('dashboard/tags')->withSuccess(count($tagIds) . ' ' . str_plural('tag', count($tagIds)) . ' destroyed.');
    }

}
