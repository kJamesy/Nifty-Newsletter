<?php

use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function($table)
		{
			$table->increments('id');
			$table->string('email');
			$table->string('password');
			$table->text('permissions')->nullable();
			$table->boolean('activated')->default(0);
			$table->string('activation_code')->nullable();
			$table->timestamp('activated_at')->nullable();
			$table->timestamp('last_login')->nullable();
			$table->string('persist_code')->nullable();
			$table->string('reset_password_code')->nullable();
			$table->string('first_name', 128);
			$table->string('last_name', 128);
			$table->timestamps();

			$table->engine = 'InnoDB';
			$table->unique('email');
			$table->index('activation_code');
			$table->index('reset_password_code');
		});
		
	}


	public function down()
	{
		Schema::drop('users');
	}
}