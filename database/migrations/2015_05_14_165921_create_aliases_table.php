<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAliasesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // 元素别名
		Schema::create('aliases', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('name');
            $table->string('pinyin');
            $table->string('letter');
            $table->integer('rel_id')->unsigned();
            $table->tinyInteger('rel_type');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('aliases');
	}

}
