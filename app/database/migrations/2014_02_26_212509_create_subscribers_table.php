<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubscribersTable extends Migration {

	public function up()
	{
		Schema::create('subscribers', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->string('first_name', 128);
			$table->string('last_name', 128);
			$table->string('email')->unique();
			$table->boolean('active')->default(1);
			$table->boolean('is_deleted')->default(0);
			$table->timestamps();						
		});
	}


	public function down()
	{
		Schema::drop('subscribers');
	}

}
