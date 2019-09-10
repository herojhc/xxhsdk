<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-07-06
 * Time: 9:46
 */

namespace XinXiHua\SDK\Services\Traits;


use XinXiHua\SDK\CorpClient;
use XinXiHua\SDK\IsvClient;

trait RestClient
{
    public function getIsvCorpClient($corpId = null, $service = null)
    {
        $accessToken = new CorpClient($service);
        return $accessToken->getClient($corpId);
    }

    public function getIsvClient($service = null)
    {
        $accessToken = new IsvClient($service);
        return $accessToken->getClient();
    }
}