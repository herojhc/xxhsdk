<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-03-25
 * Time: 18:15
 */

namespace XinXiHua\SDK\Services;

use XinXiHua\SDK\Exceptions\ApiException;
use XinXiHua\SDK\Support\Crypto\XxhCrypt;
use XinXiHua\SDK\Support\Sign\MakeSign;

class OrderService extends BaseService
{

    /**
     * @param int $page
     * @param int $limit
     * @param array $criteria
     * @param array $include
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function paginate($page = 1, $limit = 20, $criteria = [], $include = [], $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->get('/orders', array_merge([
            'page' => $page,
            'limit' => $limit,
            'include' => implode(',', $include)
        ], $criteria));
        if ($response->isResponseSuccess()) {
            return $response->getResponseData();
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $id
     * @param array $include
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function show($id, $include = [], $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->get('/orders/' . $id, [
            'include' => implode(',', $include)
        ]);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }

        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $data
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function store($data, $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->post('/orders', $data);
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
        $response = $this->getIsvCorpClient($corpId)->patch('/orders/' . $id, $data);
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
        $response = $this->getIsvCorpClient($corpId)->delete('/orders/' . $id);
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
        $response = $this->getIsvCorpClient($corpId)->post('/orders/' . $id . '/closed');
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
        $response = $this->getIsvCorpClient($corpId)->post('/orders/' . $id . '/deliver', $deliverInfo);
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
        $response = $this->getIsvCorpClient($corpId)->post('/orders/' . $id . '/address', $address);
        if ($response->isResponseSuccess()) {
            return $id;
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $id
     * @param string $tradeType
     * @param array $data [money,receipted_by]
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function pay($id, $data = [], $tradeType = 'MOBILE', $corpId = null)
    {

        $timestamp = time();
        $nonce = md5(uniqid());
        // 加密和签名
        $data['order_id'] = $id;
        $data['trade_type'] = $tradeType;
        $data['timestamp'] = $timestamp;
        $data['nonce'] = $nonce;
        $data['agent_id'] = config('xxh-sdk.agent.agent_id');

        // 加密
        $makeSign = new MakeSign();
        $key = config('xxh-sdk.agent.encoding_key');
        $data['sign'] = $makeSign->sign($key . $timestamp, $data);

        $response = $this->getIsvCorpClient($corpId)->post('/order/pay', $data);
        if ($response->isResponseSuccess()) {
            $result = $response->getResponseData();
            // 验签
            $checkSign = $result['sign'];
            unset($result['sign']);
            if ($makeSign->check($checkSign, $key . $result['timestamp'], $result)) {
                return $result;
            }

            throw new ApiException('验签失败');

        }
        throw new ApiException($response->getResponseMessage());
    }
}