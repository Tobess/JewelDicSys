<?php namespace App\Http\Controllers\Console;

use App\Http\Requests;
use App\Http\Controllers\ConsoleController;

use Illuminate\Http\Request;

class ColorController extends ConsoleController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
        $query = \Input::get('query', '');
        if ($query) {
            $rows = \App\Color::
                where(function($que) use ($query) {
                    $que->where('pinyin', 'like', $query.'%')
                        ->orWhere('letter', 'like', $query.'%')
                        ->orWhere('name', 'like', $query.'%');
                })
                ->paginate(20);
        } else {
            $rows = \App\Color::paginate(10);
        }

        return view('console.color.list', ['rows'=>$rows, 'query'=>$query]);
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
            $color = new \App\Color;
            $color->name = $name;
            $color->pinyin = \Input::get('pinyin')?:pinyin($name);
            $color->letter = \Input::get('letter')?:letter($name);
            $color->save();
        }

        return redirect('console/colors');
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
        $color = \App\Color::find($id);
        if ($name && $color) {
            $color->name = $name;
            $color->pinyin = \Input::get('pinyin')?:pinyin($name);
            $color->letter = \Input::get('letter')?:letter($name);
            $color->save();
        }

        return redirect('console/colors');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function getDestroy($id)
    {
        $color = \App\Color::find($id);
        if ($color) {
            $color->delete();
        }

        return redirect('console/colors');
    }

}
