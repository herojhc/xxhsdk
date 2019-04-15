<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-03-25
 * Time: 18:15
 */

namespace XinXiHua\SDK\Services;

use XinXiHua\SDK\Exceptions\ApiException;

class OrderService extends BaseService
{

    /**
     * @param $data
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function store($data, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/orders', $data);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data']['id'];
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
        $response = $this->accessToken->getIsvCorpClient($corpId)->patch('/orders/' . $id, $data);
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
        $response = $this->accessToken->getIsvCorpClient($corpId)->delete('/orders/' . $id);
        if ($response->isResponseSuccess()) {
            return $id;
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $id
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function closed($id, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/orders/' . $id . '/closed');
        if ($response->isResponseSuccess()) {
            return $id;
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $id
     * @param $deliverInfo
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function deliver($id, $deliverInfo = [], $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/orders/' . $id . '/deliver', $deliverInfo);
        if ($response->isResponseSuccess()) {
            return $id;
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $id
     * @param $address
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function address($id, $address, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/orders/' . $id . '/address', $address);
        if ($response->isResponseSuccess()) {
            return $id;
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $id
     * @param string $tradeType
     * @param array $payInfo
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function pay($id, $tradeType = 'MOBILE', $payInfo = [], $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/orders/' . $id . '/pay?tradeType=' . $tradeType, $payInfo);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseMessage());
    }
}