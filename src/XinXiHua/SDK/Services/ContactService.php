<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-07-24
 * Time: 11:51
 */

namespace XinXiHua\SDK\Services;


use Illuminate\Support\Facades\Log;
use XinXiHua\SDK\Exceptions\ApiException;

class ContactService extends BaseService
{


    public function all()
    {
        $response = $this->client->get('/contacts');

        Log::info($response->getResponse());
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseData()['message']);

    }

}