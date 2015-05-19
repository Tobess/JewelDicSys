<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model {

    public $timestamps = false;

    /**
     * 获得所有名称规则并缓存
     */
    public static function getRulesAndCache()
    {
        if (\Cache::has(\App\WRef::CACHE_KEY_RULE_IDX)) {
            $rules = unserialize(\Cache::get(\App\WRef::CACHE_KEY_RULE_IDX));
        } else {
            $rList = self::all();
            $rules = [];
            foreach ($rList as $rule) {
                $rule->configure && ($rules[$rule->id] = $rule->configure);
            }
            if (count($rules)) {
                \Cache::put(\App\WRef::CACHE_KEY_RULE_IDX, serialize($rules), \App\WRef::CACHE_KEY_WORD_SEARCH_EXPIRE);
            }
        }

        return $rules;
    }

}
