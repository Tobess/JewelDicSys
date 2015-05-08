<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class WPinyin extends Model {

    protected $table = 'pinyin';

    public $timestamps = false;

    /*
     * 获得拼音音节
     */
    public static function getAllAndCache()
    {
        if (\Cache::has('pinyin')) {
            $pinyins = explode('|', \Cache::get('pinyin'));
        } else {
            $pinyins = [];
            foreach (self::all() as $pinyin) {
                $pinyins[] = $pinyin->key;
            }

            \Cache::forever('pinyin', implode('|', $pinyins));
        }

        return $pinyins;
    }
}
