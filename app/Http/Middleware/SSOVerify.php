<?php namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Curl\Curl;
use Illuminate\Support\Facades\Cache;

class SSOVerify {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        $bearerToken = $request->header('Authorization', '');
        if ($bearerToken) {
            //根据调式模式选择服务器
            $cTokenKey = 'sys.sso.token:' . md5($bearerToken);
            $cData = \Cache::get($cTokenKey);
            $cacheToken = isset($cData['token']) ? $cData['token'] : null;
            //验证token缓存
            if (!($authenticated = ($bearerToken == $cacheToken))) {
                if ($user = self::getSSOUserInfoByBearerToken($bearerToken)) {
                    $authenticated = true;
                }
            }

            if ($authenticated) {
                return $next($request);
            }
        } else {
            $msg = '缺少用户身份信息！';
        }

        return response(isset($msg) ? $msg : '身份鉴权失败！', 401);
	}

    /**
     * 通过访问令牌获取登陆用户信息
     *
     * @param string $token 访问令牌
     * @return null
     */
    public static function getSSOUserInfoByBearerToken($token)
    {
        $sso_url = env('AUTH_SSO_URL', 'http://login.fromai.cn/api/');
        $curl = new Curl();
        $curl->setHeader('Authorization', $token);
        $curl->post($sso_url . 'auth-info');
        $uRst = $curl->response;

        $user = null;
        if ((isset($uRst->code) &&
            200 == $uRst->code &&
            isset($uRst->data) &&
            isset($uRst->data->id) && $uRst->data->id > 0)) {
            $user = $uRst->data;

            // 判断是否是首次在个人端使用token
            $cTokenKey = 'sys.sso.token:' . md5($token);
            \Cache::put($cTokenKey, ['user' => $user, 'token' => $token], Carbon::now()->addHour(2));
        }

        return $user;
    }

}
