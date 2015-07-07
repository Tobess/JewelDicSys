<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Style extends Model {

    public $timestamps = false;

    /**
     * Get all styles.
     */
    public static function allStyles($ids)
    {
        return $ids ? self::whereRaw('id in ('.$ids.')')->get() : self::all();
    }

}
