<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Word extends Model {

    public $timestamps = false;

    private static $typeDef = 0;// 完整拼音型
    private static $typeOpp = 2;// 反向拆分的拼音词根
    private static $typePos = 1;// 正向拆分的拼音词根

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
    public static function getOrCacheByKey($key, $positive = true)
    {
        if (\Cache::has(\App\WRef::CACHE_KEY_WORD_DIC.md5($key))) {
            $id = \Cache::get(\App\WRef::CACHE_KEY_WORD_DIC.md5($key));
        } else {
            $typeDef = self::$typeDef;
            $typeDes = $positive ? self::$typePos : self::$typeOpp;
            $word = self::where('key', $key)
                ->where(function($query) use ($typeDef, $typeDes) {
                    $query->where('type', $typeDef)->orWhere('type', $typeDes);
                })->first();
            if ($word && $word->id > 0) {
                $id = $word->id;
                \Cache::forever(\App\WRef::CACHE_KEY_WORD_DIC.md5($key), $id);
            }
        }

        return isset($id) ? $id : false;
    }

    /*
     * 通过拼音匹配出与拼音相关联的词汇元素
     */
    private static function match($pinyin, &$words, $positive = true)
    {
        // 首先从缓存中查询相应词条是否存在
        if (is_array($pinyin)) {
            $cPinyin = implode('', $pinyin);
        } else {
            $cPinyin = $pinyin =trim($pinyin);
        }
        if (\Cache::has(\App\WRef::CACHE_KEY_WORD_MATCH.intval($positive).':'.md5($cPinyin))) {
            $cWords = unserialize(\Cache::get(\App\WRef::CACHE_KEY_WORD_MATCH.intval($positive).':'.md5($cPinyin)));
            foreach ($cWords as $word) {
                $words[] = $word;
            }
            return;
        }

        // 拆分全拼拼音形成词条
        $wordList = [];
        $strings = [];
        if (is_array($pinyin)) {
            $strings = $pinyin;
            $wordList = array_merge($strings);
        } else {
            if (!self::split($pinyin, $strings)) {
                // 拆分失败表示拼音不是全拼拼音,则按字符单个拆掉
                $strlen = strlen($pinyin);
                for ($i = 0; $i < $strlen; $i++) {
                    $strings[] = substr($pinyin, $i, 1);
                }
            }
            $wordList = array_merge($strings);
        }

        // 匹配词条
        $word = '';
        $matches = [];
        $matchToIdx = 1;
        $idx = 1;
        while ($string = (!$positive ? array_pop($strings) : array_shift($strings))) {
            $word .= $string;
            $wordId = self::getOrCacheByKey($word, $positive);

            if ($wordId !== false) {
                array_push($matches, $word);
                $matchToIdx = $idx;
            }

            $idx++;
        }

        // 匹配结果处理
        if (count($matches)) {
            $words[] = $word = array_pop($matches);
        }

        // 继续匹配剩下的词条
        $strings = $positive ? array_slice($wordList, $matchToIdx) : array_slice($wordList, 0, count($wordList) - $matchToIdx);
        if (is_array($strings) && count($strings) > 0) {
            $lenMatched = count($words);
            self::match($strings, $words, $positive);
            if (count($words) > $lenMatched) {
                \Cache::put(\App\WRef::CACHE_KEY_WORD_MATCH.intval($positive).':'.md5(implode('', $strings)),
                    serialize(array_slice($words, $lenMatched)), \App\WRef::CACHE_KEY_WORD_SEARCH_EXPIRE);
            }
        }
    }

    /*
     * 全拼拼音分词处理
     */
    public static function split($pinyin, &$words)
    {
        $pinyin = trim($pinyin);
        if (\Cache::has(\App\WRef::CACHE_KEY_WORD_SPLIT.md5($pinyin))) {
            $cWords = explode('|', \Cache::get(\App\WRef::CACHE_KEY_WORD_SPLIT.md5($pinyin)));
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
        $pinyins = \App\WPinyin::getPinyinIndex();// 获得所有音节
        $matches = [];
        while ($char = array_shift($chars)) {
            $string .= $char;
            if (in_array($string, $pinyins)) {
                $cLen = count($chars);
                if ($cLen &&
                    (// 尝试向后推演，判断是否是一个完整的拼音的部分，因为拼音长度最多6故推演5次尝试
                        ($cLen >= 1 && in_array($string.$chars[0], $pinyins)) ||
                        ($cLen >= 2 && in_array($string.$chars[1], $pinyins)) ||
                        ($cLen >= 3 && in_array($string.$chars[2], $pinyins)) ||
                        ($cLen >= 4 && in_array($string.$chars[3], $pinyins)) ||
                        ($cLen >= 5 && in_array($string.$chars[4], $pinyins))
                    )) {
                    continue;
                } else {
                    array_push($matches, $string);
                    $string = '';
                }
            }
        }

        if (count($matches) && implode('', $matches) == $pinyin) {
            $words = $matches;
            \Cache::forever(\App\WRef::CACHE_KEY_WORD_SPLIT.md5($pinyin), implode('|', $matches));
            return true;
        } else {
            return false;
        }
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
        self::wordLinkSave($relType, $relId, $pinyin);
        if ($isFullPy) {
            $pySplits = [];
            self::split($pinyin, $pySplits);
            if (count($pySplits)) {
                $posPySplits = array_merge($pySplits);
                $oppPySplits = array_merge($pySplits);

                // 正向拆分
                $pinyin = '';
                array_pop($posPySplits);
                while ($word = array_shift($posPySplits)) {
                    $pinyin .= $word;
                    self::wordLinkSave($relType, $relId, $pinyin, self::$typePos);
                }

                // 反向拆分
                array_shift($oppPySplits);
                $pinyin = '';
                while ($word = array_pop($oppPySplits)) {
                    $pinyin = $word.$pinyin;
                    self::wordLinkSave($relType, $relId, $pinyin, self::$typeOpp);
                }
            }
        }
    }

    /**
     * 添加词根并保存拼音链接关系
     */
    private static function wordLinkSave($relType, $relId, $pinyin, $pyType = 0) {
        $py = self::where('key', $pinyin)->first();
        if (!$py || !$py->id) {
            $py = new self;
            $py->key = strtolower($pinyin);
            $py->type = in_array($pyType, [1, 2]) ? $pyType : 0;
            $py->save();
        }

        \App\WRelation::link($py->id, $relType, $relId);
    }

    /**
     * 词库匹配搜索
     *
     * @param string $query 简拼或全拼
     * @param bool $positive 是否正向搜索
     *
     * @return array
     */
    public static function search($query, $positive = true)
    {
        // 从缓存中确定匹配结果
        if (\Cache::has(\App\WRef::CACHE_KEY_WORD_SEARCH.intval($positive).':'.md5($query))) {
            $results = unserialize(\Cache::get(\App\WRef::CACHE_KEY_WORD_SEARCH.intval($positive).':'.md5($query)));
            return $results;
        }

        // 匹配出相关元素
        $words = [];
        if (\Cache::has(\App\WRef::CACHE_KEY_WORD_MATCH.intval($positive).':'.md5($query))) {
            $words = unserialize(\Cache::get(\App\WRef::CACHE_KEY_WORD_MATCH.intval($positive).':'.md5($query)));
        } else {
            self::match($query, $words, $positive);
            if (count($words)) {
                \Cache::put(\App\WRef::CACHE_KEY_WORD_MATCH.intval($positive).':'.md5($query),
                    serialize($words), \App\WRef::CACHE_KEY_WORD_SEARCH_EXPIRE);
            }
        }

        // 如果存在匹配元素则进行规则匹配流程
        $results = [];
        if (count($words)) {
            $types = [];
            $typeLinks = [];
            // 取出词根包含的名称组成元素类型集合
            $wordIndexLinkToTypes = self::getWordsLinkRelation($words, $typeLinks, $types, $positive);
            // 用词条的匹配的类型集合与系统名称规则定义的配置对比分析找出与之匹配的规则
            if (count($types) >= 2) {
                // 过滤掉无效的匹配元素
                $newTypes = array_merge($types);
                $first = array_shift($newTypes);
                $typeToRuleString = count($newTypes) ? self::typeToRuleModel($first, array_shift($newTypes), $newTypes) : $first;

                $matchRules = self::matchRules($types, $typeToRuleString);
                if ($matchRules) {
                    $relValTree = [];// 匹配的相关元素
                    $ruleTree = [];// 匹配的名称规则
                    $matchedResults = [];// 合成的商品名称
                    foreach ($matchRules as $ruleObj) {
                        $gName = [];
                        $ruleId = $ruleObj['ruleId'];
                        $rule = $ruleObj['rule'];// 系统设置名称规则
                        $realRule = $ruleObj['realRule'];// 匹配到的名称规则中的部分
                        $rCfg = explode('+', $rule);
                        $gNameCount = 1;
                        foreach ($rCfg as $index => $rel) {
                            $rel = trim($rel);
                            if (is_array(\App\WRef::getRefById($rel))) {
                                // 根据真实的规则取出无效的匹配元素对象
                                $relValues = [];
                                if (isset($typeLinks[$rel])) {
                                    foreach ($typeLinks[$rel] as $relId => $relIdx) {
                                        foreach ($realRule as $rRule) {
                                            if ($rRule[$relIdx] == $rel) {
                                                $relValues[] = $relId;
                                            }
                                        }
                                    }
                                }

                                $tValues = \App\WRef::getRelationNameByType($rel,
                                    count($relValues) ? 'id in ('.implode(',', $relValues).')' : '');
                                $tRelIds = [];
                                foreach ($tValues as $relObj) {
                                    if (in_array($rel, [1,2,3]) && \App\WRef::relationHasParentByTypeAndId($rel, $relObj->id)) continue;

                                    $relObj->rel_type = $rel;
                                    $relValTree[$rel][$relObj->id] = $relObj;
                                    if (!in_array($relObj->id, $tRelIds)) {
                                        $tRelIds[] = $relObj->id;
                                    }
                                }
                                $gNameCount *= count($tRelIds);
                                $gName[] = ['type'=>$rel, 'data'=>$tRelIds];
                                $prevRel = $rel;
                            } else {
                                $gName[] = $rel;
                            }
                        }
                        if (count($gName) && $gNameCount <= 400000) {
                            $ruleTree[$ruleId] = $gName;
                            // 用规则生成结果列表
                            $newGNames = array_merge($gName);
                            $item = array_shift($newGNames);
                            $first = self::parseGNameItem($item, $relValTree);
                            $dicWords = count($newGNames) ? self::mergeDicWords($first, self::parseGNameItem(array_shift($newGNames), $relValTree), $newGNames, $relValTree) : $first;
                            $matchedResults = array_merge($matchedResults, $dicWords);
                        }
                    }

                    $matchedResults = array_sort($matchedResults, function($item)
                    {
                        return count($item['refs']);
                    });
                    $results = ['relTree'=>$relValTree, 'words'=>array_values($matchedResults)];
                }
            }
        }

        if (count($results['words'])) {
            // 缓存数据
            \Cache::put(\App\WRef::CACHE_KEY_WORD_SEARCH.intval($positive).':'.md5($query), serialize($results), \App\WRef::CACHE_KEY_WORD_SEARCH_EXPIRE);
        }

        return $results;
    }

    /**
     * 解析一个匹配部分用于名称生成
     */
    private static function parseGNameItem($item, &$relValTree)
    {
        if (is_array($item)) {
            $items = [];
            foreach ($item['data'] as $id) {
                $type = $item['type'];
                $items[] = ['title'=>$relValTree[$type][$id]->name, 'refs'=>[$id.':'.$type]];
                if (in_array($type, [1,2,3])) {
                    // 检测别名是否存在并加入名称生成中
                    $aliases = \App\WAlias::getAliasesByTypeAndId($type, $id);
                    if (count($aliases)) {
                        foreach ($aliases as $alias) {
                            $items[] = ['title'=>$alias->name, 'refs'=>[$id.':'.$type]];
                        }
                    }
                }
            }
        } else {
            $items = [['title'=>$item, 'refs'=>[]]];
        }

        return $items;
    }

    /**
     * 珠宝名称合成
     */
    private static function mergeDicWords($first, $second, &$sources, &$relValTree) {
        $newFirst = [];
        foreach ($first as $fEle) {
            foreach ($second as $sEle) {
                $title = $fEle['title'].$sEle['title'];
                $refs = array_merge($fEle['refs'], $sEle['refs']);
                $newFirst[] = ['title'=>$title, 'refs'=>$refs];
            }
        }

        return count($sources) ? self::mergeDicWords($newFirst, self::parseGNameItem(array_shift($sources), $relValTree), $sources, $relValTree) : $newFirst;
    }

    /**
     * 将匹配的元素类型组成各种规则组合
     */
    private static function typeToRuleModel($first, $second, &$sources)
    {
        $newFirst = [];
        foreach ($first as $fEle) {
            foreach ($second as $sEle) {
                $newFirst[] = $fEle.','.$sEle;
            }
        }

        return count($sources) ? self::typeToRuleModel($newFirst, array_shift($sources), $sources) : $newFirst;
    }

    /**
     * 获取已匹配的词根的词汇组成关系
     */
    private static function getWordsLinkRelation($words, &$relTypeQue, &$relTypes, $positive = true)
    {
        $realWordLinkToMatchedTypes = [];// 匹配出来的词根的索引顺序与有效的元素类型的位置链接
        $words = $positive ? $words : array_reverse($words);
        $mCount = 0;
        $rIndex = 0;
        foreach ($words as $index => $pinyin) {
            if (($wId = self::getOrCacheByKey($pinyin, $positive)) !== false) {
                $links = \App\WRelation::getLinksAndCacheByWordID($wId);
                foreach ($links as $link) {
                    if (!isset($relTypeQue[$link->rel_type]) || !isset($relTypeQue[$link->rel_type][$link->rel_id])) {
                        $relTypeQue[$link->rel_type][$link->rel_id] = $rIndex;
                        $mCount++;
                    }
                    if (!isset($relTypes[$rIndex]) || !in_array($link->rel_type, $relTypes[$rIndex])) {
                        $relTypes[$rIndex][] = $link->rel_type;
                    }

                    $realWordLinkToMatchedTypes[$index] = $rIndex;
                    if ($mCount > 10) {
                        abort(200, '', []);
                    }
                }
                $rIndex++;
            }
        }

        return $realWordLinkToMatchedTypes;
    }

    /**
     * 规则匹配
     *
     * @param array $types 已匹配到的元素类型
     * @param array $typeToRules 用元素匹配类型生成的可能的规则组合
     * @return array false for fail
     */
    private static function matchRules($types, $typeToRules)
    {
        if (!count($types)) return false;

        // TODO 暂时支持单一规则当时。复杂规则下一步处理
        // 将已经匹配的名称组合元素生成正则匹配表达式
        $regex = '';
        $linkExt = '';
        foreach ($types as $type) {
            $regex .= $linkExt.'(\+['.implode(',', $type).']){1}';
            $linkExt = '+(.){0}+';
        }

        if (!empty($regex)) {
            if (\Cache::has(\App\WRef::CACHE_KEY_RULE_MATCH.md5($regex))) {
                $matches = unserialize(\Cache::get(\App\WRef::CACHE_KEY_RULE_MATCH.md5($regex)));
                return $matches;
            }

            $rules = \App\Rule::getRulesAndCache();
            $matches = [];
            foreach ($rules as $ruleId => $rule) {
                $rCfg = explode('+', $rule);
                $justEle = [];
                foreach ($rCfg as $ele) {
                    if (is_array(\App\WRef::getRefById($ele))) {
                        $justEle[] = $ele;
                    }
                }
                if (count($justEle) && preg_match('/'.$regex.'/', '+'.implode('+', $justEle))) {
                    // 筛选出正确的匹配规则
                    $tMatchRule = [];
                    foreach ($typeToRules as $_rule) {
                        $_matchEles = [];
                        $tRuleEle = explode(',', $_rule);
                        foreach ($justEle as $ele) {
                            if (in_array($ele, $tRuleEle)) {
                                $_matchEles[] = $ele;
                            }
                        }
                        if (implode(',', $_matchEles) == $_rule) {
                            $tMatchRule[] = $tRuleEle;
                        }
                    }
                    $matches[] = ['ruleId'=>$ruleId, 'rule'=>$rule, 'realRule'=>$tMatchRule];
                }
            }
            if (count($matches)) {
                \Cache::put(\App\WRef::CACHE_KEY_RULE_MATCH.md5($regex), serialize($matches), \App\WRef::CACHE_KEY_WORD_SEARCH_EXPIRE);
                return $matches;
            }
        }

        return false;
    }
}
