<?php namespace App\Http\Controllers\Console;

use App\Http\Requests;
use App\Http\Controllers\ConsoleController;

use Illuminate\Http\Request;

class BrandController extends ConsoleController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
        $rows = \App\Brand::paginate(20);

        return view('console.brand.list', ['rows'=>$rows]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postStore()
	{
        $name = \Input::get('name');
        if ($name) {
            $brand = new \App\Brand;
            $brand->name = $name;
            $brand->pinyin = \Input::get('pinyin')?:pinyin($name);
            $brand->letter = \Input::get('letter')?:letter($name);
            $brand->save();
        }

        return redirect('console/brands');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getProfile($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdate($id)
	{
        $name = \Input::get('name');
        $brand = \App\Brand::find($id);
        if ($name && $brand) {
            $brand->name = $name;
            $brand->pinyin = \Input::get('pinyin')?:pinyin($name);
            $brand->letter = \Input::get('letter')?:letter($name);
            $brand->save();
        }

        return redirect('console/brands');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getDestroy($id)
	{
        $brand = \App\Brand::find($id);
        if ($brand) {
            $brand->delete();
        }

        return redirect('console/brands');
	}

}
