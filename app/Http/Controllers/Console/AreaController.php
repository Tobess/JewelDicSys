<?php namespace App\Http\Controllers\Console;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        $query = \Input::get('query', '');
        $province = \Input::get('province', 0);
        $city = \Input::get('city', 0);
        $district = \Input::get('district', 0);

        $parent = $district ?: ($city ?: ($province ?: 0));

        if ($query) {
            $rows = \DB::table('area as a')->where('parent_id', $parent)
                ->where(function($que) use ($query) {
                    $que->where('name', 'like', $query.'%')
                        ->orWhere('short_name', 'like', $query.'%');
                })
                ->select('a.*', \DB::raw('(select count(id) from area where parent_id=a.id) as child'))
                ->orderBy('sort')->paginate(20);
        } else {
            $rows = \DB::table('area as a')->where('parent_id', $parent)
                ->select('a.*', \DB::raw('(select count(id) from area where parent_id=a.id) as child'))
                ->orderBy('sort')->paginate(10);
        }

        $provinces = \App\Area::where('parent_id', 0)->select('id', 'name')->get();
        $cities = $province > 0 ? \App\Area::where('parent_id', $province)->select('id', 'name')->get() : [];
        $districts = $city > 0 ? \App\Area::where('parent_id', $city)->select('id', 'name')->get() : [];

        return view('console.area.list', [
            'rows'=>$rows,
            'query'=>$query,
            'parent'=>$parent,
            'province'=>$province,
            'city'=>$city,
            'district'=>$district,
            'provinces'=>$provinces,
            'cities'=>$cities,
            'districts'=>$districts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postStore()
    {
        $name = \Input::get('name');
        $short_name = \Input::get('short_name');
        $parent = \Input::get('parent', 0);
        $longitude = \Input::get('longitude', 0);
        $latitude = \Input::get('latitude', 0);
        if ($name) {
            $area = new \App\Area();
            $area->name = $name;
            $area->short_name = $short_name ?: $name;
            $area->parent_id = $parent;
            $area->longitude = $longitude ?: 0;
            $area->latitude = $latitude ?: 0;
            $area->level = \DB::table('area')->where('id', $parent)->pluck('level') + 1;
            $area->sort = \DB::table('area')->where('parent_id', $parent)->max('sort') + 1;
            $area->save();
        }

        return redirect()->back();
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
        $short_name = \Input::get('short_name');
        $longitude = \Input::get('longitude', 0);
        $latitude = \Input::get('latitude', 0);

        $area = \App\Area::find($id);
        if ($name && $area) {
            $area->name = $name;
            $area->short_name = $short_name ?: $name;
            $area->longitude = $longitude ?: 0;
            $area->latitude = $latitude ?: 0;
            $area->save();
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function getDestroy($id)
    {
        $area = \App\Area::find($id);
        if ($area && \App\Area::where('parent_id', $id)->count() == 0) {
            $area->delete();
        }

        return redirect()->back();
    }

}
