<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('area', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->decimal('longitude', 18, 6);
            $table->decimal('latitude', 18, 6);
            $table->unsignedInteger('level')->default(0);
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('status')->default(true);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('area');
	}

}
