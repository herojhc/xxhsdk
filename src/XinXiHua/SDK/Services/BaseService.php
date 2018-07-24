<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-07-24
 * Time: 16:08
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\AccessToken;

class BaseService
{

    protected $client;


    function __construct(AccessToken $accessToken)
    {
        $this->client = $accessToken->getIsvCorpClient();
    }
}