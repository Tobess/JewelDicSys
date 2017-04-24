<?php
/**
 * Created by PhpStorm.
 * User: formai
 * Date: 2017/3/22
 * Time: 11:30
 */

namespace App\Http\Controllers\Console;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class SShapeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {

        /** 获取查询基本请求条件*/
        $que = \App\SShape::select('id', 'name', 'pinyin', 'material_id', 'letter');
        $query = \Input::get('query', '');
        $materialName = \Input::get('material', '');
        if ($query) {
            $que->where(function ($que) use ($query) {
                $que->where('material_id', '=', $query)
                    ->orWhere('pinyin', 'like', $query . '%')
                    ->orWhere('letter', 'like', $query . '%')
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
            $name = !empty($materials_name->name) ? $materials_name->name:"";
            $val->material_name =$name;
        }

        $type = \Input::get('type');
        /** send view data*/
        if (!empty($materialName) || $type ) {
            return view('console.sshape.list', [
                'rows' => $rows,
                'materialName' => $materialName,
                'materialID'   => $query,
                'query' => '',
            ]);
        }
        return view('console.sshape.list', [
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
            $sShape = new \App\SShape();
            $sShape->name = $name;
            $sShape->pinyin = \Input::get('pinyin')?:pinyin($name);
            $sShape->material_id = \Input::get('material');
            $sShape->letter = \Input::get('letter')?:letter($name);
            $sShape->save();
        }

        return redirect('console/sshape');
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
        $sShape = \App\SShape::find($id);
        if ($name && $sShape) {
            $sShape->name = $name;
            $sShape->pinyin = \Input::get('pinyin')?:pinyin($name);
            $sShape->material_id = \Input::get('material');
            $sShape->letter = \Input::get('letter')?:letter($name);
            $sShape->save();
        }

        return redirect('console/sshape');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function getDestroy($id)
    {
        //
        $sShape = \App\SShape::find($id);
        if ($sShape) {
            $sShape->delete();
        }

        return redirect('console/sshape');
    }
}