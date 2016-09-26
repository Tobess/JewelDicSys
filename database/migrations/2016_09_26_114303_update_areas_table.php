<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAreasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    $path = database_path('areas.sql');
        $areas = file_get_contents($path);
        foreach (explode("\n", $areas) as $sql) {
            if ($sql && strpos($sql, 'INSERT INTO') !== false) {
                \DB::statement($sql);
            }
        }
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
