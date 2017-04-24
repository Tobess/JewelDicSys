<?php namespace App\Http\Controllers\console;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\SColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SColorController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {

        /** 获取查询基本请求条件*/
        $que = \App\SColor::select('id', 'name', 'pinyin', 'material_id', 'letter');
        $query = \Input::get('query', '');
        $materialName = \Input::get('material', '');
        if ($query) {
            $que->where(function ($que) use ($query) {
                $que->where('pinyin', 'like', $query . '%')
                    ->orWhere('letter', 'like', $query . '%')
                    ->orWhere('material_id', '=', $query)
                    ->orWhere('name', 'like', $query . '%');
            });
        }

        /** 分页*/
        $rows = $que->orderBy('id','desc')->paginate(10);

        /** 优化结果集*/
        foreach ($rows as $val) {
            $materials_name = \DB::table('materials')
                ->select('name')
                ->where('id', $val->material_id)
                ->first();
            $name = !empty($materials_name->name) ? $materials_name->name : "";
            $val->material_name = $name;
        }
        $type = \Input::get('type');
        /** send view data*/
        if (!empty($materialName) || $type ) {
            return view('console.scolor.list', [
                'rows' => $rows,
                'materialName' => $materialName,
                'materialID'   => $query,
                'query' => '',
            ]);
        }
        return view('console.scolor.list', [
            'rows' => $rows,
            'materialName' => '',
            'materialID'   => '',
            'query' => $query
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
        if ($name) {
            $sColor = new \App\SColor();
            $sColor->name = $name;
            $sColor->pinyin = \Input::get('pinyin') ?: pinyin($name);
            $sColor->material_id = \Input::get('material');
            $sColor->letter = \Input::get('letter') ?: letter($name);
            $sColor->save();
        }

        return redirect('console/scolor');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function postUpdate($id)
    {
        $name = \Input::get('name');
        $sColor = \App\SColor::find($id);
        if ($name && $sColor) {
            $sColor->name = $name;
            $sColor->pinyin = \Input::get('pinyin') ?: pinyin($name);
            $sColor->material_id = \Input::get('material');
            $sColor->letter = \Input::get('letter') ?: letter($name);
            $sColor->save();
        }

        return redirect('console/scolor');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function getDestroy($id)
    {
        //
        $sColor = \App\SColor::find($id);
        if ($sColor) {
            $sColor->delete();
        }

        return redirect('console/scolor');
    }

}
