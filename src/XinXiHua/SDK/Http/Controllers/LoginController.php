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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use XinXiHua\SDK\Facades\XXH;
use XinXiHua\SDK\Services\AuthService;

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
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectToAdmin = '/admin';

    /**
     * @var mixed 网关
     */
    protected $gatewayUrl;

    /**
     * @var mixed 应用ID
     */
    protected $agentId;

    /**
     * @var AuthService
     */
    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
        $this->gatewayUrl = config('xxh-sdk.agent.gateway_url');
        $this->agentId = config('xxh-sdk.agent.agent_id');

        if (config('xxh-sdk.home.url')) {
            $this->redirectTo = config('xxh-sdk.home.url');
        }

        if (config('xxh-sdk.admin.url')) {
            $this->redirectToAdmin = config('xxh-sdk.home.url');
        }

    }


    public function login(Request $request)
    {
        $agentId = $this->agentId;
        $redirectUrl = urlencode(config('xxh-sdk.home.url'));
        return redirect($this->gatewayUrl . '?agent_id=' . $agentId . '&redirect_url=' . $redirectUrl);
    }

    public function admin(Request $request)
    {

        // 后台

        // 验证权限

    }


    public function callback(Request $request)
    {

        $authCode = $request->get('auth_code');
        $corpId = $request->get('corp_id');

        if (!$authCode || !$corpId) {
            return '参数错误！';
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
            return $this->sendLoginResponse($request);


        } catch (\Exception $exception) {
            Log::info($exception->getMessage(), ['exception' => $exception]);
        }


        return '登陆失败或授权码已过期';

    }

}