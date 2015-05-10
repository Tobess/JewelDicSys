<?php namespace App\Http\Controllers\Console;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class RuleController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
        $rows = \App\Rule::paginate(20);

        return view('console.rule.list', ['rows'=>$rows]);
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
            $rule = new \App\Rule;
            $rule->name = $name;
            $rule->code = $code;
            $rule->pinyin = pinyin($name);
            $rule->letter = letter($name);
            $rule->save();
        }

        return redirect('console/rules');
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
        $rule = \App\Rule::find($id);
        if (($name || $code) && $rule) {
            $rule->name = $name;
            $rule->code = $code;
            $rule->pinyin = pinyin($name);
            $rule->letter = letter($name);
            $rule->save();
        }

        return redirect('console/rules');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function getDestroy($id)
    {
        $rule = \App\Rule::find($id);
        if ($rule) {
            $rule->delete();
        }

        return redirect('console/rules');
    }

}
