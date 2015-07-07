<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Craft extends Model {

    public $timestamps = false;

    /**
     * Get all crafts.
     */
    public static function allCrafts($ids)
    {
        return $ids ? self::whereRaw('id in ('.$ids.')')->get() : self::all();
    }

}
