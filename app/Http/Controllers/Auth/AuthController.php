<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers;

    protected $redirectTo = '/console';

    protected $redirectAfterLogout = '/';

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;

		$this->middleware('guest', ['except' => 'getLogout']);
	}

    /**
     * Send register token to administrator.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getVerifyCode()
    {
        $name = \Input::get('name');
        $email = \Input::get('email');
        if ($name && $email) {
            if (\App\User::where('email', $email)->count()) {
                return \Response::json(['message'=>'该电子邮箱已经注册.']);
            }

            $token = mt_rand(10000000, 99999999);
            \Session::put('verify_code', $token);
            \Mail::raw($name.'正在注册珠宝名称系统，验证码为：'.$token, function($message)
            {
                $message->to('product@fromai.com', '智爱科技－产品部');
            });
            return \Response::json([]);
        } else {
            return \Response::json(['message'=>'姓名或电子邮箱地址不能为空.']);
        }
    }

}
