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
        set_time_limit(0);
        // 清除缓存
        //\Artisan::call('cache:clear');

        \DB::transaction(function()
        {
            // 生成所有词根
            if (\App\WRelation::count()) {
                \DB::table('words_relations')->delete();
            }
            if (\App\Word::count()) {
                \DB::table('words')->delete();
            }

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

            // 生成拼音词根缓存文件
            \App\WPinyin::generateDict();
        });

        return redirect()->back();
    }

    /**
     * 修复部分拼音生成错误
     */
    public function getFixPinyin()
    {
        $wRefs = \App\WRef::allRefs();
        foreach ($wRefs as $wRef) {
            if (is_array($wRef)) {
                $dQue = \DB::table($wRef['table']);
                if (isset($wRef['where'])) {
                    $dQue->whereRaw($wRef['where']);
                }
                $data = $dQue->where('name', 'like', '%足%')->get();
                foreach ($data as $row) {
                    $row->pinyin = pinyin($row->name);
                    $row->letter = letter($row->name);
                    $row->save();
                }
            }
        }
    }

}
