<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // 品牌
		Schema::create('brands', function(Blueprint $table)
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
		Schema::drop('brands');
	}

}
