<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePagesTable extends Migration {

	public function up()
	{
		Schema::create('pages', function(Blueprint $table)
		{
	      	$table->increments('id');
	      	$table->integer('user_id')->unsigned();
	      	$table->string('title', 255);
	      	$table->string('slug', 255);
			$table->text('content');
			$table->text('anchors')->nullable();
			$table->boolean('is_deleted')->default(0);
	      	$table->timestamps();

	      	$table->index('slug');
	      	$table->engine = "InnoDB";
	      	$table->foreign('user_id')->references('id')->on('users');
		});
	}

	public function down()
	{
		Schema::drop('pages');
	}

}
