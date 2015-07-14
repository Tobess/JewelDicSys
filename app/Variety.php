<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Variety extends Model {

    public $timestamps = false;

    /**
     * 获得所有的样式分类
     */
    public static function allVarieties($ids, $containAlias = false)
    {
        $vList = $ids ? self::whereRaw('id in ('.$ids.')')->get() : self::all();
        if ($containAlias) {
            $aQue = \DB::table('aliases')
                ->select(\DB::raw('group_concat(`name`) as `name`'),
                    \DB::raw('group_concat(`pinyin`) as `pinyin`'),
                    \DB::raw('group_concat(`letter`) as `letter`'),
                    'rel_id')
                ->where('rel_type', 3);
            if ($ids) {
                $aQue->whereRaw('rel_id in ('.$ids.')');
            }
            $aList = $aQue->groupBy('rel_id')->get();
            $aTree = [];
            foreach ($aList as $aItem) {
                $aTree[$aItem->rel_id] = $aItem;
            }
            foreach ($vList as &$vItem) {
                if (isset($aTree[$vItem->id])) {
                    $vItem->alias_name = $aTree[$vItem->id]->name;
                    $vItem->alias_pinyin = $aTree[$vItem->id]->pinyin;
                    $vItem->alias_letter = $aTree[$vItem->id]->letter;
                }
            }
        }

        return \App\Material::_convert($vList, false, 'variety_');
    }

    /**
     * 根据样式ID获取样式信息
     */
    public static function getVarietyByID($id, $notParentNode = true)
    {
        $variety = self::find($id);
        if ($variety) {
            $vItem = $variety->toArray();

            if (!($notParentNode && self::isParentNode($id))) {
                return \App\Material::_convert($vItem, true, 'variety_');
            }
        }

        return false;
    }

    /**
     * 获得材质通过名称
     */
    public static function getVarietyByAlias($alias, $notParentNode = true)
    {
        $variety = self::where('name', $alias)->first();
        if ($variety && !($notParentNode && self::isParentNode($variety->id))) {
            $vItem = $variety->toArray();

            return \App\Material::_convert($vItem, true, 'variety_');
        } else {
            // 通过别名搜索
            $aliases = \App\WAlias::where('name', $alias)->where('rel_type', 3)->get();
            if (count($aliases)) {
                $relIdArr = [];
                foreach ($aliases as $aItem) {
                    !in_array($aItem->rel_id, $relIdArr) && ($relIdArr[] = $aItem->rel_id);
                }
                if (count($relIdArr) == 1) {
                    $vId = array_shift($relIdArr);
                    return self::getVarietyByID($vId, $notParentNode);
                }
            }
        }

        return false;
    }

    /**
     * 检测指定节点是否是父级节点
     */
    public static function isParentNode($id)
    {
        return self::where('parent', $id)->count() > 0;
    }

}
