<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration {

	public function up()
	{
		Schema::create('tags', function($table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->string('name', 128)->unique();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('tags');
	}

}
