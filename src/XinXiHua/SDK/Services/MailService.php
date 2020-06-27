<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2020-06-27
 * Time: 15:00
 */

namespace XinXiHua\SDK\Services;


class MailService extends BaseService
{

    public function sendErrorLog(\Exception $exception, $tos = [], $corpId = null)
    {
        try {
            $this->getIsvCorpClient($corpId)->post('mail/error-logs', [
                'tos' => array_merge(explode(',', config('xxh.error.mail_to')), $tos),
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString()
            ]);
        } catch (\Throwable $exception) {

        }

    }
}