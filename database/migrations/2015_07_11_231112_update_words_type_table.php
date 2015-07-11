<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWordsTypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('words', function(Blueprint $table)
		{
            $table->dropColumn('type');
            $table->boolean('fullable')->default(false);
            $table->boolean('positive')->default(false);
            $table->boolean('reverse')->default(false);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('words', function(Blueprint $table)
		{
            $table->dropColumn('fullable');
		});
	}

}
