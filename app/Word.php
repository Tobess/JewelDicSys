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
            $id = \Cache::get(\App\WRef::CACHE_KEY_WORD_DIC.$key);
        } else {
            $word = self::where('key', $key)->first();
            if ($word && $word->id > 0) {
                $id = $word->id;
                \Log::debug('mathch:'.$key.'->'.print_r($word->count(), true));
                \Cache::forever(\App\WRef::CACHE_KEY_WORD_DIC.$key, $id);
            }
        }

        return isset($id) ? $id : false;
    }

    /*
     * 通过拼音匹配出与拼音相关联的词汇元素
     */
    private static function match($pinyin, &$words)
    {
        // 首先从缓存中查询相应词条是否存在
        if (is_array($pinyin)) {
            $cPinyin = implode('', $pinyin);
        } else {
            $cPinyin = $pinyin =trim($pinyin);
        }
        if (\Cache::has(\App\WRef::CACHE_KEY_WORD_MATCH.$cPinyin)) {
            $cWords = unserialize(\Cache::get(\App\WRef::CACHE_KEY_WORD_MATCH.$cPinyin));
            foreach ($cWords as $word) {
                $words[] = $word;
            }
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
                \Log::debug('split:'.print_r($strings, true));
                \Cache::forever(\App\WRef::CACHE_KEY_WORD_SPLIT.$pinyin, implode('|', $strings));
            }
        }

        // 匹配词条
        $word = '';
        $matches = [];
        while ($string = array_shift($strings)) {
            $word .= $string;
            $wordId = self::getOrCacheByKey($word);
            //if ($wordId === false) {
            //    break;
            //}

            array_push($matches, $word);
        }

        // 匹配结果处理
        $words[] = $word = array_pop($matches);
        //foreach ($matches as $word) {
        //    $words[] = $word;
        //}
        \Log::debug('word:'.$cPinyin.':'.print_r($words, true));

        // 继续匹配剩下的词条
        $lenMatched = count($words);
        if (count($strings)) {
            self::match($strings, $words);
            if (count($words) > $lenMatched) {
                \Cache::forever(\App\WRef::CACHE_KEY_WORD_MATCH.implode('', $strings), serialize(array_slice($words, $lenMatched)));
            }
        }
    }

    /*
     * 全拼拼音分词处理
     */
    public static function split($pinyin, &$words)
    {
        $pinyin = trim($pinyin);
        if (\Cache::has(\App\WRef::CACHE_KEY_WORD_SPLIT.$pinyin)) {
            $cWords = explode('|', \Cache::get(\App\WRef::CACHE_KEY_WORD_SPLIT.$pinyin));
            foreach ($cWords as $word) {
                $words[] = $word;
            }
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
                    \Cache::forever(\App\WRef::CACHE_KEY_WORD_SPLIT.$pinyin, implode('|', array_slice($words, $lenMatched)));
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
     * @param bool $isFullPy 是否是全拼
     *
     * @return void
     */
    public static function wordRefToLink($relType, $relId, $pinyin, $isFullPy = true)
    {
        if ($isFullPy) {
            $pySplits = [];
            self::split($pinyin, $pySplits);
            if (count($pySplits)) {
                $posPySplits = $oppPySplits = $pySplits;
                // 正向拆分
                $pinyin = '';
                while ($word = array_shift($posPySplits)) {
                    $pinyin .= $word;
                    self::wordLinkSave($relType, $relId, $pinyin);
                }
                // 反向拆分
                array_shift($oppPySplits);
                $pinyin = '';
                while ($word = array_pop($posPySplits)) {
                    $pinyin = $word.$pinyin;
                    self::wordLinkSave($relType, $relId, $pinyin);
                }
                return;
            }
        }

        self::wordLinkSave($relType, $relId, $pinyin);
    }

    /**
     * 添加词根并保存拼音链接关系
     */
    private static function wordLinkSave($relType, $relId, $pinyin) {
        $py = self::where('key', $pinyin)->first();
        if (!$py || !$py->id) {
            $py = new self;
            $py->key = $pinyin;
            $py->save();
        }

        \App\WRelation::link($py->id, $relType, $relId);
    }

    public static function search($query)
    {
        // 从缓存中确定匹配结果
        if (\Cache::has(\App\WRef::CACHE_KEY_WORD_SEARCH.$query)) {
            $results = unserialize(\Cache::get(\App\WRef::CACHE_KEY_WORD_SEARCH.$query));
            return $results;
        }

        // 匹配出相关元素
        $words = [];
        if (\Cache::has(\App\WRef::CACHE_KEY_WORD_MATCH.$query)) {
            $words = unserialize(\Cache::get(\App\WRef::CACHE_KEY_WORD_MATCH.$query));
        } else {
            self::match($query, $words);
            \Log::debug('words->'.$query.':'.print_r($words, true));
            if (count($words)) {
                \Cache::forever(\App\WRef::CACHE_KEY_WORD_MATCH.$query, serialize($words));
            }
        }
        // 如果存在匹配元素则进行规则匹配流程
        if (count($words)) {
            $types = [];
            $typeLinks = [];
            // 取出词根包含的名称组成元素类型集合
            self::getWordsLinkRelation($words, $typeLinks, $types);

            // 用词条的匹配的类型集合与系统名称规则定义的配置对比分析找出与之匹配的规则
            if (count($types)) {
                \Log::debug('types->'.print_r($types, true));
                $matchRules = self::matchRules($types);
                \Log::debug('match->rules:'.print_r($matchRules, true));

                // 用规则生成结果列表
            }
        }

        // 结合名称生成规则分析元素合成名称待选项目
        $results = [];
        $rules = \App\Rule::getRulesAndCache();


        // 缓存数据
        \Cache::put(\App\WRef::CACHE_KEY_WORD_SEARCH.$query, serialize($results), \App\WRef::CACHE_KEY_WORD_SEARCH_EXPIRE);

        return $results;
    }

    /**
     * 获取已匹配的词根的词汇组成关系
     */
    private static function getWordsLinkRelation($words, &$relTypeQue, &$relTypes)
    {
        foreach ($words as $index => $pinyin) {
            if (($wId = self::getOrCacheByKey($pinyin)) !== false) {
                $links = \App\WRelation::getLinksAndCacheByWordID($wId);
                foreach ($links as $link) {
                    if (!isset($relTypeQue[$link->rel_type]) || !in_array($link->rel_id, $relTypeQue[$link->rel_type])) {
                        $relTypeQue[$link->rel_type][] = $link->rel_id;
                    }
                    if (!isset($relTypes[$index]) || !in_array($link->rel_type, $relTypes[$index])) {
                        $relTypes[$index][] = $link->rel_type;
                    }
                }
            }
        }
    }

    private static function matchRules($types)
    {
        if (!count($types)) return false;

        // TODO 暂时支持单一规则当时。复杂规则下一步处理
        // 将已经匹配的名称组合元素生成正则匹配表达式
        $regex = '';
        $linkExt = '';
        foreach ($types as $type) {
            $regex .= $linkExt.'(\+['.implode(',', $type).']\+)';
            $linkExt = '+.+';
        }
        \Log::debug('regex->:'.$regex);
        if (!empty($regex)) {
            if (\Cache::has(\App\WRef::CACHE_KEY_RULE_MATCH.$regex)) {
                $matches = unserialize(\Cache::get(\App\WRef::CACHE_KEY_RULE_MATCH.$regex));
                return $matches;
            }

            $rules = \App\Rule::getRulesAndCache();
            $matches = [];
            foreach ($rules as $rule) {
                if (preg_match('/'.$regex.'/', $rule)) {
                    $matches[] = $rule;
                }
            }
            if (count($matches)) {
                \Cache::put(\App\WRef::CACHE_KEY_RULE_MATCH.$regex, serialize($matches), \App\WRef::CACHE_KEY_WORD_SEARCH_EXPIRE);
                return $matches;
            }
        }

        return false;
    }
}
