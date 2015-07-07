<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model {

    public $timestamps = false;

    /**
     * Get all grades.
     */
    public static function allGrades($ids)
    {
        return $ids ? self::whereRaw('id in ('.$ids.')')->get() : self::all();
    }

}
