<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 名称规则元素类型
 * @package App
 */
class WRef {

    const CACHE_KEY_WORD_DIC = 'words:dic:';
    const CACHE_KEY_WORD_SEARCH = 'words:search:';
    const CACHE_KEY_WORD_SPLIT = 'words:split:';

    const CACHE_KEY_PINYIN_IDX = 'pinyin:index';

    /**
     * 名称生成规则元素类型
     * @var array
     */
    protected static $wTypes = [
        ['id'=>1, 'name'=>'宝石分类', 'table'=>'materials', 'where'=>'type<>1'],
        ['id'=>2, 'name'=>'金属分类', 'table'=>'materials', 'where'=>'type==1'],
        ['id'=>3, 'name'=>'样式分类', 'table'=>'varieties'],
        ['id'=>4, 'name'=>'珠宝品牌', 'table'=>'brands'],
        ['id'=>5, 'name'=>'加工工艺', 'table'=>'crafts'],
        ['id'=>6, 'name'=>'宝石颜色', 'table'=>'colors'],
        ['id'=>7, 'name'=>'宝石等级', 'table'=>'grades'],
        ['id'=>8, 'name'=>'珠宝款式', 'table'=>'styles'],
        ['id'=>9, 'name'=>'珠宝寓意', 'table'=>'morals']
    ];

    /**
     * 获得名称生成规则元素类型
     *
     * @param int $id 元素类型ID
     * @return array|bool <p>return false for fail</p>
     */
    public static function getRefById($id)
    {
        return self::$wTypes[$id - 1]?:false;
    }

}
