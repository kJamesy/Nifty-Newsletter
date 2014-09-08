<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailsTable extends Migration {

	public function up()
	{
		Schema::create('emails', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('user_id')->unsigned()->index();
			$table->integer('tag_id')->unsigned()->index();
			$table->string('from', 512);
			$table->string('reply_to', 512);
			$table->string('subject', 255);
			$table->mediumText('email_body');
			$table->boolean('is_deleted')->default(0);
			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');				
		});
	}


	public function down()
	{
		Schema::drop('emails');
	}

}
