<?php namespace App\Http\Controllers\Console;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AliasController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$type = \Input::get('type');
        $parent = \Input::get('parent');

        $wRef = \App\WRef::getRefById($type);
        if (is_array($wRef)) {
            $pRow = \DB::table($wRef['table'])->find($parent);
            if (!$pRow) {
                return redirect('console');
            }
        } else {
            return redirect('console');
        }

        $aliases = \App\WAlias::where('rel_type', $type)->where('rel_id', $parent)->paginate(10);

        return view('console.alias.list', ['rows'=>$aliases, 'pItem'=>$pRow, 'tItem'=>$wRef]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postStore()
	{
		$alias = new \App\WAlias();
        $alias->name = \Input::get('name');
        $alias->rel_type = \Input::get('rel_type');
        $alias->rel_id = \Input::get('rel_id');
        $alias->pinyin = pinyin($alias->name);
        $alias->letter = letter($alias->name);
        $alias->save();

        return redirect()->back();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getShow($id)
	{
        $alias = \App\WAlias::find($id);

        return \Response::json($alias->toArray());
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdate($id)
	{
        $alias = \App\WAlias::find($id);
        $alias->name = \Input::get('name');
        $alias->pinyin = pinyin($alias->name);
        $alias->letter = letter($alias->name);
        $alias->save();

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
        $alias = \App\WAlias::find($id);
        if ($alias) {
            $alias->delete();
        }

        return redirect()->back();
    }

}
