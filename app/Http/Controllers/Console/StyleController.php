<?php namespace App\Http\Controllers\Console;

use App\Http\Requests;
use App\Http\Controllers\ConsoleController;

use Illuminate\Http\Request;

class StyleController extends ConsoleController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
        $rows = \App\Style::paginate(10);

        return view('console.style.list', ['rows'=>$rows]);
	}

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postStore()
    {
        $name = \Input::get('name');
        $code = \Input::get('code');
        if ($name || $code) {
            $style = new \App\Style;
            $style->name = $name;
            $style->code = $code;
            $style->pinyin = pinyin($name);
            $style->letter = letter($name);
            $style->save();
        }

        return redirect('console/styles');
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
        $code = \Input::get('code');
        $style = \App\Style::find($id);
        if (($name || $code) && $style) {
            $style->name = $name;
            $style->code = $code;
            $style->pinyin = pinyin($name);
            $style->letter = letter($name);
            $style->save();
        }

        return redirect('console/styles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function getDestroy($id)
    {
        $style = \App\Style::find($id);
        if ($style) {
            $style->delete();
        }

        return redirect('console/styles');
    }

}
