<?php
use Carbon\Carbon; 

class MaillistController extends BaseController {

    public function __construct()
    {      
        $this->user = Sentry::getUser();
        $this->isAdmin = User::isAdmin( $this->user );
        $this->logged_in_for = $this->user->last_login->diffForHumans();
        $this->configs = Setting::getSiteSettings();
        $this->paginate = Maillist::getRecordsPerPage();
        $this->orderBy = Maillist::getOrderBy();
        $this->cacheMinutes = 30;
        $this->rules = Maillist::$rules;
        $this->activeParent = 'lists'; 
        $this->subscriberRules = Subscriber::$rules;  
        $this->fileSizeRules = Subscriber::$fileRules;                
    }

    public function setRecordsPerPage() 
    {
        $num = (int) Input::get('number') > 0 ? (int) Input::get('number') : 10;
        $this->paginate = Maillist::setRecordsPerPage($num);
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

        $this->orderBy = Maillist::setOrderBy($criterion);
        return Redirect::to('dashboard/lists');
    }    

    public function index()
    {
        $lists = Maillist::getMaillists( $this->paginate, $this->orderBy ); 
        $listsHtml = Jamesy\Maillists::getListsHtml( $lists );      

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
        $name =  Jamesy\Sanitiser::trimInput( Input::get('name') );

        $validation = Jamesy\MyValidations::validate( ['name' => $name], $this->rules );

        if($validation != NULL) {
            return Redirect::back()->withErrors($validation)->withInput();
        }

        else {                      
            if ( Input::file('file') ) {
                $fileValidation = Jamesy\MyValidations::validate( ['file' => Input::file('file')], $this->fileSizeRules );

                if($fileValidation != NULL) {
                    return Redirect::back()->withErrors($fileValidation)->withInput();
                }

                else {
                    $maillist = new Maillist;
                    $maillist->name = $name;
                    if ( Input::get('active') == '0' || Input::get('active') == '1' ) $maillist->active = Input::get('active');
                    $maillist->save();     
                                    
                    $input = Input::file('file');
                    $ext = pathinfo($input->getClientOriginalName(), PATHINFO_EXTENSION);

                    if ( $ext != 'xlt' && $ext != 'xls' && $ext != 'csv' ) {
                        return Redirect::back()->withIssues('You attempted the import with an invalid file. File must be Excel or CSV')->withInput();
                    }

                    else {
                        $import = new Jamesy\Imports( $input, $this->subscriberRules ); 
                        $importResult = $import->getInsertArray();
                        
                        if ( is_array($importResult) ) {
                            $totalNum = $importResult[0];
                            $duplicatesNum = $importResult[1];
                            $passedArr = $importResult[2];
                            $passedNum = count($passedArr);
                            $failedNum = (int) $totalNum - (int) $duplicatesNum - (int) $passedNum;
                            $timestamp = $importResult[3];
                            $existingEmails = $importResult[4];

                            $newPassedArr = [];

                            if ( $maillist->active == 0 ) {
                                foreach ( $passedArr as $subscriber ) {
                                    $subscriber['active'] = 0;
                                    $newPassedArr[] = $subscriber;
                                }   
                            }  

                            else {
                                $newPassedArr = $passedArr;
                            }

                            if ( count($newPassedArr) ) {
                                Subscriber::insert($newPassedArr);

                                $generalList = Maillist::find(1);

                                $generalList->touch();
                                $maillist->touch();                            

                                $subscribers = Subscriber::where('created_at', $timestamp)->where('updated_at', $timestamp)->get();

                                foreach ($subscribers as $subscriber) {
                                    $generalList->subscribers()->attach($subscriber->id);   
                                    $maillist->subscribers()->attach($subscriber->id);       
                                }
                            }

                            if ( count($existingEmails) ) {
                                $alreadySubs = Subscriber::whereIn('email', $existingEmails)->get();
                               
                                if ( count($alreadySubs) ) {
                                    foreach ($alreadySubs as $subscriber) {
                                        $maillist->subscribers()->attach($subscriber->id);

                                        if ( $maillist->active == 0 ) {
                                            $subscriber->active = 0;
                                            $subscriber->save();
                                        }
                                    }
                                } 
                            }
                            
                            $message = "New list created.";
                            $message .= "<br /><b>$totalNum</b> " . str_plural('row', $totalNum) . " found in excel file.";
                            if ( $duplicatesNum )
                                $message .= "<br /><b>$duplicatesNum</b> had duplicate email addresses.";
                            if ( $passedNum == 1 )             
                                $message .= "<br /><b>1</b> out of the <b>" . ($totalNum - $duplicatesNum) . "</b> with unique emails passed validation and was stored.";
                            else
                                $message .= "<br /><b>$passedNum</b> out of the <b>" . ($totalNum - $duplicatesNum) . "</b> with unique emails passed validation and were stored.";
                            if ( $failedNum == 1 )             
                                $message .= "<br /><b>1</b> out of the <b>" . ($totalNum - $duplicatesNum) . "</b> with unique emails failed validation (no first name, last name or bad email) and was NOT stored.";
                            elseif ( $failedNum > 1 ) 
                                $message .= "<br /><b>$failedNum</b> out of the <b>" . ($totalNum - $duplicatesNum) . "</b> with unique emails failed validation (no first name, last name or bad email) and were NOT stored.";

                            return Redirect::to('dashboard/lists')->withSuccess($message);
                        }
                        else {
                            return Redirect::back()->withIssues($importResult);
                        }
                    }
                }
            }

            else {
                $maillist = new Maillist;
                $maillist->name = $name;
                if ( Input::get('active') == '0' || Input::get('active') == '1' ) $maillist->active = Input::get('active');
                $maillist->save();     
                            
                return Redirect::to('dashboard/lists')->withSuccess('New list created');
            }
        }
    }

