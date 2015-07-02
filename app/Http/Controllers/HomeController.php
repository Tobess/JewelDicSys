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
        if (isset($posResults['words']) && !count($posResults['words'])) {
            $results = \App\Word::search($query, false);
        } else {
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
                foreach ($gNameArr as $gName) {
                    // 商品名称拆分队列
                    \Queue::push(function($job) use ($gName, $redisIdentify, $redis)
                    {
                        $gNameKey = $mainKey . ':'. md5($gName);
                        // S1 生成商品名称全拼码
                        $pinyin = pinyin($gName);
                        // S2 拆分分析
                        $results = \App\Word::search($pinyin);
                        if (isset($results['words']) && count($results['words'])) {
                            $_tmpWords = [];
                            foreach ($results['words'] as $word) {
                                $_tmpWords[] = implode(',', $word['refs']);
                            }
                            $redis->put($gNameKey, implode(';', $_tmpWords));
                        }
                        $job->delete();
                    });
                }

                return \Response::json(['state'=>true, 'message'=>'拆分请求已推送至队列中，请使用'.$redisIdentify.':{md5(商品名称)}的key从Redis中获取拆分结果']);
            }
        }

        return \Response::json(['state'=>false, 'message'=>'未能获取到商品名称.']);
    }
}
