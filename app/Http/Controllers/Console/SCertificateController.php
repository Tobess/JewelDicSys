<?php namespace App\Http\Controllers\console;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class SCertificateController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{

	    /** 获取查询基本请求条件*/
        $que = \App\SCertificate::select('id', 'name', 'pinyin', 'material_id', 'letter');
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
        $rows = $que->orderBy('id')->paginate(10);

        /** 优化结果集*/
        foreach ($rows as $val) {
            $materials_name = \DB::table('materials')
                ->select('name')
                ->where('id', $val->material_id)
                ->first();
            $name = !empty($materials_name->name) ? $materials_name->name:"";
            $val->material_name = $name;
        }

        $type = \Input::get('type');
        /** send view data*/
        if (!empty($materialName) || $type ) {
            return view('console.scertificate.list', [
                'rows' => $rows,
                'materialName' => $materialName,
                'materialID'   => $query,
                'query' => '',
            ]);
        }
        return view('console.scertificate.list', [
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
            $sCertificate = new \App\SCertificate();
            $sCertificate->name = $name;
            $sCertificate->pinyin = \Input::get('pinyin')?:pinyin($name);
            $sCertificate->material_id = \Input::get('material');
            $sCertificate->letter = \Input::get('letter')?:letter($name);
            $sCertificate->save();
        }

        return redirect('console/scertificate');
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
        $sCertificate = \App\SCertificate::find($id);
        if ($name && $sCertificate) {
            $sCertificate->name = $name;
            $sCertificate->pinyin = \Input::get('pinyin')?:pinyin($name);
            $sCertificate->material_id = \Input::get('material');
            $sCertificate->letter = \Input::get('letter')?:letter($name);
            $sCertificate->save();
        }

        return redirect('console/scertificate');
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
        $sCertificate = \App\SCertificate::find($id);
        if ($sCertificate) {
            $sCertificate->delete();
        }

        return redirect('console/scertificate');
    }

}
