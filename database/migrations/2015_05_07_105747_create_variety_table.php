<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVarietyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // 样式标准资料
		Schema::create('varieties', function(Blueprint $table)
		{
            $table->increments('id')->unsigned();
            $table->char('code', 5)->unique();// 编号
            $table->integer('parent')->default(0);// 父级节点
            $table->string('name', 100);// 名称
            $table->string('pinyin', 150);// 全拼
            $table->string('letter', 50);// 简拼
            $table->string('description')->nullable();// 描述
            $table->tinyInteger('type');// 材质大类
            $table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('varieties');
	}

}
