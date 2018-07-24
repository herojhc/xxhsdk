<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-07-24
 * Time: 11:51
 */

namespace XinXiHua\SDK\Services;


use Illuminate\Support\Facades\Log;
use XinXiHua\SDK\AccessToken;
use XinXiHua\SDK\Exceptions\ApiException;

class ContactService
{

    protected $accessToken;

    function __construct(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function all()
    {
        $isvCorpClient = $this->accessToken->getIsvCorpClient();

        $response = $isvCorpClient->get('/contacts');

        Log::info($response->getResponse());
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseData()['message']);

    }

}