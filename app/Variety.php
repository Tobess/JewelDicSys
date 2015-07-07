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

}
