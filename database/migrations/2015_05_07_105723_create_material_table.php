<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // 材质标准数据
		Schema::create('materials', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
            $table->char('code', 6)->unique();// 编号
            $table->integer('parent')->default(0);// 父级节点
            $table->string('name', 100);// 名称
            $table->string('pinyin', 150);// 全拼
            $table->string('letter', 50);// 简拼
            $table->string('description')->nullable();// 描述
            $table->tinyInteger('type');// 材质大类
            $table->string('mineral')->nullable();// 矿物成分
            $table->softDeletes();
		});

        // 成色扩展属性
        Schema::create('materials_metals', function(Blueprint $table)
        {
            $table->integer('material_id')->unsigned();
            $table->foreign('material_id')->references('id')->on('materials');
            $table->decimal('condition', 5, 2);// 纯度千分比
            $table->string('chemistry', 50);// 化学符号
            $table->string('chinese', 50);// 中文名字
            $table->string('english', 50);// 英语名字
            $table->tinyInteger('metal');// 贵金属类型
            $table->primary('material_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('materials_metals');
		Schema::drop('materials');
	}

}
