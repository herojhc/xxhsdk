<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-07-24
 * Time: 8:30
 */

namespace XinXiHua\SDK\Http\Controllers;


use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use XinXiHua\SDK\Facades\XXH;
use XinXiHua\SDK\Services\AuthService as Service;

class LoginController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * @var mixed 网关
     */
    protected $gatewayUrl;

    /**
     * @var mixed 应用ID
     */
    protected $agentId;

    /**
     * @var Service
     */
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
        $this->gatewayUrl = config('xxh-sdk.agent.gateway_url');
        $this->agentId = config('xxh-sdk.agent.agent_id');

        if (config('xxh-sdk.redirect.home')) {
            $this->redirectTo = config('xxh-sdk.redirect.home');
        }
    }


    public function login(Request $request)
    {
        $agentId = $this->agentId;

        $baseUrl = rtrim($request->getSchemeAndHttpHost(), '\/');
        $redirectUri = config('xxh-sdk.callback.home');
        $service = $request->get('service', null);
        if (!empty($service)) {
            if (stripos($redirectUri, '?')) {
                $redirectUri .= '&service=' . base64_encode(urldecode($service));
            } else {
                $redirectUri .= '?service=' . base64_encode(urldecode($service));
            }
        }

        if (!stripos($redirectUri, 'http')) {
            $redirectUri = $baseUrl . '/' . ltrim($redirectUri, '\/');
        }

        $gatewayUri = $this->gatewayUrl . '?agent_id=' . $agentId . '&redirect_uri=' . urlencode($redirectUri) . '&scope=authorize_contact&response_type=code';

        // 附加参数
        if (!empty($request->get('corp_id'))) {
            $gatewayUri .= '&corp_id=' . $request->get('corp_id');
        }

        return redirect($gatewayUri);
    }


    public function callback(Request $request)
    {

        $authCode = $request->get('auth_code');
        $corpId = $request->get('corp_id');

        if (!$authCode || !$corpId) {
            return '参数错误';
        }

        try {

            $userId = $this->service->setUserInfo($corpId, $authCode);
            if (!$userId) {
                return '获取用户信息失败';
            }

            if (!$this->service->setAuthInfo($corpId)) {
                return '获取企业信息失败';
            }

            // 执行登录
            XXH::loginUsingId($corpId);
            Auth::loginUsingId($userId, false);

            // 定义跳转url
            if (!empty($request->get('service', ''))) {
                $request->session()->put('url.intended', base64_decode($request->get('service', '')));
            }
            return $this->sendLoginResponse($request);

        } catch (\Exception $exception) {
            Artisan::call('cache:clear');
            Log::error($exception->getMessage(), ['exception' => $exception]);
        }

        return '登陆失败或授权码已过期';
    }

}