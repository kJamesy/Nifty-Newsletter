<?php
namespace Jamesy;
use \Excel;
use Str;

class Imports
{
	public function __construct($excelInput,$rules)
	{
		$this->excelInput = $excelInput;
		$this->rules = $rules; 
        $this->destination = 'files/imports';
        $this->ext = pathinfo($this->excelInput->getClientOriginalName(), PATHINFO_EXTENSION); 
        $this->filename = date('Y_m_d_H_i') . '_subscribers.' . $this->ext; 
        $this->pathToFile = public_path() . '/' . $this->destination . '/'. $this->filename;	
        $this->timestamp = date("Y-m-d H:i:s"); 
        $this->first_name_field = 'first';
        $this->last_name_field = 'last';
        $this->email_field = 'email';	
        $this->active_field = 'active';
        $this->error = '';
	}


	public function getInsertArray()
	{   
		$rows = $this->processExcel();

		if ( Str::length($this->error) ) {
			return $this->error;
		}

		else {
			$unvalidated = [];
			$uniques = [];
			$duplicatesNum = 0;		
			$existingEmails = [];
				
			try {
		        foreach ( $rows as $row ) {
		            if ( array_key_exists( trim( Str::lower($row[$this->email_field]) ), $unvalidated ) ) {
		                $duplicatesNum++;
		            }

		            else {
		            	if ( count($row) == 3 )
		                	$unvalidated[trim( Str::lower($row[$this->email_field]) )] = [trim( $row[$this->first_name_field] ), trim( $row[$this->last_name_field] ), 1];
		                elseif ( count($row) == 4 )
		                	$unvalidated[trim( Str::lower($row[$this->email_field]) )] = [trim( $row[$this->first_name_field] ), trim( $row[$this->last_name_field] ), trim( $row[$this->active_field] )];
		            }                    
		        }        

		        foreach ($unvalidated as $key => $value) {
		            $uniques[] = ['first_name' => $value[0], 'last_name' => $value[1], 'email' => $key, 'active' => $value[2], 'created_at' => $this->timestamp, 'updated_at' => $this->timestamp];    
		        }  

		        foreach ($uniques as $key => $value) {
		        	$validation = MyValidations::validateReturnAll( $value, $this->rules );

		        	if( $validation != NULL ) {
		        		$failed = $validation->failed();

			            if ( array_key_exists('email', $failed) ) {
			                if ( array_key_exists('Unique', $failed['email']) ) {
			                    $existingEmails[] = $value['email'];
			                }
			            }

		        		unset( $uniques[$key] );
		        	}
		        }

		        $passedArr = $uniques;

		        unlink( $this->pathToFile );

		        return [count($rows), $duplicatesNum, $passedArr, $this->timestamp, $existingEmails];	

			} catch (Exception $e) {
				return $this->error = 'An unexpected error occurred with the manipulation of data. Please check the file and try again.';
			}
	    }

	}

	public function processExcel()
	{
		$rows = null;

        if ( $this->excelInput->move($this->destination, $this->filename) ) {
        	try {
        		$reader = Excel::selectSheetsByIndex(0)->load( $this->pathToFile );
        		// $rows = $reader->get();
        		$rows = $reader->select([$this->first_name_field, $this->last_name_field, $this->email_field, $this->active_field])->get();

        		if ( count($rows[0]) != 3 && count($rows[0]) != 4 )
        			$this->error = "Your excel file must contain at least three fields with columns titled appropriately";

        		if ( ! isset( $rows[0][$this->first_name_field] ) || ! isset( $rows[0][$this->last_name_field] ) || ! isset( $rows[0][$this->email_field] ) )
        			$this->error = "Your excel file must contain at least three fields with columns titled appropriately";
					
        	} catch (Exception $e) {
        		$this->error = "It wasn't possible to select rows from your file. Please check the file and try again.";
        	}
        }
              	
        else  {
        	$this->error = "File could not be saved on disk. Please check file permissions";		
        }

        return $rows;
	}

}