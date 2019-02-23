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
     * @param $kfId
     * @param $toId
     * @param array $content
     * @param string $msgType
     * @return bool
     * @throws ApiException
     */
    public function sendCustomMessage($toId, array $content, $msgType = 'text', $kfId = 0)
    {
        $response = $this->client->post('/wechat/custom/message', [
            'kf_id' => $kfId,
            'to_id' => $toId,
            'content' => $content,
            'msg_type' => $msgType
        ]);
        if ($response->isResponseSuccess()) {
            return true;
        }
        throw new ApiException($response->getResponseMessage());
    }
}