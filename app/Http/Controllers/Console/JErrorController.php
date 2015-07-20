<?php namespace App\Http\Controllers\Console;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class JErrorController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
        $query = \Input::get('query', '');
        if ($query) {
            $rows = \App\JError::
                where(function($que) use ($query) {
                    $que->where('domain', 'like', $query.'%')
                        ->orWhere('mobile', 'like', $query.'%');
                })
                ->paginate(20);
        } else {
            $rows = \App\JError::paginate(10);
        }

        return view('console.jerror.list', ['rows'=>$rows, 'query'=>$query]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postStore()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getShow($id)
	{
		//
	}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getProfile($id)
    {
        $error = \App\JError::find($id);
        if (!$error) {
            return redirect()->back();
        }

        return view('console.jerror.profile', ['row'=>$error]);
    }

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdate($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getDestroy($id)
	{
        $error = \App\JError::find($id);
        if ($error) {
            $error->delete();
        }

        return redirect()->back();
	}

}
