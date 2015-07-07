<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model {

    public $timestamps = false;

    /**
     * Get all colors.
     */
    public static function allColors($ids)
    {
        return $ids ? self::whereRaw('id in ('.$ids.')')->get() : self::all();
    }

}
