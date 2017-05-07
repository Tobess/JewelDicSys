<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropStandardUnique extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('s_colors', function(Blueprint $table){
            $table->dropUnique('s_colors_name_unique');
        });
        Schema::table('s_certificates', function(Blueprint $table){
            $table->dropUnique('s_certificates_name_unique');
        });
        Schema::table('s_clarities', function(Blueprint $table){
            $table->dropUnique('s_clarities_name_unique');
        });
        Schema::table('s_cuts', function(Blueprint $table){
            $table->dropUnique('s_cuts_name_unique');
        });
        Schema::table('s_grades', function(Blueprint $table){
            $table->dropUnique('s_grades_name_unique');
        });
        Schema::table('s_shapes', function(Blueprint $table){
            $table->dropUnique('s_shapes_name_unique');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