    public function edit($id)
    {
        $list = Maillist::find($id);
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
        $maillist = Maillist::getMaillistWithSubs($id);
        $name =  Jamesy\Sanitiser::trimInput( Input::get('name') ); 

        if ( $name == $maillist->name )
            $validation = NULL;
        else 
            $validation = Jamesy\MyValidations::validate( ['name' => $name], $this->rules );

        if($validation != NULL) {
            return Redirect::back()->withErrors($validation)->withInput();
        }

        else {

            $maillist->name = $name;
            $maillist->active = Input::get('active');
            $maillist->save();

            if ( $maillist->active == 0 ) {
                $subscribers = $maillist->subscribers;

                foreach ( $subscribers as $subscriber ) {
                        $subscriber->active = 0;
                        $subscriber->save();
                }   
            }   

            if ( Input::file('file') ) {
                $fileValidation = Jamesy\MyValidations::validate( ['file' => Input::file('file')], $this->fileSizeRules );

                if($fileValidation != NULL) {
                    return Redirect::back()->withErrors($fileValidation)->withInput();
                }

                else {
                    $input = Input::file('file');
                    $ext = pathinfo($input->getClientOriginalName(), PATHINFO_EXTENSION);

                    if ( $ext != 'xlt' && $ext != 'xls' && $ext != 'csv' ) {
                        return Redirect::back()->withIssues('You attempted the import with an invalid file. File must be Excel or CSV')->withInput();
                    }

                    else {
                        $import = new Jamesy\Imports( $input, $this->subscriberRules ); 
                        $importResult = $import->getInsertArray();
                        
                        if ( is_array($importResult) ) {
                            $totalNum = $importResult[0];
                            $duplicatesNum = $importResult[1];
                            $passedArr = $importResult[2];
                            $passedNum = count($passedArr);
                            $failedNum = (int) $totalNum - (int) $duplicatesNum - (int) $passedNum;
                            $timestamp = $importResult[3];
                            $existingEmails = $importResult[4];

                            $newPassedArr = [];

                            if ( $maillist->active == 0 ) {
                                foreach ( $passedArr as $subscriber ) {
                                        $subscriber['active'] = 0;
                                        $newPassedArr[] = $subscriber;
                                }   
                            }  

                            else {
                                $newPassedArr = $passedArr;
                            }

                            if ( count($newPassedArr) ) {
                                Subscriber::insert($newPassedArr);

                                $generalList = Maillist::find(1);

                                $generalList->touch();
                                $maillist->touch();

                                $subscribers = Subscriber::where('created_at', $timestamp)->where('updated_at', $timestamp)->get();

                                foreach ($subscribers as $key => $subscriber) {
                                    $generalList->subscribers()->attach($subscriber->id);   
                                    $maillist->subscribers()->attach($subscriber->id);       
                                }
                            }

                            if ( count($existingEmails) ) {
                                $alreadySubs = Subscriber::with('maillists')->whereIn('email', $existingEmails)->get();

                                if ( count($alreadySubs) ) {
                                    foreach ($alreadySubs as $subscriber) {
                                        if ( ! $subscriber->maillists->contains($maillist->id) ) {
                                            $maillist->subscribers()->attach($subscriber->id);
                                        }

                                        if ( $maillist->active == 0 ) {
                                            $subscriber->active = 0;
                                            $subscriber->save();
                                        }                                        
                                    }
                                } 
                            }

                            $message = "List updated.";
                            $message .= "<br /><b>$totalNum</b> " . str_plural('row', $totalNum) . " found in excel file.";
                            if ( $duplicatesNum )
                                $message .= "<br /><b>$duplicatesNum</b> had duplicate email addresses.";
                            if ( $passedNum == 1 )             
                                $message .= "<br /><b>1</b> out of the <b>" . ($totalNum - $duplicatesNum) . "</b> with unique emails passed validation and was stored.";
                            else
                                $message .= "<br /><b>$passedNum</b> out of the <b>" . ($totalNum - $duplicatesNum) . "</b> with unique emails passed validation and were stored.";
                            if ( $failedNum == 1 )             
                                $message .= "<br /><b>1</b> out of the <b>" . ($totalNum - $duplicatesNum) . "</b> with unique emails failed validation (no first name, last name or bad email) and was NOT stored.";
                            elseif ( $failedNum > 1 ) 
                                $message .= "<br /><b>$failedNum</b> out of the <b>" . ($totalNum - $duplicatesNum) . "</b> with unique emails failed validation (no first name, last name or bad email) and were NOT stored.";

                            return Redirect::to('dashboard/lists')->withSuccess($message);
                        }
                        else {
                            return Redirect::back()->withIssues($importResult);
                        }
                    }
                }
            }

            else {
                return Redirect::to('dashboard/lists')->withSuccess($maillist->active == 0 ? 'List updated. NOTE: All subscribers in the list are now inactive.' : 'List updated.');
            }                  

        }       
    }

