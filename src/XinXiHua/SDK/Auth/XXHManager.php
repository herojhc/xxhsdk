<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-07-23
 * Time: 16:53
 */

namespace XinXiHua\SDK\Auth;


use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use XinXiHua\SDK\Events\Login;
use XinXiHua\SDK\Models\Corporation;

class XXHManager
{

    protected $corp = null;
    protected $session;
    protected $request;


    /**
     * XxhManager constructor.
     * @param  \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct($app)
    {
        $this->session = $app->make(Session::class);
        $this->request = $app->make(Request::class);

    }

    /**
     * @return bool
     */
    public function check()
    {
        return !is_null($this->corp());
    }

    /**
     * @return mixed|null|Corporation
     */
    public function corp()
    {

        if (!is_null($this->corp)) {
            return $this->corp;
        }

        $id = $this->getId();

        $corp = null;

        if (!is_null($id)) {
            try {
                $corp = Corporation::query()->find($id);
            } catch (ModelNotFoundException $exception) {
                $corp = null;
            }
        }

        return $this->corp = $corp;
    }

    public function getId()
    {
        if ($this->session->isStarted()) {
            $id = $this->session->get($this->getName());
        } else {
            $id = $this->request->header('CorpId');
        }

        return $id;

    }

    protected function getName()
    {
        return 'login_corp_' . sha1(static::class);
    }

    public function getCorp()
    {
        return $this->corp;
    }

    public function setCorp($corp)
    {
        $this->corp = $corp;

        return $this;
    }

    public function id()
    {
        return $this->corp()
            ? $this->corp()->getKey()
            : $this->getId();
    }

    public function login(Corporation $corp)
    {
        $this->session->put($this->getName(), $corp->getKey());

        $this->setCorp($corp);

        event(new Login($corp));
    }

    public function loginUsingId($id)
    {

        try {
            $corp = Corporation::query()->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            $corp = null;
        }
        if ($corp) {
            $this->session->put($this->getName(), $corp->getKey());

            $this->setCorp($corp);

            event(new Login($corp));
        }
        return $corp;

    }


}