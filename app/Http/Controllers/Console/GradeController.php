<?php namespace App\Http\Controllers\Console;

use App\Http\Requests;
use App\Http\Controllers\ConsoleController;

use Illuminate\Http\Request;

class GradeController extends ConsoleController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
        $rows = \App\Grade::paginate(10);

        return view('console.grade.list', ['rows'=>$rows]);
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
            $grade = new \App\Grade;
            $grade->name = $name;
            $grade->pinyin = pinyin($name);
            $grade->letter = letter($name);
            $grade->save();
        }

        return redirect('console/grades');
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
        $grade = \App\Grade::find($id);
        if ($name && $grade) {
            $grade->name = $name;
            $grade->pinyin = pinyin($name);
            $grade->letter = letter($name);
            $grade->save();
        }

        return redirect('console/grades');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function getDestroy($id)
    {
        $grade = \App\Grade::find($id);
        if ($grade) {
            $grade->delete();
        }

        return redirect('console/grades');
    }

}
