<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

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
     * @param string $redisIdentify 商品名称列表在Redis服务器中的key
     * @return Response
     */
    public function getAnalyse($redisIdentify)
    {
        // S1 通过Redis Key获取商品名称列表
        $redis = \Redis::connection('serve');
        if ($redis->exists($redisIdentify)) {
            $gNames = $redis->get($redisIdentify);
            if ($gNames) {
                $gNameArr = explode(',', $gNames);
                $sKey = $redisIdentify.':status';
                $redis->del($sKey);

                Log::info("####################################################################################");

                foreach ($gNameArr as $gName) {
                    // 商品名称拆分队列
                    \Queue::push(function($job) use ($gName, $redisIdentify, $sKey, $redis)
                    {
                        $gNameKey = $redisIdentify . ':'. md5($gName);
                        // S1 生成商品名称全拼码
                        $pinyin = \App\Word::getPinyinAndCache($gName);
                        Log::info($pinyin);

                        // S2 拆分分析
                        $results = \App\Word::search($pinyin);
                        Log::info(print_r($results, true));
                        if (isset($results['words']) && count($results['words'])) {
                            $redis->set($gNameKey, json_encode($results));
                            $redis->expire($gNameKey, 24*60*60);
                        }

                        $redis->incr($sKey);
                        $redis->expire($sKey, 24*60*60);

                        $job->delete();
                    });
                }

                return \Response::json(['state'=>true, 'message'=>'拆分请求已推送至队列中，请使用'.$redisIdentify.':status获取生成进度,使用'.$redisIdentify.':{md5(商品名称)}的key从Redis中获取拆分结果']);
            }
        }

        return \Response::json(['state'=>false, 'message'=>'未能获取到商品名称.']);
    }
}
