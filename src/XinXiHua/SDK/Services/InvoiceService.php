<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-04-10
 * Time: 11:14
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\Exceptions\ApiException;

class InvoiceService extends BaseService
{

    /**
     * @param $orderId
     * @param $data
     * @param int $attention
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function store($orderId, $data, $attention = 0, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/orders/' . $orderId . '/invoice', $data);
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
        $response = $this->accessToken->getIsvCorpClient($corpId)->delete('/invoices/' . $id);
        if ($response->isResponseSuccess()) {
            return $id;
        }
        throw new ApiException($response->getResponseMessage());
    }
}