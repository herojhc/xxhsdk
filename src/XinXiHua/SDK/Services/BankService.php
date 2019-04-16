<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-04-16
 * Time: 15:07
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\Exceptions\ApiException;

class BankService extends BaseService
{

    /**
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function all($corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->get('/banks');
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseMessage());

    }
}