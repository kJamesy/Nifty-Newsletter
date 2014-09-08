<?php
use Carbon\Carbon; 

class AddressbookController extends BaseController {

    public function __construct()
    {
        $this->user = Sentry::getUser();
        $this->isAdmin = User::isAdmin( $this->user );
        $this->logged_in_for = $this->user->last_login->diffForHumans();
        $this->configs = Setting::getSiteSettings();
        $this->paginate = Addressbook::getRecordsPerPage();
        $this->orderBy = Addressbook::getOrderBy();
        $this->cacheMinutes = 30;
        $this->rules = Addressbook::$rules;
        $this->activeParent = 'lists';                   
    }

    public function setRecordsPerPage() 
    {
        $num = (int) Input::get('number') > 0 ? (int) Input::get('number') : 10;
        $this->paginate = Addressbook::setRecordsPerPage($num);
        return Redirect::to('dashboard/lists');
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

        $this->orderBy = Addressbook::setOrderBy($criterion);
        return Redirect::to('dashboard/lists');
    }    

	public function index()
	{
        $lists = Addressbook::getAddressbooks( $this->paginate, $this->orderBy ); 
        $listsHtml = Jamesy\Addressbooks::getListsHtml( $lists );      

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

        return View::make('backend.lists.index', [
    				'user' => $this->user, 
    				'isAdmin' => $this->isAdmin, 
    				'configs' => $this->configs,
                    'listsHtml' => $listsHtml, 
    				'logged_in_for' => $this->logged_in_for, 
        			'activeParent' => $this->activeParent,
        			'active' => 'alllists',
                    'records' => $this->paginate,
                    'orderBy' => $orderBy,
                    'links' => $lists->links('backend.pagination.nifty')                    
    			]);
	}

    public function create()
    {
        return View::make('backend.lists.new', [
                    'user' => $this->user, 
                    'isAdmin' => $this->isAdmin, 
                    'configs' => $this->configs, 
                    'logged_in_for' => $this->logged_in_for,                
                    'activeParent' => $this->activeParent,
                    'active' => 'createlist'
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

            $addressbook = new Addressbook;
            $addressbook->name = $inputs['name'];
            if ( Input::get('active') == '0' || Input::get('active') == '1' ) $addressbook->active = Input::get('active');
            $addressbook->save();

            return Redirect::to('dashboard/lists')->withSuccess('New list created.');
        }
    }

    public function edit($id)
    {
        $list = Addressbook::find($id);
        return View::make('backend.lists.edit', [
                    'user' => $this->user, 
                    'isAdmin' => $this->isAdmin, 
                    'configs' => $this->configs, 
                    'logged_in_for' => $this->logged_in_for,                
                    'activeParent' => $this->activeParent,
                    'active' => 'createlist',
                    'list' => $list
                ]);
    }

    public function update($id)
    {
        $addressbook = Addressbook::find($id);
        $inputs = [];

        foreach(Input::all() as $key=>$input) {
            $inputs[$key] = Jamesy\Sanitiser::trimInput($input);
        }   

        if ( $inputs['name'] == $addressbook->name )
            $validation = NULL;
        else 
            $validation = Jamesy\MyValidations::validate( $inputs, $this->rules );

        if($validation != NULL) {
            return Redirect::back()->withErrors($validation)->withInput();
        }

        else {

            $addressbook->name = $inputs['name'];
            $addressbook->active = $inputs['active'];
            $addressbook->save();

            if ( $addressbook->active == 0 ) {
                $subscribers = Subscriber::with(['addressbooks' => function($query) use($id) { $query->where('addressbook_id', $id); }])->get();

                foreach ( $subscribers as $subscriber ) {
                        $subscriber->active = 0;
                        $subscriber->save();
                }   
            }         

            return Redirect::to('dashboard/lists')->withSuccess($addressbook->active == 0 ? 'List updated. NOTE: All subscribers in the list are now inactive.' : 'List updated.');
        }       
    }

    public function destroy($id)
    {
        Addressbook::whereId($id)->delete();

        return Redirect::to('dashboard/lists')->withSuccess('List destroyed.');
    }

    public function bulk_destroy()
    {
        $listIds = Input::get('lists'); 
        Addressbook::whereIn('id', $listIds)->delete();

        return Redirect::to('dashboard/lists')->withSuccess(count($listIds) . ' ' . str_plural('list', count($listIds)) . ' destroyed.');
    }

}
