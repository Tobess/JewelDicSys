<?php namespace App\Http\Controllers;

use App\Brand;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;

class ResourcesController extends Controller
{

    public static function response($data)
    {
        return \Response::json($data);
    }

    /**
     * Get the material by id.
     *
     * @param int $id
     *
     * @return Response
     */
    public function getMaterial($id)
    {
        $stone = \App\Material::getMaterialByID($id);

        return self::response($stone);
    }

    /**
     * Check the material is parent node.
     *
     * @param int $id
     *
     * @return Response
     */
    public function getMaterialIsParent($id)
    {
        $isParentNode = \App\Material::isParentNode($id);

        return self::response(['isParentNode' => $isParentNode]);
    }

    /**
     * Get the material by alias.
     *
     * @param string $alias
     *
     * @return Response
     */
    public function getMaterialAlias($alias)
    {
        return self::response(\App\Material::getMaterialByAlias($alias));
    }

    /**
     * Get the metal by alias.
     *
     * @param string $alias
     *
     * @return Response
     */
    public function getMetalAlias($alias)
    {
        return self::response(\App\Material::getMaterialByAlias($alias, true));
    }

    /**
     * Get the all materials.
     *
     * @return Response
     */
    public function getMaterials()
    {
        $ids = \Input::get('ids');
        $noParentNode = \Input::get('noParentNode', 'N') == 'Y';
        return self::response(\App\Material::allMaterials($ids, $noParentNode));
    }

    /**
     * Get the all parent nodes of material.
     *
     * @return Response
     */
    public function getMaterialsParent()
    {
        return self::response(\App\Material::getMaterialParentNodes());
    }

    /**
     * Get the stone material by id.
     *
     * @param int $id
     *
     * @return Response
     */
    public function getStone($id)
    {
        $stone = \App\Material::getMaterialByID($id);

        return self::response($stone);
    }

    /**
     * Get the stone materials.
     *
     * @return Response
     */
    public function getStones()
    {
        $ids = \Input::get('ids');
        $hasAlias = \Input::get('hasAlias', 'N') == 'Y';
        return self::response(\App\Material::allStones($ids, $hasAlias));
    }

    /**
     * Get the metal material by id.
     *
     * @param int $id
     *
     * @return Response
     */
    public function getMetal($id)
    {
        $metal = \App\Material::getMaterialByID($id);

        return self::response($metal);
    }

    /**
     * Get the metal materials.
     *
     * @return Response
     */
    public function getMetals()
    {
        $ids = \Input::get('ids');
        $hasAlias = \Input::get('hasAlias', 'N') == 'Y';
        return self::response(\App\Material::allMetals($ids, $hasAlias));
    }

    /**
     * Get the variety by id.
     *
     * @param int $id
     *
     * @return Response
     */
    public function getVariety($id)
    {
        $variety = \App\Variety::getVarietyByID($id);

        return self::response($variety);
    }

    /**
     * Check the variety is parent node.
     *
     * @param int $id
     *
     * @return Response
     */
    public function getVarietyIsParent($id)
    {
        $isParentNode = \App\Variety::isParentNode($id);

        return self::response(['isParentNode' => $isParentNode]);
    }

    /**
     * Get the variety by alias.
     *
     * @param string $alias
     *
     * @return Response
     */
    public function getVarietyAlias($alias)
    {
        return self::response(\App\Variety::getVarietyByAlias($alias));
    }

    /**
     * Get the varieties.
     *
     * @return Response
     */
    public function getVarieties()
    {
        $ids = \Input::get('ids');
        $hasAlias = \Input::get('hasAlias', 'N') == 'Y';
        $noParentNode = \Input::get('noParentNode', 'N') == 'Y';
        return self::response(\App\Variety::allVarieties($ids, $hasAlias, $noParentNode));
    }

    /**
     * Get brand by id.
     *
     * @return Response
     */
    public function getBrand($id)
    {
        $brand = \App\Brand::getBrandByID($id);

        return self::response($brand);
    }

    /**
     * Get the brands.
     *
     * @return Response
     */
    public function getBrands()
    {
        $ids = \Input::get('ids');
        return self::response(\App\Brand::allBrands($ids));
    }

    /**
     * Get color by id.
     *
     * @return Response
     */
    public function getColor($id)
    {
        $item = \App\Color::find($id);

        return self::response($item);
    }

    /**
     * Get the colors.
     *
     * @return Response
     */
    public function getColors()
    {
        $ids = \Input::get('ids');
        return self::response(\App\Color::allColors($ids));
    }

    /**
     * Get craft by id.
     *
     * @return Response
     */
    public function getCraft($id)
    {
        $item = \App\Craft::find($id);

        return self::response($item);
    }

