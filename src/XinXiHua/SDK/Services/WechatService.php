<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-12-29
 * Time: 13:10
 */

namespace XinXiHua\SDK\Services;

use XinXiHua\SDK\Exceptions\ApiException;

class WechatService extends BaseService
{

    /**
     * @param $sceneStr
     * @param $expireSeconds
     * @return mixed
     * @throws ApiException
     */
    public function qrcodeForTemporary($sceneStr, $expireSeconds)
    {
        $response = $this->client->post('/wechat/qrcodes/temporary', [
            'scene_str' => $sceneStr,
            'expire_seconds' => $expireSeconds
        ]);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $data
     * @param string $sendType
     * @return bool
     * @throws ApiException
     */
    public function sendCustomMessage($data, $sendType = 'default')
    {
        $response = $this->client->post('/wechat/custom/message?sendType=' . $sendType, $data);
        if ($response->isResponseSuccess()) {
            return true;
        }
        throw new ApiException($response->getResponseMessage());
    }
}