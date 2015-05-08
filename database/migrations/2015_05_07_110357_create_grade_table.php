<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // 等级
		Schema::create('grades', function(Blueprint $table)
		{
            $table->increments('id')->unsigned();
            $table->string('name', 100)->unique();// 名称
            $table->string('pinyin', 150);// 全拼
            $table->string('letter', 50);// 简拼
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('grades');
	}

}
