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
     * @return \Illuminate\Http\Response
     */
    public function getGenerateCache()
    {
        // 清除缓存
        $keysPinyin = \Redis::keys('pinyin*');
        \Redis::forgot($keysPinyin);
        $keysWords = \Redis::keys('words*');
        \Redis::forgot($keysWords);
        $keysRules = \Redis::keys('rules*');
        \Redis::forgot($keysRules);
        $keysAlias = \Redis::keys('aliases*');
        \Redis::forgot($keysAlias);
        $keysData = \Redis::keys('data*');
        \Redis::forgot($keysData);

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
            \App\Word::wordRefToLink($alias->rel_type, $alias->rel_id, $alias->pinyin);
            \App\Word::wordRefToLink($alias->rel_type, $alias->rel_id, $alias->letter, false);
        }

        // 生成所有数据关联关系
        \App\DLink::generateCache();

        return redirect()->back();
    }
}
