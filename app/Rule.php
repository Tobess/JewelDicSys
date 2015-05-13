<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model {

    public $timestamps = false;

    /**
     * 获得所有名称规则并缓存
     */
    public static function getRulesAndCache()
    {
        if (\Cache::has('rules')) {
            $rules = unserialize(\Cache::get('rules'));
        } else {
            $rList = self::all();
            $rules = []
            foreach ($rList as $rule) {
                $rule->configure && ($rules[] = $rule->configure);
            }
            if (count($rules)) {
                \Cache::put('rules', serialize($rules), \App\WRef::CACHE_KEY_WORD_SEARCH_EXPIRE);
            }
        }

        return $rules;
    }

}