    /**
     * Get the crafts.
     *
     * @return Response
     */
    public function getCrafts()
    {
        $ids = \Input::get('ids');
        return self::response(\App\Craft::allCrafts($ids));
    }

    /**
     * Get grade by id.
     *
     * @return Response
     */
    public function getGrade($id)
    {
        $item = \App\Grade::find($id);

        return self::response($item);
    }

    /**
     * Get the crafts.
     *
     * @return Response
     */
    public function getGrades()
    {
        $ids = \Input::get('ids');
        return self::response(\App\Grade::allGrades($ids));
    }

    /**
     * Get moral by id.
     *
     * @return Response
     */
    public function getMoral($id)
    {
        $item = \App\Moral::find($id);

        return self::response($item);
    }

    /**
     * Get the morals.
     *
     * @return Response
     */
    public function getMorals()
    {
        $ids = \Input::get('ids');
        return self::response(\App\Moral::allMorals($ids));
    }

    /**
     * Get style by id.
     *
     * @return Response
     */
    public function getStyle($id)
    {
        $item = \App\Style::find($id);

        return self::response($item);
    }

    /**
     * Get the styles.
     *
     * @return Response
     */
    public function getStyles()
    {
        $ids = \Input::get('ids');
        return self::response(\App\Style::allStyles($ids));
    }

    /**
     * Get rule by id.
     *
     * @return Response
     */
    public function getRule($id)
    {
        $item = \App\Rule::find($id);

        return self::response($item);
    }

    /**
     * Get the rules.
     *
     * @return Response
     */
    public function getRules()
    {
        return self::response(\App\Rule::all());
    }

    /**
     * Post the feedback
     *
     * @return Response
     */
    public function getFeedback()
    {
        $file = \Input::get('file_id');
        $domain = \Input::get('domain');
        $companyName = \Input::get('companyName');
        $mobile = \Input::get('mobile');
        $userName = \Input::get('userName');
        $contents = \Input::get('contents');
        $fileGroup = \Input::get('file_group');
        $fileName = \Input::get('file_name');

        $redis = \Redis::connection('default');
        if ($redis->exists($contents)) {
            $contents = $redis->get($contents);
        } else {
            return self::response(['state' => false, 'message' => '无效的参数.']);
        }

        return self::response(\App\JError::feedback($file, $domain, $companyName, $mobile, $userName, $contents, $fileGroup, $fileName));
    }

    /**
     * 获得县级地的所有乡镇
     *
     * @return Response
     */
    public function getCountries()
    {
        $city = \Input::get('city');
        $district = \Input::get('district');

        $countries = [];
        if ($city && $district) {
            $ppid = \App\Area::where('level', 2)->where(function ($sql) use ($city) {
                $sql->where('name', 'like', '%' . $city . '%')
                    ->orWhere('short_name', 'like', '%' . $city . '%');
            })->lists('id');
            if (count($ppid) > 0) {
                $did = \App\Area::where('level', '3')
                    ->where(function ($sql) use ($district) {
                        $sql->where('name', 'like', '%' . $district . '%')
                            ->orWhere('short_name', 'like', '%' . $district . '%');
                    })
                    ->whereIn('parent_id', $ppid)
                    ->pluck('id');
                \Log::info(print_r($did, true));
                if ($did > 0) {
                    $countries = \App\Area::where('level', 4)->where('parent_id', $did)
                        ->select('id', 'name')->get();
                }
            }
        }

        return self::response($countries);
    }

    /**
     * 获得标准体系分类
     *
     * @return Response
     */
    public function getStandard()
    {
        $mid = \Input::get('mid');
        $data = [];
        $types = ['s_shape' => 's_shapes', 's_color' => 's_colors', 's_clarity' => 's_clarities', 's_cut' => 's_cuts', 's_grade' => 's_grades', 's_certificate' => 's_certificates'];
        foreach ($types as $type => $table) {
            $que = \DB::table($table);
            if ($mid > 0) {
                $que->where('material_id', $mid);
            }
            $data[$type] = $que->select(['id', 'name as title', 'material_id as mid', 'pinyin', 'letter'])->get();
        }

        return self::response($data);

    }

    /**
     * 按名称获得品牌id或创建品牌
     *
     * @return Response
     */
    public function postBrands()
    {
        $brands = Input::get('brands');
        $bidArr = [];
        if ($brands && !empty($brands)) {
            $brands = is_array($brands) ? $brands : explode(',', $brands);
            foreach ($brands as $bName) {
                $brand = Brand::where('name', $bName)->first();
                if (!$brand) {
                    $brand = new \App\Brand;
                    $brand->name = $bName;
                    $brand->pinyin = pinyin($bName);
                    $brand->letter = letter($bName);
                    $brand->save();
                }

                $bidArr[] = $brand->id;
            }
        }

        return self::response($bidArr);
    }
}
