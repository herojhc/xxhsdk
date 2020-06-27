<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2020-06-27
 * Time: 14:54
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\Exceptions\ApiException;

class SmsService extends BaseService
{
    /**
     * @param $mobile
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function sendVerifyCode($mobile, $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->post('/sms/verify-code/send', [
            'to' => $mobile
        ]);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData();
        }

        throw new ApiException('发送失败');
    }

    public function validateVerifyCode($token, $mobile, $code, $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->post('/sms/verify-code/validate', [
            'token' => $token,
            'to' => $mobile,
            'verify_code' => $code
        ]);
        if ($response->isResponseSuccess()) {
            return true;
        }
        return false;
    }

    public function sendByTemplate($to, $templateId, $templateData, $sender, $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->post('/sms/send', [
            'to' => $to,
            'template_id' => $templateId,
            'template_data' => $templateData,
            'sender' => $sender
        ]);
        if ($response->isResponseSuccess()) {
            return true;
        }
        return false;
    }
}