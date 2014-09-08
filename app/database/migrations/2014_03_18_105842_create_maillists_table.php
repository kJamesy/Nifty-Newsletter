<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaillistsTable extends Migration {

	public function up()
	{
		Schema::create('maillists', function($table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->string('name', 128)->unique();
			$table->boolean('active')->default(1);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('maillists');
	}

}
