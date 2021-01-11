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
}