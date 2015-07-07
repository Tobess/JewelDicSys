<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ResourcesController extends Controller {

    public static function response($data)
    {
        return \Response::json(is_array($data) ? $data : null);
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
     * Get the all materials.
     *
     * @return Response
     */
    public function getMaterials()
    {
        $ids = \Input::get('ids');
        return self::response(\App\Material::allMaterials($ids));
    }

    /**
     * Get the stone materials.
     *
     * @return Response
     */
    public function getStones()
    {
        $ids = \Input::get('ids');
        return self::response(\App\Material::allStones($ids));
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
        return self::response(\App\Material::allMetals($ids));
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
     * Get the varieties.
     *
     * @return Response
     */
    public function getVarieties()
    {
        $ids = \Input::get('ids');
        return self::response(\App\Variety::allVarieties($ids));
    }

    /**
     * Get brand by id.
     *
     * @return Response
     */
    public function getBrand($id)
    {
        $brand = \App\Brand::getBrandByID($id);

        return self::response($variety);
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

}
