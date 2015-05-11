<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWordsRelationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('words_relations', function(Blueprint $table)
        {
            $table->primary(array('word_id', 'rel_type', 'rel_id'));
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('words_relations', function(Blueprint $table)
        {
            $table->dropPrimary(array('word_id', 'rel_type', 'rel_id'));
        });
	}

}
