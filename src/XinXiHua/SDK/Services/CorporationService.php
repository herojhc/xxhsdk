<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-03-08
 * Time: 14:37
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\Exceptions\ApiException;

class CorporationService extends BaseService
{

    /**
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function basic($corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->get('/corporation/basic');
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseMessage());

    }


    /**
     * @param array $include
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function details($include = [], $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->get('/corporation/details', [
            'include' => implode(',', $include)
        ]);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseMessage());

    }
}