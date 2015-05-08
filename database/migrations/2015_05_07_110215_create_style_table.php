<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStyleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // 款式
		Schema::create('styles', function(Blueprint $table)
		{
            $table->increments('id')->unsigned();
            $table->string('code', 100);// 编号
            $table->string('name', 100);// 名称
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
		Schema::drop('styles');
	}

}
