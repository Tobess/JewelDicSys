<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class WRelation extends Model {

    protected $table = 'words_relations';

    public $timestamps = false;

    /**
     * 生成拼音链接关系
     *
     * @param int 拼音ID
     * @param int $relType 名称元素类型
     * @param int $relId   名称元素类型ID
     *
     * @return mixed
     */
    public static function link($wId, $rType, $rId)
    {
        $rel = self::where('word_id', $wId)->where('rel_type', $rType)->where('rel_id', $rId)->first();
        if (!$rel || !$rel->word_id) {
            $rel = new self;
            $rel->word_id = $wId;
            $rel->rel_type = $rType;
            $rel->rel_id = $rId;
            $rel->save();
        }

        return $rel;
    }

    /**
     * 移除拼音链接关系
     *
     * @param int $relType 名称元素类型
     *
     * @return mixed
     */
    public static function unlinkByType($rType)
    {
        return self::where('rel_type', $rType)->delete();
    }

    /**
     * 获得词根的关联关系并缓存
     */
    public static function getLinksAndCacheByWordID($wId)
    {
        if (\Cache::has(\App\WRef::CACHE_KEY_WORD_LINK.$wId)) {
            $links = unserialize(\Cache::get(\App\WRef::CACHE_KEY_WORD_LINK.$wId));
        } else {
            $links = self::where('word_id', $wId)->get();
            if (count($links)) {
                \Cache::put(\App\WRef::CACHE_KEY_WORD_LINK.$wId, serialize($links), \App\WRef::CACHE_KEY_WORD_SEARCH_EXPIRE);
            } else {
                $links = [];
            }
        }

        return $links;
    }

    /**
     * 判断指定的词根ID和指定的元数据存在绑定关系
     */
    public static function wordIsRefToTypeID($wId, $relType, $relId)
    {
        foreach (self::getLinksAndCacheByWordID($wId) as $link) {
            if ($link->rel_id == $relId && $link->rel_type == $relType) {
                return true;
            }
        }

        return false;
    }

}
