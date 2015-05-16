<?php namespace App\Http\Controllers\Console;

use App\Http\Requests;
use App\Http\Controllers\ConsoleController;

use Illuminate\Http\Request;

class VarietyController extends ConsoleController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
        $que = \App\Variety::select('code', 'name', 'description', 'pinyin', 'letter', 'id');
        $query = \Input::get('query', '');
        if ($query) {
            $que->where('code', '=', $query)->orWhere('name', '=', $query);
        }
        $parent = \Input::get('parent', 0);
        if ($parent > 0) {
            $que->where('parent', $parent);
        }
        $rows = $que->orderBy('code')->paginate(10);

        return view('console.variety.list', ['rows'=>$rows, 'query'=>$query, 'parent'=>$parent]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postStore()
	{
        $variety = new \App\Variety();
        $variety->name = \Input::get('name');
        $variety->parent = \Input::get('parent');
        $variety->code = \Input::get('code');
        $variety->type = \Input::get('type');
        $variety->description = \Input::get('description');
        $variety->pinyin = pinyin($variety->name);
        $variety->letter = letter($variety->name);
        $variety->save();

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
        $variety = \App\Variety::find($id);
        $item = $variety->toArray();

        return \Response::json($item);
	}

    /**
     * Get the parent's nodes of variety
     *
     * @param  int  $type
     * @return Response
     */
    public function getParentList($type)
    {
        if ($type == 0) {
            $id = \Input::get('variety');
            if ($id > 0) {
                $variety = \App\Variety::find($id);
                if ($variety->parent == 0) {
                    $type = 1;
                } else {
                    $sVariety = \App\Variety::find($variety->parent);
                    if ($sVariety->parent == 0) {
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
                $pList = \App\Variety::where('parent', 0)->get();
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
                    $sList = \App\Variety::whereRaw('parent in ('.implode(',', $sParentNodes).')')->get();
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
        $variety = \App\Variety::find($id);
        $variety->name = \Input::get('name');
        $variety->code = \Input::get('code');
        $variety->description = \Input::get('description');
        $variety->pinyin = pinyin($variety->name);
        $variety->letter = letter($variety->name);
        $variety->save();

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
        if (\App\Variety::where('parent', $id)->count()) {
            return redirect()->back();
        }
        $variety = \App\Variety::find($id);
        $variety->delete();

        return redirect()->back();
	}

}
