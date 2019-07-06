<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-07-06
 * Time: 9:46
 */

namespace XinXiHua\SDK\Services\Traits;


use XinXiHua\SDK\AccessToken;

trait RestClient
{
    public function getIsvCorpClient($corpId = null, $service = null)
    {
        $accessToken = new AccessToken($service);
        return $accessToken->getIsvCorpClient($corpId);
    }

    public function getIsvClient($service = null)
    {
        $accessToken = new AccessToken($service);
        return $accessToken->getIsvClient();
    }
}