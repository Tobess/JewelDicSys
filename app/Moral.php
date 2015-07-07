<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Moral extends Model {

    public $timestamps = false;

    /**
     * Get all morals.
     */
    public static function allMorals($ids)
    {
        return $ids ? self::whereRaw('id in ('.$ids.')')->get() : self::all();
    }

}
