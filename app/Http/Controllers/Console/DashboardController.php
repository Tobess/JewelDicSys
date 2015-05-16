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

//    /**
//     * Generate words pinyin cache & pinyin link relations
//     *
//     * @param  int $type
//     * @return \Illuminate\Http\Response
//     */
//    public function getGenerateCache($type)
//    {
//        $wRef = \App\WRef::getRefById($type);
//        if (is_array($wRef)) {
//            $dQue = \DB::table($wRef['table']);
//            if (isset($wRef['where'])) {
//                $dQue->whereRaw($wRef['where']);
//            }
//            $data = $dQue->get();
//            foreach ($data as $row) {
//                \App\Word::wordRefToLink($type, $row->id, $row->pinyin);
//                \App\Word::wordRefToLink($type, $row->id, $row->letter, false);
//            }
//
//            return \Response::json([]);
//        } else {
//            return \Response::json(['message'=>'无效的类型']);
//        }
//    }

    /**
     * Generate words pinyin cache & pinyin link relations
     *
     * @return \Illuminate\Http\Response
     */
    public function getGenerateCache()
    {
        // 生成所有词根
        $wRefs = \App\WRef::allRefs();
        foreach ($wRefs as $wRef) {
            if (is_array($wRef)) {
                $dQue = \DB::table($wRef['table']);
                if (isset($wRef['where'])) {
                    $dQue->whereRaw($wRef['where']);
                }
                $data = $dQue->get();
                \App\WRelation::unlinkByType($wRef['id']);
                foreach ($data as $row) {
                    \App\Word::wordRefToLink($wRef['id'], $row->id, $row->pinyin);
                    \App\Word::wordRefToLink($wRef['id'], $row->id, $row->letter, false);
                }
            }
        }

        // 对所有别名进行链接
        $aliases = \App\WAlias::all();
        foreach ($aliases as $alias) {
            \App\Word::wordRefToLink($alias->rel_type, $alias->rel_id, $row->pinyin);
            \App\Word::wordRefToLink($alias->rel_type, $alias->rel_id, $row->letter, false);
        }

        return redirect()->back();
    }
}