    public function activate($id)
    {
        $maillist = Maillist::find($id);
        $maillist->active = 1;
        $maillist->save();

        return Redirect::back()->withSuccess('List activated. Please note that this does NOT activate the subscribers in the list.');
    }

    public function deactivate($id)
    {
        $maillist = Maillist::getMaillistWithSubs($id);
        $maillist->active = 0;
        $maillist->save();

        $subscribers = $maillist->subscribers;

        foreach ( $subscribers as $subscriber ) {
            $subscriber->active = 0;
            $subscriber->save();
        }          

        return Redirect::back()->withSuccess('List deactivated. Please note that all subscribers that belong to this list have been deactivated.');        
    }

    public function destroy($id)
    {
        Maillist::whereId($id)->delete();

        return Redirect::to('dashboard/lists')->withSuccess('List destroyed.');
    }

    public function bulk_destroy()
    {
        $listIds = Input::get('lists'); 
        Maillist::whereIn('id', $listIds)->delete();

        return Redirect::to('dashboard/lists')->withSuccess(count($listIds) . ' ' . str_plural('list', count($listIds)) . ' destroyed.');
    }

    public function bulk_email()
    {
        $lists = Input::get('lists');
        $string = '?lists=';

        foreach ($lists as $key => $value) {
            $string .= $value;
            $string .= $key + 1 == count($lists) ? '' : ',';
        }

        return Redirect::to('dashboard/emails/create' . $string);
    }

}
