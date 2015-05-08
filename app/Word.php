<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Word extends Model {

    public $timestamps = false;

    /**
     * 拼音码的相关联的元素
     */
    public function relations()
    {
        return $this->hasMany('App\WRelation');
    }

    public static function match($pinyin, $callback)
    {
        // 首先从缓存中查询相应词条是否存在
        $pinyin = trim($pinyin);
        if (\Cache::has('search:words:'.$pinyin)) {
            $cWords = explode('|', \Cache::get('search:words:'.$pinyin));
            foreach ($cWords as $word) {
                $words[] = $word;
            }

            return true;
        }

        $pinyin = trim($pinyin);
        if (!$pinyin || !count($pinyin)) return;

        $hasCallback = is_callable($callback);// 检测回调是否合法

        $string = "";
        $chars = explode('', $pinyin);
        while ($char = array_shift($chars)) {
            $string .= $char;

            // TODO 处理缓存
            $wInfo = self::where('key', $string)->first();
            if (!($wInfo && $wInfo->id)) {
                if ($hasCallback) {
                    call_user_func($callback, $string);
                }
                break;
            }
        }
    }

    /*
     * 拼音分词处理
     */
    public static function split($pinyin, &$words)
    {
        $pinyin = trim($pinyin);
        if (\Cache::has('search:words:'.$pinyin)) {
            $cWords = explode('|', \Cache::get('search:words:'.$pinyin));
            foreach ($cWords as $word) {
                $words[] = $word;
            }

            return true;
        }

        $string = "";
        $chars = [];
        $strlen = strlen($pinyin);
        for ($i = 0; $i < $strlen; $i++) {
            $chars[] = substr($pinyin, $i, 1);
        }
        $pinyins = WPinyin::getAllAndCache();// 获得所有音节
        $matches = [];
        while ($char = array_shift($chars)) {
            $string .= $char;
            if (in_array($string, $pinyins)) {
                array_push($matches, $string);
            }
        }

        if (count($matches)) {
            $words[] = $word = array_pop($matches);

            $pinyin = substr($pinyin, strlen($word));
            $lenMatched = count($words);
            if ($pinyin) {
                $state = self::split($pinyin, $words);
                if ($state) {
                    \Cache::forever('search:words:'.$pinyin, implode('|', array_slice($words, $lenMatched)));
                }

                return $state;
            } else {
                return true;
            }
        }

        return false;
    }

    /**
     * 获得子字符串在主串中出现的所有位置.
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return array
     */
    public static function strpos($haystack, $needle)
    {
        $offset = 0;
        $poses = array();

        $count = substr_count($str, $char);
        for($i = 0; $i < $count; $i++){
            $offset = strpos($str, $char, $offset);
            $poses[] = $offset;

            $offset = $offset+1;
        }

        return $poses;
    }
}
