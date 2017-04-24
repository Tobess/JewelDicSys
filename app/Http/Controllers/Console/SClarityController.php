<?php namespace App\Http\Controllers\console;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class SClarityController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
        /** 获取查询基本请求条件*/
        $que = \App\SClarity::select('id','name','pinyin', 'material_id','letter');
        $query = \Input::get('query', '');
        $materialName = \Input::get('material', '');
        if ($query) {
            $que->where(function($que) use ($query) {
                $que->where('material_id', '=', $query)
                    ->orWhere('pinyin', 'like', $query.'%')
                    ->orWhere('letter', 'like', $query.'%')
                    ->orWhere('name', 'like', $query.'%');
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
            return view('console.sclarity.list', [
                'rows' => $rows,
                'materialName' => $materialName,
                'materialID'   => $query,
                'query' => '',
            ]);
        }
        return view('console.sclarity.list', [
            'rows' => $rows,
            'materialName' => '',
            'materialID'   => '',
            'query' => $query
        ]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
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
            $sClarity = new \App\SClarity();
            $sClarity->name = $name;
            $sClarity->pinyin = \Input::get('pinyin')?:pinyin($name);
            $sClarity->material_id = \Input::get('material');
            $sClarity->letter = \Input::get('letter')?:letter($name);
            $sClarity->save();
        }

        return redirect('console/sclarity');
    }

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
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
        $sClarity = \App\SClarity::find($id);
        if ($name && $sClarity) {
            $sClarity->name = $name;
            $sClarity->pinyin = \Input::get('pinyin')?:pinyin($name);
            $sClarity->material_id = \Input::get('material');
            $sClarity->letter = \Input::get('letter')?:letter($name);
            $sClarity->save();
        }

        return redirect('console/sclarity');
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
        $sClarity = \App\SClarity::find($id);
        if ($sClarity) {
            $sClarity->delete();
        }

        return redirect('console/sclarity');
    }

}
