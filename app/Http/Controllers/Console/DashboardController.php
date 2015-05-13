<?php namespace App\Http\Controllers\Console;

use App\Http\Requests;
use App\Http\Controllers\ConsoleController;

use Illuminate\Http\Request;

class DashboardController extends ConsoleController {

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getIndex()
    {
        return view('console.main');
    }

    /**
     * Generate words pinyin cache & pinyin link relations
     *
     * @param  int $type
     * @return \Illuminate\Http\Response
     */
    public function getGenerateCache($type)
    {
        $wRef = \App\WRef::getRefById($type);
        if (is_array($wRef)) {
            $dQue = \DB::table($wRef['table']);
            if (isset($wRef['where'])) {
                $dQue->whereRaw($wRef['where']);
            }
            $data = $dQue->get();
            foreach ($data as $row) {
                \App\Word::wordRefToLink($type, $row->id, $row->pinyin);
                \App\Word::wordRefToLink($type, $row->id, $row->letter, false);
            }

            return \Response::json([]);
        } else {
            return \Response::json(['message'=>'无效的类型']);
        }
    }
}
