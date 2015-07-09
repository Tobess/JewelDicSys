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
            $data = is_object($data) ? $data->toArray() : $data;
            foreach ($data as $mKey => $mVal) {
                if (!strstr($mKey, $keyString)) {
                    $item[$keyString.$mKey] = $mVal;
                } else {
                    $item[$mKey] = $mVal;
                }
            }

            return $item;
        } else {
            $mData = [];
            foreach ($data as $material) {
                $mArr = $material->toArray();
                $item = [];
                foreach ($mArr as $mKey => $mVal) {
                    if (!strstr($mKey, $keyString)) {
                        $item[$keyString.$mKey] = $mVal;
                    } else {
                        $item[$mKey] = $mVal;
                    }
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
            $extend = \App\MMetal::whereRaw('material_id in ('.$ids.')')->get();
        } else {
            $extend = \App\MMetal::all();
        }
        $main = self::_convert($que->get());

        $extendTree = [];
        $extend = self::_convert($extend);
        foreach ($extend as $eItem) {
            $eItem['material_type_sub'] = $eItem['material_metal'];
            $extendTree[$eItem['material_id']] = $eItem;
        }

        foreach ($main as &$mItem) {
            if (isset($extendTree[$mItem['material_id']])) {
                $mItem = array_merge($extendTree[$mItem['material_id']], $mItem);
            }
        }

        return $main;
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
                    $metal->type_sub = $metal->metal;
                    $mItem = array_merge($mItem, self::_convert($metal, true));
                }
            }

            return self::_convert($mItem, true);
        }

        return false;
    }

    /**
     * 获得材质分类的所有父亲节点
     * 因为数据只有三级结构，所以获取到的数据只会是一二级数据
     */
    public static function getMaterialParentNodes()
    {
        $materials = self::_convert(self::all());
        $mTree = [];
        $pNodes = [];
        foreach ($materials as $mItem) {
            $mTree[$mItem['material_id']] = $mItem;
            $mItem['material_parent'] > 0 &&
            !in_array($mItem['material_parent'], $pNodes) &&
            ($pNodes[] = $mItem['material_parent']);
        }
        $pItems = [];
        foreach ($pNodes as $pId) {
            $pItems[] = $mTree[$pId];
        }

        return $pItems;
    }

}
