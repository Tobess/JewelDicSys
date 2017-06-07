<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 行业品牌
 * @package App
 */
class Brand extends Model {

    public $timestamps = false;

    /**
     * Get all brands.
     */
    public static function allBrands($ids)
    {
        return \App\Material::_convert($ids ? self::whereIn('id', is_string($ids) ? explode(',', $ids) : $ids)->orderBy('name')->get() : self::orderBy('name')->get(), false, 'brand_');
    }

    /**
     * 根据品牌ID获取样式信息
     */
    public static function getBrandByID($id)
    {
        $brand = self::find($id);
        if ($brand) {
            $bItem = $brand->toArray();

            return \App\Material::_convert($bItem, true, 'brand_');
        }

        return false;
    }

}
