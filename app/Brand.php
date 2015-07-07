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
        return \App\Material::_convert($ids ? self::whereRaw('id in ('.$ids.')')->get() : self::all(), false, 'brand_');
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
