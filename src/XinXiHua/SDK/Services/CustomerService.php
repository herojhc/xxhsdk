<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-04-02
 * Time: 17:53
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\Exceptions\ApiException;

class CustomerService extends BaseService
{
    /**
     * @param $data
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function store($data, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/customers', $data);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data']['id'];
        }
        throw new ApiException($response->getResponseMessage());

    }

    /**
     * @param $rows
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function import($rows, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/customers?storeType=import', $rows);
        if ($response->isResponseSuccess()) {
            return true;
        }
        throw new ApiException($response->getResponseMessage());

    }

    /**
     * @param $data
     * @param $id
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function update($data, $id, $corpId = null)
    {

        $response = $this->accessToken->getIsvCorpClient($corpId)->patch('/customers/' . $id, $data);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data']['id'];
        }
        throw new ApiException($response->getResponseMessage());
    }


    /**
     * @param $id
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function destroy($id, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->delete('/customers/' . $id);
        if ($response->isResponseSuccess()) {
            return $id;
        }
        throw new ApiException($response->getResponseMessage());
    }
}