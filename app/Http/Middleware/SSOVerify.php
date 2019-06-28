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
        $signTxt = \Input::header('X_JEAT_SIGNATURE_TEXT');
        if (!$signTxt) {
            $signTxt = json_encode(\Input::all(), JSON_NUMERIC_CHECK);
        }

        // 签名在头部信息的 x-jeat-signature 字段
        $signature = \Input::header('X_JEAT_SIGNATURE');
        if ($signature) {
            $pkPath = env('JEAT_AUTH_KEY_PATH', storage_path('keys/jeat_public_key.pem'));
            if (file_exists($pkPath)) {
                $public_key = file_get_contents($pkPath);
            }

            if (isset($public_key) && $public_key &&
                openssl_verify($signTxt, base64_decode($signature), $public_key, OPENSSL_ALGO_SHA256)) {
                $authorized = true;
            }
        }

        if (isset($authorized) && $authorized) {
            return $next($request);
        }

        return response(isset($msg) ? $msg : '身份鉴权失败！', 401);
	}
}
