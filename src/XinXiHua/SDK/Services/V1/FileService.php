<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-12-28
 * Time: 11:53
 */

namespace XinXiHua\SDK\Services\V1;

use Illuminate\Http\UploadedFile;
use XinXiHua\SDK\Events\Uploaded;
use XinXiHua\SDK\Exceptions\ApiException;
use XinXiHua\SDK\Models\Attachment;
use XinXiHua\SDK\Services\BaseService;

class FileService extends BaseService
{

    /**
     * @param $file
     * @param $type
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function upload(UploadedFile $file, $type, $corpId = null)
    {

        // 调用存储API
        $url = 'v1/files';
        if (!empty($type)) {
            $url .= '?type=' . $type;
        }

        $response = $this->accessToken->getIsvCorpClient($corpId)->postMultipartSimple($url, [
            'name' => $file->getClientOriginalName(),
            'attachment' => fopen($file, 'r')// 这里无需关闭，STEAM 自动关闭
        ]);

        if ($response->isResponseSuccess()) {

            $file = $response->getResponseData()['data'];
            // 这里入库，返回本地信息
            $attachment = new Attachment();
            $attachment->forceFill([
                'platform_attachment_id' => $file['platform_attachment_id'],
                'filename' => $file['filename'],
                'original_name' => $file['original_name'],
                'real_path' => $file['real_path'],
                'url' => $file['url'],
                'mime' => $file['mime'] ?? '',
                'size' => $file['size'] ?? 0,
                'md5' => $file['md5'] ?? '',
                'sha1' => $file['sha1'] ?? '',
                'is_image' => $file['is_image'] ?? 0
            ])->save();
            // 触发上传后事件
            event(new Uploaded($attachment));
            return $attachment->toArray();
        }

        throw new ApiException($response->getResponseData()['message']);
    }
}