<?php namespace App\Http\Controllers\Console;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class MoralController extends ConsoleController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
        $rows = \App\Moral::paginate(10);

        return view('console.moral.list', ['rows'=>$rows]);
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
            $moral = new \App\Moral;
            $moral->name = $name;
            $moral->pinyin = pinyin($name);
            $moral->letter = letter($name);
            $moral->save();
        }

        return redirect('console/morals');
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
        $moral = \App\Moral::find($id);
        if ($name && $moral) {
            $moral->name = $name;
            $moral->pinyin = pinyin($name);
            $moral->letter = letter($name);
            $moral->save();
        }

        return redirect('console/morals');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function getDestroy($id)
    {
        $moral = \App\Moral::find($id);
        if ($moral) {
            $moral->delete();
        }

        return redirect('console/morals');
    }

}
