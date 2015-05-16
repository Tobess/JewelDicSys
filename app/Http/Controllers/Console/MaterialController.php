<?php namespace App\Http\Controllers\Console;

use App\Http\Requests;
use App\Http\Controllers\ConsoleController;

use Illuminate\Http\Request;

class MaterialController extends ConsoleController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
        $que = \App\Material::select('code', 'name', 'description', 'pinyin', 'letter', 'id', 'type');
        $query = \Input::get('query', '');
        if ($query) {
            $que->where('code', '=', $query)->orWhere('name', '=', $query);
        }
        $parent = \Input::get('parent', 0);
        if ($parent > 0) {
            $que->where('parent', $parent);
        }
        $rows = $que->orderBy('code')->paginate(10);

        return view('console.material.list', ['rows'=>$rows, 'query'=>$query, 'parent'=>$parent]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postStore()
	{
		$material = new \App\Material();
        $material->name = \Input::get('name');
        $material->parent = \Input::get('parent');
        $material->code = \Input::get('code');
        $material->type = \Input::get('type');
        $material->description = \Input::get('description');
        $material->mineral = \Input::get('mineral');
        $material->pinyin = pinyin($material->name);
        $material->letter = letter($material->name);
        $material->save();

        if ($material->id && $material->type == 1) {
            if (\App\Material::where('id', $material->parent)->whereRaw('parent>0')->count()) {
                $metal = new \App\MMetal();
                $metal->material_id = $material->id;
                $metal->condition = \Input::get('condition');
                $metal->chemistry = \Input::get('chemistry');
                $metal->chinese = \Input::get('chinese');
                $metal->english = \Input::get('english');
                $metal->metal = \Input::get('metal');
                $metal->save();
            }
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
		$material = \App\Material::find($id);
        $item = $material->toArray();
        if ($material->type == 1) {
            $metal = \App\MMetal::find($id);
            if ($metal && $metal->material_id) {
                $item = array_merge($item, $metal->toArray());
            }
        }

        return \Response::json($item);
	}

    /**
     * Get the parent's nodes of material
     *
     * @param  int  $type
     * @return Response
     */
    public function getParentList($type)
    {
        if ($type == 0) {
            $id = \Input::get('material');
            if ($id > 0) {
                $material = \App\Material::find($id);
                if ($material->parent == 0) {
                    $type = 1;
                } else {
                    $sMaterial = \App\Material::find($material->parent);
                    if ($sMaterial->parent == 0) {
                        $type = 2;
                    } else {
                        $type = 3;
                    }
                }
            } else {
                return \Response::json([]);
            }
        }
        if ($type == 1) {
            return \Response::json([['id'=>0, 'name'=>'顶级']]);
        } else {
            if ($type == 2 || $type == 3) {
                $pList = \App\Material::where('parent', 0)->get();
                $sParentNodes = [];
                $pTree = [];
                foreach ($pList as $pItem) {
                    $sParentNodes[] = $pItem->id;
                    $pTree[$pItem->id]['name'] = $pItem->name;
                    $pTree[$pItem->id]['id'] = $pItem->id;
                }
                if ($type == 2) {
                    return \Response::json(array_values($pTree));
                } else {
                    $sList = \App\Material::whereRaw('parent in ('.implode(',', $sParentNodes).')')->get();
                    foreach ($sList as $sItem) {
                        $pTree[$sItem->parent]['children'][] = ['id'=>$sItem->id, 'name'=>$sItem->name];
                    }

                    return \Response::json(array_values($pTree));
                }
            }

            return \Response::json([]);
        }
    }

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdate($id)
	{
        $material = \App\Material::find($id);
        $material->name = \Input::get('name');
        $material->code = \Input::get('code');
        $material->description = \Input::get('description');
        $material->mineral = \Input::get('mineral');
        $material->pinyin = pinyin($material->name);
        $material->letter = letter($material->name);
        $material->save();

        if ($material->type == 1) {
            if (\App\Material::where('id', $material->parent)->whereRaw('parent>0')->count()) {
                $metal = \App\MMetal::find($id);
                if (!$metal) {
                    $metal = new \App\MMetal();
                    $metal->material_id = $id;
                }
                $metal->condition = \Input::get('condition');
                $metal->chemistry = \Input::get('chemistry');
                $metal->chinese = \Input::get('chinese');
                $metal->english = \Input::get('english');
                $metal->metal = \Input::get('metal');
                $metal->save();
            }
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
        if (\App\Material::where('parent', $id)->count()) {
            return redirect()->back();
        }

        $material = \App\Material::find($id);
        if ($material->type == 1 && \App\MMetal::find($id)) {
            \App\MMetal::find($id)->delete();
        }
        $material->delete();

        return redirect()->back();
	}

}
