<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2021-01-09
 * Time: 18:36
 */

namespace XinXiHua\SDK\Auth;

use Illuminate\Contracts\Session\Session;
use XinXiHua\SDK\Models\OauthUser;
use Illuminate\Support\Facades\Cache;

class OauthManager
{
    /**
     * @var OauthUser|null
     */
    protected $oauth = null;
    protected $session;


    /**
     * OauthManager constructor.
     * @param  \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct($app)
    {
        $this->session = $app->make(Session::class);
    }

    /**
     * @return bool
     */
    public function check()
    {
        return !is_null($this->oauth());
    }

    /**
     * @return OauthUser|null
     */
    public function oauth()
    {

        if (!is_null($this->oauth)) {
            return $this->oauth;
        }

        if ($this->session->isStarted()) {
            $oauth = json_decode($this->session->get($this->getName()), true);
            $this->setOauth($oauth);
        }

        return $this->oauth;
    }

    public function id()
    {
        return $this->oauth() ? $this->oauth->oauthId : null;
    }

    protected function getName()
    {
        return 'login_oauth_' . sha1(static::class);
    }

    public function setOauth($oauth)
    {
        $oauthUser = new OauthUser();
        $oauthUser->oauthId = $oauth['open_id'];
        $oauthUser->oauthType = $oauth['oauth_type'] ?? 'wechat';
        $this->oauth = $oauthUser;
    }

    public function login($oauth)
    {
        $this->session->put($this->getName(), json_encode($oauth, JSON_UNESCAPED_UNICODE));
        $this->setOauth($oauth);
        return $this->oauth;

    }


    protected function getAuthTokenKey($token)
    {
        return 'oauth.auth_token.' . sha1(static::class) . '.' . $token;
    }

    public function setAuthToken()
    {
        $token = $this->token();
        $cacheKey = $this->getAuthTokenKey($token);
        Cache::put($cacheKey, [
            'oauth_id' => $this->oauth->oauthId,
            'oauth_type' => $this->oauth->oauthType
        ], 15);
        return $token;
    }

    public function getAuthToken($token)
    {
        $cacheKey = $this->getAuthTokenKey($token);
        return Cache::get($cacheKey);
    }

    protected function token()
    {
        return hash_hmac('sha256', \Illuminate\Support\Str::random(40), $this->id());
    }
}