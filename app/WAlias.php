<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class WAlias extends Model {

    protected $table = 'aliases';

    public $timestamps = false;

    /**
     * 获得指定类型指定元素的别名集合
     */
    public static function getAliasesByTypeAndId($type, $id)
    {
        if (\Cache::has(\App\WRef::CACHE_KEY_ALIAS.$type.':'.$id)) {
            $aliases = unserialize(\Cache::get(\App\WRef::CACHE_KEY_ALIAS.$type.':'.$id));
        } else {
            $aliases = self::where('rel_type', $type)->where('rel_id', $id)->get();
            if (count($aliases) > 0) {
                \Cache::put(\App\WRef::CACHE_KEY_ALIAS.$type.':'.$id, serialize($aliases), \App\WRef::CACHE_KEY_WORD_SEARCH_EXPIRE);
            }
        }

        return $aliases;
    }

}
