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

    /*
     * 获得拼音词条(缓存)
     */
    public static function getOrCacheByKey($key)
    {
        if (\Cache::has(\App\WRef::CACHE_KEY_WORD_DIC.$key)) {
            $id = explode('|', \Cache::get(\App\WRef::CACHE_KEY_WORD_DIC.$key));
        } else {
            $word = self::where('key', $key)->first();
            if ($word && $word->id > 0) {
                $id = $word->id;
                \Cache::forever(\App\WRef::CACHE_KEY_WORD_DIC.$key, $id);
            }
        }

        return isset($id) ? $id : false;
    }

    /*
     * 通过拼音匹配出与拼音相关联的词汇元素
     */
    public static function match($pinyin, $words)
    {
        // 首先从缓存中查询相应词条是否存在
        if (is_array($pinyin)) {
            $cPinyin = implode('', $pinyin);
        } else {
            $cPinyin = $pinyin =trim($pinyin);
        }
        if (\Cache::has(\App\WRef::CACHE_KEY_WORD_SEARCH.$cPinyin)) {
            $words = unserialize(\Cache::get(\App\WRef::CACHE_KEY_WORD_SEARCH.$cPinyin));
            return;
        }

        // 拆分全拼拼音形成词条
        $strings = [];
        if (is_array($pinyin)) {
            $strings = $pinyin;
        } else {
            if (!self::split($pinyin, $strings)) {
                // 拆分失败表示拼音不是全拼拼音,则按字符单个拆掉
                $strlen = strlen($pinyin);
                for ($i = 0; $i < $strlen; $i++) {
                    $strings[] = substr($pinyin, $i, 1);
                }
            } else {
                \Cache::forever(\App\WRef::CACHE_KEY_WORD_DIC.$pinyin, implode('|', $strings));
            }
        }

        // 匹配词条
        $word = '';
        $matches = [];
        while ($string = array_shift($strings)) {
            $word .= $string;
            $wordId = self::getOrCacheByKey($word);
            if ($wordId === false) {
                break;
            }

            array_push($matches, $word);
        }

        // 匹配结果处理
        $words[] = $word = array_pop($matches);
        foreach ($matches as $word) {
            $words[] = $word;
        }

        // 继续匹配剩下的词条
        $lenMatched = count($words);
        if (count($strings)) {
            self::match($strings, $words);
            if (count($words) > $lenMatched) {
                \Cache::forever(\App\WRef::CACHE_KEY_WORD_SEARCH.implode('', $strings), serialize(array_slice($words, $lenMatched)));
            }
        }
    }

    /*
     * 全拼拼音分词处理
     */
    public static function split($pinyin, &$words)
    {
        $pinyin = trim($pinyin);
        if (\Cache::has(\App\WRef::CACHE_KEY_WORD_DIC.$pinyin)) {
            $words = explode('|', \Cache::get(\App\WRef::CACHE_KEY_WORD_DIC.$pinyin));
            return true;
        }

        // 音节匹配
        $string = '';
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

        // 拆分结果处理
        if (count($matches)) {
            $words[] = $word = array_pop($matches);

            // 继续筛分剩下的字符串
            $pinyin = substr($pinyin, strlen($word));
            $lenMatched = count($words);
            if ($pinyin) {
                $state = self::split($pinyin, $words);
                if ($state) {
                    \Cache::forever(\App\WRef::CACHE_KEY_WORD_DIC.$pinyin, implode('|', array_slice($words, $lenMatched)));
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

    /**
     * 生成拼音与名称元素的关联
     *
     * @param int $relType 名称元素类型
     * @param int $relId   名称元素类型ID
     * @param string 名称拼音或简拼
     *
     * @return void
     */
    public static function wordRefToLink($relType, $relId, $pinyin)
    {
        $py = self::where('key', $pinyin)->first();
        if (!$py || !$py->id) {
            $py = new self;
            $py->key = $pinyin;
            $py->save();
        }

        \App\WRelation::link($py->id, $relType, $relId);
    }
}
