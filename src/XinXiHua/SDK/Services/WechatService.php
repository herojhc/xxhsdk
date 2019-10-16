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
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function qrcodeForTemporary($sceneStr, $expireSeconds, $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->post('/wechat/qrcodes/temporary', [
            'scene_str' => $sceneStr,
            'expire_seconds' => $expireSeconds
        ]);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $toId
     * @param array $content
     * @param string $msgType
     * @param int $kfId
     * @param null $corpId
     * @return bool
     * @throws ApiException
     */
    public function sendCustomMessage($toId, array $content, $msgType = 'text', $kfId = 0, $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->post('/wechat/custom/message', [
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

    /**
     * @param $toId
     * @param array $data
     * @param $templateId
     * @param null $url
     * @param null $corpId
     * @return bool
     * @throws ApiException
     */
    public function sendTemplateMessage($toId, array $data, $templateId, $url = null, $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->post('/wechat/template/message', [
            'to_id' => $toId,
            'data' => $data,
            'url' => $url,
            'template_id' => $templateId
        ]);
        if ($response->isResponseSuccess()) {
            return true;
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param null $corpId
     * @return bool
     */
    public function getJsApiTicket($corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->get('/wechat/jsapi/ticket');
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        return false;
    }

    public function getSignPackage($url, $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->get('/wechat/jsapi/sign-package', [
            'url' => $url
        ]);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        return false;
    }
}