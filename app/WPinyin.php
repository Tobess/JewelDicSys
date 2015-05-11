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
        if (\Cache::has(\App\WRef::CACHE_KEY_PINYIN_IDX)) {
            $pinyins = explode('|', \Cache::get(\App\WRef::CACHE_KEY_PINYIN_IDX));
        } else {
            $pinyins = [];
            foreach (self::all() as $pinyin) {
                $pinyins[] = $pinyin->key;
            }

            \Cache::forever(\App\WRef::CACHE_KEY_PINYIN_IDX, implode('|', $pinyins));
        }

        return $pinyins;
    }
}
