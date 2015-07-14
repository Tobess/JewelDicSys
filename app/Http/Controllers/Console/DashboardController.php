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
        $keyPrefix = 'laravel:dictionary:';
        foreach (['pinyin*', 'words*', 'rules*', 'aliases*', 'data*'] as $key) {
            $exitCode = \Artisan::call('redis:clear', ['key' => $keyPrefix.$key]);
        }

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

    public function getTest($pinyin)
    {
        \App\WPinyin::match($pinyin);
    }
}
