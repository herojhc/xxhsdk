<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-03-28
 * Time: 11:48
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\Exceptions\ApiException;

class LinkmanService extends BaseService
{

    /**
     * @param $data
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function store($data, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/linkmen', $data);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data']['id'];
        }
        throw new ApiException($response->getResponseMessage());

    }
}