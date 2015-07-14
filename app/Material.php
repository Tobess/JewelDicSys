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
    public static function allStones($ids, $containAlias = false)
    {
        $que = self::whereRaw('type<>1');
        if ($ids) {
            $que->whereRaw('id in ('.$ids.')');
        }
        $sList = $que->get();

        if ($containAlias) {
            $aQue = \DB::table('aliases')
                ->select(\DB::raw('group_concat(`name`) as `name`'),
                    \DB::raw('group_concat(`pinyin`) as `pinyin`'),
                    \DB::raw('group_concat(`letter`) as `letter`'),
                    'rel_id')
                ->where('rel_type', 1);
            if ($ids) {
                $aQue->whereRaw('rel_id in ('.$ids.')');
            }
            $aList = $aQue->groupBy('rel_id')->get();
            $aTree = [];
            foreach ($aList as $aItem) {
                $aTree[$aItem->rel_id] = $aItem;
            }
            foreach ($sList as &$sItem) {
                if (isset($aTree[$sItem->id])) {
                    $sItem->alias_name = $aTree[$sItem->id]->name;
                    $sItem->alias_pinyin = $aTree[$sItem->id]->pinyin;
                    $sItem->alias_letter = $aTree[$sItem->id]->letter;
                }
            }
        }

        return self::_convert($sList);
    }

    /**
     * 获得所有的贵金属分类
     */
    public static function allMetals($ids, $containAlias = false)
    {
        $que = self::whereRaw('type=1');
        if ($ids) {
            $que->whereRaw('id in ('.$ids.')');
            $extend = \App\MMetal::whereRaw('material_id in ('.$ids.')')->get();
        } else {
            $extend = \App\MMetal::all();
        }
        $mList = $que->get();
        if ($containAlias) {
            $aQue = \DB::table('aliases')
                ->select(\DB::raw('group_concat(`name`) as `name`'),
                    \DB::raw('group_concat(`pinyin`) as `pinyin`'),
                    \DB::raw('group_concat(`letter`) as `letter`'),
                    'rel_id')
                ->where('rel_type', 2);
            if ($ids) {
                $aQue->whereRaw('rel_id in ('.$ids.')');
            }
            $aList = $aQue->groupBy('rel_id')->get();
            $aTree = [];
            foreach ($aList as $aItem) {
                $aTree[$aItem->rel_id] = $aItem;
            }
            foreach ($mList as &$mItem) {
                if (isset($aTree[$mItem->id])) {
                    $mItem->alias_name = $aTree[$mItem->id]->name;
                    $mItem->alias_pinyin = $aTree[$mItem->id]->pinyin;
                    $mItem->alias_letter = $aTree[$mItem->id]->letter;
                }
            }
        }

        $main = self::_convert($mList);

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
    public static function getMaterialByID($id, $notParentNode = true)
    {
        $material = self::find($id);
        if ($material) {
            if (!($notParentNode && self::isParentNode($id))) {
                return self::parseMaterial($material);
            }
        }

        return false;
    }

    private static function parseMaterial($material)
    {
        $mItem = $material->toArray();
        // 如果为贵金属，则获取贵金属详细信息
        if ($material->type == 1) {
            $metal = \App\MMetal::find($material->id);
            if ($metal) {
                $metal->type_sub = $metal->metal;
                $mItem = array_merge($mItem, self::_convert($metal, true));
            }
        }

        return self::_convert($mItem, true);
    }

    /**
     * 获得材质通过名称
     */
    public static function getMaterialByAlias($alias, $isMetal = false, $notParentNode = true)
    {
        if ($isMetal) {
            $material = self::where('name', $alias)->where('type', 1)->first();
        } else {
            $material = self::where('name', $alias)->first();
        }

        if ($material && !($notParentNode && self::isParentNode($material->id))) {
            return self::parseMaterial($material);
        } else {
            // 通过别名搜索
            $aliasesQue = \App\WAlias::where('name', $alias);
            if ($isMetal) {
                $aliasesQue->where('rel_type', 2);
            } else {
                $aliasesQue->whereRaw('rel_type in (1, 2)');
            }
            $aliases = $aliasesQue->get();

            if (count($aliases)) {
                $relIdArr = [];
                foreach ($aliases as $aItem) {
                    !in_array($aItem->rel_id, $relIdArr) && ($relIdArr[] = $aItem->rel_id);
                }
                if (count($relIdArr) == 1) {
                    $mId = array_shift($relIdArr);
                    return self::getMaterialByID($mId, $notParentNode);
                }
            }
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

    /**
     * 检测指定节点是否是父级节点
     */
    public static function isParentNode($id)
    {
        return self::where('parent', $id)->count() > 0;
    }

}
