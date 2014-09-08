<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDraftsTable extends Migration {

	public function up()
	{
		Schema::create('drafts', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('user_id')->unsigned()->index();
			$table->integer('tag_id')->unsigned()->index();
			$table->string('subject', 255);
			$table->mediumText('email_body');
			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');				
		});
	}


	public function down()
	{
		Schema::drop('drafts');
	}

}
