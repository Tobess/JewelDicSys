<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Material extends Model {

    public $timestamps = false;

    /**
     * 转换key的名称
     */
    public static function _convert($data, $isSingleArray = false, $keyString = 'material_')
    {
        if ($isSingleArray) {
            $item = [];
            foreach ($data as $mKey => $mVal) {
                if (!strstr($mKey, $keyString)) $item[$keyString.$mKey] = $mVal;
            }

            return $item;
        } else {
            $mData = [];
            foreach ($data as $material) {
                $mArr = $material->toArray();
                $item = [];
                foreach ($mArr as $mKey => $mVal) {
                    $item[$keyString.$mKey] = $mVal;
                }
                $mData[] = $item;
            }

            return $mData;
        }
    }

    /**
     * 获得所有的宝石分类
     */
    public static function allStones($ids)
    {
        $que = self::whereRaw('type<>1');
        if ($ids) {
            $que->whereRaw('id in ('.$ids.')');
        }
        return self::_convert($que->get());
    }

    /**
     * 获得所有的贵金属分类
     */
    public static function allMetals($ids)
    {
        $que = self::whereRaw('type=1');
        if ($ids) {
            $que->whereRaw('id in ('.$ids.')');
        }
        return self::_convert($que->get());
    }

    /**
     * 获得所有的材质分类
     */
    public static function allMaterials($ids)
    {
        return self::_convert($ids ? self::whereRaw('id in ('.$ids.')')->get() : self::all());
    }

        /**
     * 获得指定材质的信息
     */
    public static function getMaterialByID($id)
    {
        $material = self::find($id);
        if ($material) {
            $mItem = $material->toArray();
            // 如果为贵金属，则获取贵金属详细信息
            if ($material->type == 1) {
                $metal = \App\MMetal::find($id);
                if ($metal) {
                    $mItem = array_merge($mItem, $metal->toArray());
                }
            }

            return self::_convert($mItem, true);
        }

        return false;
    }

}
