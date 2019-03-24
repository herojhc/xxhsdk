<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-03-24
 * Time: 11:46
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\Exceptions\ApiException;

class AgentService extends BaseService
{

    /**
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function installed($corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->get('/agents');
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseMessage());
    }
}