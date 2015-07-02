<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class DLink extends Model {

    public $timestamps = false;

    /**
     * 如果数据链接关系存在则移除其关系
     */
    public static function linkExistsAndRemove($tType, $tId)
    {
        return self::where('rel_type_tar', $tType)->where('rel_id_tar', $tId)->delete();
    }

    /**
     * 获得指定数据与其他数据间的链接关系
     */
    public static function getLinksBySrcTypeID($sType, $sId)
    {
        $links = [];
        if (\Cache::has($cKey = \App\WRef::CACHE_KEY_DATA_LINK.$sType.':'.$sId)) {
            $links = unserialize(\Cache::get($cKey));
        }

        return $links;
    }

    /**
     * 生成指定数据与其他数据间的链接关系缓存
     */
    public static function setLinksBySrcTypeID($sType, $sId)
    {
        $links = self::where('rel_type_src', $sType)->where('rel_id_src', $sId)->get();
        $dLinks = [];
        if (count($links)) {
            foreach ($links as $link) {
                $tList = \App\WRef::getRelationNameByType($link->rel_type_tar, "`id`='{$link->rel_id_tar}'");
                if ($tList !== false && count($tList) == 1) {
                    if ($tItem = array_shift($tList)) {
                        $tItem->rel_type = $link->rel_type_tar;
                        $dLinks[] = $tItem;
                    }
                }
            }

            if (count($dLinks)) {
                $cKey = \App\WRef::CACHE_KEY_DATA_LINK.$sType.':'.$sId;
                \Cache::forever($cKey, serialize($dLinks));
            }
        }

        return $dLinks;
    }

    /**
     * 生成所有数据关联的缓存
     */
    public static function generateCache()
    {
        $links = \DB::table('d_links')
            ->select('rel_type_src', 'rel_id_src')
            ->groupBy('rel_type_src', 'rel_id_src')
            ->get();
        foreach($links as $link) {
            self::setLinksBySrcTypeID($link->rel_type_src, $link->rel_id_src);
        }
    }

}
