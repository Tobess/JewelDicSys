<?php namespace App\Http\Controllers\Console;

use App\Http\Requests;
use App\Http\Controllers\ConsoleController;

use Illuminate\Http\Request;

class RuleController extends ConsoleController {

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
        $configure = \Input::get('configure');
        if ($name && $configure) {
            $rule = new \App\Rule;
            $rule->name = $name;
            $rule->configure = $configure;
            $rule->save();
            \Cache::forget('rules');
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
        $configure = \Input::get('configure');
        $rule = \App\Rule::find($id);
        if ($name && $configure && $rule) {
            $rule->name = $name;
            $rule->configure = $configure;
            $rule->save();
            \Cache::forget('rules');
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
            \Cache::forget('rules');
        }

        return redirect('console/rules');
    }

}
