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
    public static function getOrCacheByKey($key, $positive = true)
    {
        if (\Cache::has(\App\WRef::CACHE_KEY_WORD_DIC.md5($key))) {
            $id = \Cache::get(\App\WRef::CACHE_KEY_WORD_DIC.md5($key));
        } else {
            $word = self::where('key', $key)
                ->where(function($query) use ($positive) {
                    $query->where('fullable', 1)->orWhere($positive ? 'positive' : 'reverse', 1);
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
            $cPinyin = $pinyin = trim($pinyin);
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
        $metalPinyins = \App\WPinyin::getMetalPinyinIndex();// 贵金属音节
        $matches = [];
        while ($char = array_shift($chars)) {
            // 关于贵金属特殊拼音组合预先处理
            if ($string == '') {
                $mChar = $char;
                $mLen = 0;
                foreach ($chars as $_idx => $_char) {
                    $mChar .= $_char;
                    if (in_array($mChar, $metalPinyins)) {
                        $mLen = $_idx+1;
                        continue;
                    }

                    if ($_idx == 7) {
                        break;
                    }
                }
                if ($mLen > 0) {
                    $string .= $char;
                    while ($mLen > 0) {
                        $string .= array_shift($chars);
                        $mLen--;
                    }
                    array_push($matches, $string);
                    $string = '';
                    continue;
                }
            }

            // 正常的拼音拆分匹配
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
        self::wordLinkSave($relType, $relId, $pinyin, true);
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
                    self::wordLinkSave($relType, $relId, $pinyin, false, true, false);
                }

                // 反向拆分
                array_shift($oppPySplits);
                $pinyin = '';
                while ($word = array_pop($oppPySplits)) {
                    $pinyin = $word.$pinyin;
                    self::wordLinkSave($relType, $relId, $pinyin, false, false, true);
                }
            }
        }
    }

    /**
     * 添加词根并保存拼音链接关系
     */
    private static function wordLinkSave($relType, $relId, $pinyin,
                                         $fullable = false, $positive = false, $reverse = false) {
        $pinyin = strtolower($pinyin);
        $py = self::where('key', $pinyin)->first();
        if (!$py || !$py->id) {
            $py = new self;
            $py->key = $pinyin;
        }

        if ($fullable) {
            $py->fullable = 1;
        }
        if ($positive) {
            $py->positive = 1;
        }
        if ($reverse) {
            $py->reverse = 1;
        }
        $py->save();

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
        $query = strtolower($query);

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
\Log::info(print_r($words, true));
        // 如果存在匹配元素则进行规则匹配流程
        $results = [];
        if (count($words)) {
            $types = [];
            $typeLinks = [];
            // 取出词根包含的名称组成元素类型集合
            $wordIndexLinkToTypes = self::getWordsLinkRelation($words, $typeLinks, $types, $positive);
            \Log::info(print_r($types, true));
            // 用词条的匹配的类型集合与系统名称规则定义的配置对比分析找出与之匹配的规则
            if ($wordIndexLinkToTypes !== false && count($types) >= 2) {
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
                                // 根据真实的规则去除无效的匹配元素对象
                                $relValues = [];
                                if (isset($typeLinks[$rel])) {
                                    foreach ($typeLinks[$rel] as $relId => $relIdx) {
                                        if ($realRule[$relIdx] == $rel) {
                                            $relValues[] = $relId;
                                        }
                                    }
                                }

                                $tValues = \App\WRef::getRelationNameByType($rel,
                                    count($relValues) ? 'id in ('.implode(',', $relValues).')' : '');
                                $tRelIds = [];
                                foreach ($tValues as $relObj) {
                                    if (in_array($rel, [1,2,3]) && \App\WRef::relationIsParentNodeByTypeAndId($rel, $relObj->id)) continue;

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

                        if (count($gName) && $gNameCount <= 20) {
                            $ruleTree[$ruleId] = $gName;
                            // 用规则生成结果列表
                            $newGNames = array_merge($gName);
                            $item = array_shift($newGNames);
                            // 通过分析最优匹配来生成排序
                            $prevMatchSubPYIdx = -1;
                            $first = self::parseGNameItem($item, $relValTree, $prevMatchSubPYIdx, $words);
                            $dicWords = count($newGNames) ?
                                self::mergeDicWords($first,
                                    self::parseGNameItem(array_shift($newGNames), $relValTree, $prevMatchSubPYIdx, $words),
                                    $newGNames, $relValTree, $prevMatchSubPYIdx, $words) : $first;
                            \Log::info('>>>'.print_r($first, true));
                            $matchedResults = array_merge($matchedResults, $dicWords);
                        }
                    }

                    $matchedResults = array_sort($matchedResults, function($item)
                    {
                        return count($item['refs']);
                    });

                    // 检测生成结果中的组成元素是否还能确定出为饱含在规则的元素
                    self::checkLinkData($matchedResults, $relValTree);

                    $results = ['relTree'=>$relValTree, 'words'=>array_values($matchedResults)];
                }
            }
        }

        if (isset($results['words']) && count($results['words'])) {
            // 缓存数据
            \Cache::put(\App\WRef::CACHE_KEY_WORD_SEARCH.intval($positive).':'.md5($query), serialize($results), \App\WRef::CACHE_KEY_WORD_SEARCH_EXPIRE);
        }

        return $results;
    }

    /**
     * 通过分析生成的返回结果确定是否存在不包含在规则中的元素
     */
    private static function checkLinkData(&$words, &$relTree)
    {
        foreach ($words as &$word) {
            // 获得数据的链接关系
            $linkRefs = [];
            foreach ($word['refs'] as $ref) {
                $refArr = explode(':', $ref);
                if (count($refArr) == 2) {
                    $links = \App\DLink::getLinksBySrcTypeID($refArr[1], $refArr[0]);
                    foreach ($links as $link) {
                        if (!isset($relTree[$link->rel_type][$link->id])) {
                            $relTree[$link->rel_type][$link->id] = $link;
                        }
                        $linkRefs[] = $link->id.':'.$link->rel_type;
                    }
                }
            }

            // 将通过关联数据确定的数据合并的返回结果中
            $word['refs'] = array_merge($word['refs'], $linkRefs);
        }
    }

    /**
     * 解析一个匹配部分用于名称生成
     */
    private static function parseGNameItem($item, &$relValTree, &$prevMatchSubPYIdx, &$words)
    {
        $currentMatchWordIdx = $prevMatchSubPYIdx + 1;// 当前要匹配的拼音子词
        $prevMatchSubPYIdx = $currentMatchWordIdx;
        $items = [];

        if (is_array($item)) {
            // 词条存在
            if (isset($words[$currentMatchWordIdx])) {
                $_mWord = $words[$currentMatchWordIdx];
                foreach ($item['data'] as $id) {
                    $type = $item['type'];

                    // TODO 当前匹配名称是严格模式，每次无论标准名称还是别名只会从中找出真实确认的那一个
                    // TODO 后期是否需要支持通过真实匹配取出其标准名称及所有别名
                    $strictMode = true;
                    // 匹配标准名称
                    if (!$strictMode ||
                        (strcasecmp($_mWord, $relValTree[$type][$id]->pinyin) === 0 || strcasecmp($_mWord, $relValTree[$type][$id]->letter) === 0)) {
                        $items[] = ['title'=>$relValTree[$type][$id]->name, 'refs'=>[$id.':'.$type]];
                    }

                    // 当标准名称无法匹配时则匹配别名
                    if (!count($items) && in_array($type, [1,2,3])) {
                        // 检测别名是否存在并加入名称生成中
                        $aliases = \App\WAlias::getAliasesByTypeAndId($type, $id);
                        if (count($aliases)) {
                            foreach ($aliases as $alias) {
                                if (!$strictMode ||
                                    (strcasecmp($_mWord, $alias->pinyin) === 0 || strcasecmp($_mWord, $alias->letter) === 0)) {
                                    $items[] = ['title'=>$alias->name, 'refs'=>[$id.':'.$type]];
                                }
                            }
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
    private static function mergeDicWords($first, $second, &$sources, &$relValTree, &$prevMatchSubPYIdx, &$words) {
        $newFirst = [];
        foreach ($first as $fEle) {
            foreach ($second as $sEle) {
                $title = $fEle['title'].$sEle['title'];
                $refs = array_merge($fEle['refs'], $sEle['refs']);
                $newFirst[] = ['title'=>$title, 'refs'=>$refs];
            }
        }

        return count($sources) ?
            self::mergeDicWords($newFirst, self::parseGNameItem(array_shift($sources), $relValTree, $prevMatchSubPYIdx, $words),
                $sources, $relValTree, $prevMatchSubPYIdx, $words) : $newFirst;
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
                }
                $rIndex++;
            }
        }

        if ($mCount > 100) {
            return false;
        }

        return $realWordLinkToMatchedTypes;
    }

    /**
     * 规则匹配
     *
     * @param array $types 已匹配到的元素类型
     * @param array $typeToRules 用元素匹配类型生成的可能的规则组合
     * @return array | false for fail
     */
    private static function matchRules($types, $typeToRules)
    {
        if (!count($types)) return false;

        // TODO 暂时支持单一规则当时。复杂规则下一步处理
        // 将已经匹配的名称组合元素生成正则匹配表达式
        $regex = '';
        $linkExt = '';
        $lenTypeEles = count($types);
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
            foreach ($rules as $ruleId => $rItem) {
                $rExp = $rItem['exp'];
                $rElesExp = $rItem['elements'];
                $rEles = explode(',', $rElesExp);
                $rCfg = explode('+', $rExp);
                $lenEles = count($rEles);
                if ($lenEles > 0 && $lenEles == $lenTypeEles && preg_match('/'.$regex.'/', '+'.implode('+', $rEles))) {
                    // 筛选出正确的匹配规则
                    $hasMatched = true;
                    foreach ($types as $_idx => $_type) {
                        if (!in_array($rEles[$_idx], $_type)) {
                            $hasMatched = false;
                            break;
                        }
                    }

                    if ($hasMatched) {
                        $matches[] = ['ruleId'=>$ruleId, 'rule'=>$rExp, 'realRule'=>$rEles];
                    }
                }
            }
            if (count($matches)) {
                \Cache::put(\App\WRef::CACHE_KEY_RULE_MATCH.md5($regex), serialize($matches), \App\WRef::CACHE_KEY_WORD_SEARCH_EXPIRE);
                return $matches;
            }
        }

        return false;
    }

    /**
     * Conver chinese word to pinyin if exist cache and get it in cache , or not and cache.
     *
     * @return string chinese pinyin
     */
    public static function getPinyinAndCache($chinese)
    {
        $chinese = trim($chinese);
        $cKey = \App\WRef::CACHE_KEY_PY_CHINESE.md5($chinese);
        if (\Cache::has($cKey)) {
            $pinyin = \Cache::get($cKey);
        } else {
            $pinyin = pinyin($chinese);
            \Cache::forever($cKey, $pinyin);
        }

        return $pinyin;
    }
}
