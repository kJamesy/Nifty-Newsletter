<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalyticsTable extends Migration {

	public function up()
	{
		Schema::create('analytics', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('subscriber_id')->unsigned()->index();
			$table->integer('email_id')->unsigned()->index();
			$table->string('recipient', 255)->index();
			$table->string('apicall_id', 255)->index();
			$table->string('status', 32)->index();
			$table->string('ip', 32)->nullable();
			$table->string('country', 64)->nullable();
			$table->string('city', 64)->nullable();
			$table->string('client_name', 64)->nullable();
			$table->string('client_type', 64)->nullable();
			$table->string('client_os', 64)->nullable();
			$table->string('device_type', 64)->nullable();	
			$table->string('reason', 64)->nullable();			
			$table->timestamps();	

			$table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('cascade');
			$table->foreign('email_id')->references('id')->on('emails')->onDelete('cascade');						
		});
	}

	public function down()
	{
		Schema::drop('analytics');
	}

}
