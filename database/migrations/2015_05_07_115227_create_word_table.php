<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWordTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // 拼音词库
		Schema::create('words', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
			$table->string('key', 150)->unique();
		});

        // 词关系
        Schema::create('words_relations', function(Blueprint $table)
        {
            $table->integer('word_id')->unsigned();
            $table->foreign('word_id')->references('id')->on('words');
            $table->tinyInteger('rel_type');
            $table->integer('rel_id')->unsigned();
        });

        // 拼音单词
        Schema::create('pinyin', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('key', 20)->unique();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('words_relations');
		Schema::drop('words');
        Schema::drop('pinyin');
	}

}
