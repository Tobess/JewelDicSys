<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSShapeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
        Schema::create('s_shapes',function(Blueprint $table){
            $table->increments('id')->unsigned();
            $table->string('name',100)->unique();
            $table->string('pinyin',150);
            $table->string('letter',50);
            $table->integer('material_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
        Chema::drop('s_shapes');
	}

}
