<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-04-15
 * Time: 16:08
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\Exceptions\ApiException;

class ReceiptService extends BaseService
{

    /**
     * @param $data
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function store($data, $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->post('/receipts', $data);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data']['id'];
        }
        throw new ApiException($response->getResponseMessage());

    }

    /**
     * @param $id
     * @param array $data
     * @param string $tradeType
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function pay($id, $data = [], $tradeType = 'MOBILE', $corpId = null)
    {

        $data['trade_type'] = $tradeType;
        $response = $this->getIsvCorpClient($corpId)->post('/receipts/' . $id . '/pay', $data);
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
        $response = $this->getIsvCorpClient($corpId)->delete('/receipts/' . $id);
        if ($response->isResponseSuccess()) {
            return $id;
        }
        throw new ApiException($response->getResponseMessage());
    }
}