<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMaillistSubscriberTable extends Migration {

	public function up()
	{
		Schema::create('maillist_subscriber', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('maillist_id')->unsigned()->index();
			$table->foreign('maillist_id')->references('id')->on('maillists')->onDelete('cascade');
			$table->integer('subscriber_id')->unsigned()->index();
			$table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('cascade');
		});
	}

	public function down()
	{
		Schema::drop('maillist_subscriber');
	}

}
