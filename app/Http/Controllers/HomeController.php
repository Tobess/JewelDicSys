<?php namespace App\Http\Controllers;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		return view('home');
	}

    /**
     * Search jewel property & product name by chinese pinyin.
     *
     * @param string $query
     * @return Response
     */
    public function getSearch()
    {
        $query = \Input::get('query');
        $posResults = \App\Word::search($query);
        if (!count($posResults['words'])) {
            $results = \App\Word::search($query, false);
        } else {
            $results = $posResults;
        }
        return \Response::json($results);
    }
}
