<?php namespace App\Http\Controllers\Console;

use App\Http\Requests;
use App\Http\Controllers\ConsoleController;

use Illuminate\Http\Request;

class CraftController extends ConsoleController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
        $rows = \App\Craft::paginate(10);

        return view('console.craft.list', ['rows'=>$rows]);
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
            $craft = new \App\Craft;
            $craft->name = $name;
            $craft->pinyin = pinyin($name);
            $craft->letter = letter($name);
            $craft->save();
        }

        return redirect('console/crafts');
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
        $craft = \App\Craft::find($id);
        if ($name && $craft) {
            $craft->name = $name;
            $craft->pinyin = pinyin($name);
            $craft->letter = letter($name);
            $craft->save();
        }

        return redirect('console/crafts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function getDestroy($id)
    {
        $craft = \App\Craft::find($id);
        if ($craft) {
            $craft->delete();
        }

        return redirect('console/crafts');
    }

}
