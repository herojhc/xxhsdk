<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-04-04
 * Time: 12:09
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\Exceptions\ApiException;

class PartnerService extends BaseService
{

    /**
     * @param $partner
     * @param string $type
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function sendVerifyCode($partner, $type = 'code', $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/partners/' . $partner . '/verify-code?type=' . $type);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseMessage());

    }

    /**
     * @param $action
     * @param $partnerId
     * @param $data
     * @param null $corpId
     * @return bool
     * @throws ApiException
     */
    public function sign($action, $partnerId, $data = [], $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/partners/' . $partnerId . '/sign?action=' . $action, $data);
        if ($response->isResponseSuccess()) {
            return true;
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $partnerId
     * @param null $corpId
     * @return bool
     * @throws ApiException
     */
    public function release($partnerId, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/partners/' . $partnerId . '/release');
        if ($response->isResponseSuccess()) {
            return true;
        }
        throw new ApiException($response->getResponseMessage());
    }
}