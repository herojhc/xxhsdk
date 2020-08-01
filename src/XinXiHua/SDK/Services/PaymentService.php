<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2020-08-01
 * Time: 17:31
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\Exceptions\ApiException;

class PaymentService extends BaseService
{
    /**
     * @param $data
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function store($data, $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->post('/payments', $data);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data']['id'];
        }
        throw new ApiException($response->getResponseMessage());

    }

    /**
     * @param $id
     * @param array $data
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function pay($id, $data = [], $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->post('/payments/' . $id . '/pay', $data);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData();
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
        $response = $this->getIsvCorpClient($corpId)->delete('/payments/' . $id);
        if ($response->isResponseSuccess()) {
            return $id;
        }
        throw new ApiException($response->getResponseMessage());
    }
}