<?php namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use League\Flysystem\Exception;

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
        $fullMatch = \Input::get('fullMatch') == 'Y';
        if (preg_match("/[\x7f-\xff]/", $query)) {
            $query = \App\Word::getPinyinAndCache($query);
        }
        $posResults = \App\Word::search($query);
        if (isset($posResults['words']) && !count($posResults['words'])) {
            $results = [];//\App\Word::search($query, false);
        } else {
            if ($fullMatch && isset($posResults['words'])) {
                // 全字匹配
                $newWords = [];
                $fullWord = trim(\Input::get('query'));
                foreach ($posResults['words'] as $item) {
                    if ($item['title'] == $fullWord || strtolower($item['title']) == strtolower($fullWord)) {
                        $newWords[] = $item;
                    }
                }
                $posResults['words'] = $newWords;
            }

            $results = $posResults;
        }
        return \Response::json($results);
    }

    /**
     * 批量拆分商品名称并缓存
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAnalyse()
    {
        $gNames = \Input::get('names');
        if ($gNames) {
            $namesIdentify = md5($gNames);
            $gNameArr = explode(',', $gNames);
            $sKey = $namesIdentify.':status';
            \Cache::put($namesIdentify, $gNames, Carbon::now()->endOfDay()->diffInSeconds());
            foreach ($gNameArr as $gName) {
                // 商品名称拆分队列
                \Queue::push(function($job) use ($gName, $namesIdentify, $sKey) {
                    try {
                        $gMd5Key = md5($gName);
                        $gNameKey = $namesIdentify . ':' . $gMd5Key;
                        // S1 生成商品名称全拼码
                        $pinyin = \App\Word::getPinyinAndCache($gName);

                        // S2 拆分分析
                        $results = \App\Word::search($pinyin);
                        if (isset($results['words']) && !empty($results['words'])) {
                            \Cache::put($gNameKey, ['key' => $gMd5Key, 'result' => $results], Carbon::now()->endOfDay()->diffInSeconds());
                        }

                        if (!\Cache::has($sKey)) {
                            \Cache::put($sKey, 1, 180);
                        } else {
                            \Cache::increment($sKey);
                        }
                    } catch (Exception $ex) {
                        Log::info($ex->getTraceAsString());
                    }

                    $job->delete();
                });
            }

            return \Response::json([
                'state' => true,
                'data' => $namesIdentify,
                'message' => '请通过analyse/result接口获取结果。'
            ]);
        }

        return \Response::json([
            'state' => false,
            'message' => '分析任务启动失败。'
        ]);
    }

    /**
     * 获得批量拆分商品名称结果
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAnalyseResult()
    {
        $namesIdentify = \Input::get('identify');
        $sKey = $namesIdentify.':status';
        if (\Cache::has($namesIdentify) && \Cache::has($sKey)) {
            $gNames = \Cache::get($namesIdentify);
            $gNameArr = explode(',', $gNames);
            $analysedCount = \Cache::get($sKey);
            if ($analysedCount > 0 && $analysedCount == count($gNameArr)) {
                $gResults = [];
                foreach ($gNameArr as $gName) {
                    // 商品名称拆分结果
                    $gMd5Key = md5($gName);
                    $gNameKey = $namesIdentify . ':' . $gMd5Key;
                    if (\Cache::has($gNameKey)) {
                        $gRet = \Cache::get($gNameKey);
                        if (isset($gRet['key']) && $gRet['result']) {
                            $gResults[$gRet['key']] = $gRet['result'];
                        }
                    }
                }
                return \Response::json([
                    'state' => true,
                    'data' => $gResults
                ]);
            }
        }

        return \Response::json([
            'state' => false,
            'message' => '分析结果获取失败。'
        ]);
    }
}
