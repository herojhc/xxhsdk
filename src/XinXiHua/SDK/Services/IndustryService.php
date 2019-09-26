<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-09-26
 * Time: 18:32
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\Exceptions\ApiException;

class IndustryService extends BaseService
{
    /**
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function all($corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->get('/industries');
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseMessage());

    }
}