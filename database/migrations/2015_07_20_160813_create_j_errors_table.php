<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJErrorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('j_errors', function(Blueprint $table)
		{
            $table->bigInteger('file_id')->primary();
            $table->string('domain');
            $table->string('mobile');
            $table->string('userName');
            $table->longText('contents');
            $table->boolean('checked')->default(false);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('j_errors');
	}

}
