<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDLinksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('d_links', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('rel_type_src')->unsigned();
            $table->integer('rel_id_src')->unsigned();
            $table->integer('rel_type_tar')->unsigned();
            $table->integer('rel_id_tar')->unsigned();
            $table->unique(['rel_type_src', 'rel_id_src', 'rel_type_tar', 'rel_id_tar']);

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('d_links');
	}

}
