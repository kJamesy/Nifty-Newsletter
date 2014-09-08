<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClicksTable extends Migration {

	public function up()
	{
		Schema::create('clicks', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			
			$table->increments('id');
			$table->integer('subscriber_id')->unsigned()->index();
			$table->integer('email_id')->unsigned()->index();
			$table->string('url');		
			$table->timestamps();	

			$table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('cascade');
			$table->foreign('email_id')->references('id')->on('emails')->onDelete('cascade');				
		});		
	}


	public function down()
	{
		Schema::drop('clicks');
	}

}
