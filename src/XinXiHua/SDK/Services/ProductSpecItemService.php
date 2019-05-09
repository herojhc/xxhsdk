<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-05-09
 * Time: 15:28
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\Exceptions\ApiException;

class ProductSpecItemService extends BaseService
{
    /**
     * @param $specId
     * @param $data
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function store($specId, $data, $corpId = null)
    {

        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/product/corp/specs/' . $specId, $data);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data']['id'];
        }
        throw new ApiException($response->getResponseMessage());

    }

    /**
     * @param $specId
     * @param $data
     * @param $id
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function update($specId, $data, $id, $corpId = null)
    {

        $response = $this->accessToken->getIsvCorpClient($corpId)->patch('/product/corp/specs/' . $specId . $id, $data);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data']['id'];
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $specId
     * @param $id
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function destroy($specId, $id, $corpId = null)
    {

        $response = $this->accessToken->getIsvCorpClient($corpId)->delete('/product/corp/specs/' . $specId . $id);
        if ($response->isResponseSuccess()) {
            return $id;
        }
        throw new ApiException($response->getResponseMessage());
    }
}