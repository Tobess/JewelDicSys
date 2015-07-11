<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Variety extends Model {

    public $timestamps = false;

    /**
     * 获得所有的样式分类
     */
    public static function allVarieties($ids)
    {
        return \App\Material::_convert($ids ? self::whereRaw('id in ('.$ids.')')->get() : self::all(), false, 'variety_');
    }

    /**
     * 根据样式ID获取样式信息
     */
    public static function getVarietyByID($id)
    {
        $variety = self::find($id);
        if ($variety) {
            $vItem = $variety->toArray();

            return \App\Material::_convert($vItem, true, 'variety_');
        }

        return false;
    }

    /**
     * 获得材质通过名称
     */
    public static function getVarietyByAlias($alias)
    {
        $variety = self::where('name', $alias)->first();
        if ($variety) {
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
                    return self::getVarietyByID(array_shift($relIdArr));
                }
            }
        }

        return false;
    }

}
