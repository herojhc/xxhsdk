<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-12-28
 * Time: 11:53
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\Events\FileUploaded;
use XinXiHua\SDK\Exceptions\ApiException;

class FileService extends BaseService
{

    public function upload($file, $type, $name = '')
    {

        // 调用存储API
        $url = 'files';
        if (!empty($type)) {
            $url .= '?type=' . $type;
        }

        $response = $this->client->postMultipartSimple($url, [
            'name' => $name,
            'attachment' => $file
        ]);

        if ($response->isResponseSuccess()) {
            event(new FileUploaded($response->getResponseData()['data']));
            return $response->getResponseData()['data'];
        }

        throw new ApiException($response->getResponseData()['message']);
    }
}