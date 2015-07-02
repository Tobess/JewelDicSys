<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('rules', function(Blueprint $table)
		{
			$table->string('elements');// 规则相关组成元素集合
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('rules', function(Blueprint $table)
		{
            $table->dropColumn('elements');
		});
	}

}
