<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-12-28
 * Time: 11:53
 */

namespace XinXiHua\SDK\Services;

use Illuminate\Http\UploadedFile;
use XinXiHua\SDK\Events\Uploaded;
use XinXiHua\SDK\Exceptions\ApiException;
use XinXiHua\SDK\Models\Attachment;

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
        $url = 'files';
        if (!empty($type)) {
            $url .= '?type=' . $type;
        }

        $response = $this->accessToken->getIsvCorpClient($corpId)->postMultipartSimple($url, [
            'name' => $file->getClientOriginalName(),
            'attachment' => fopen($file, 'r')// 这里无需关闭，STREAM 自动关闭
        ]);

        if ($response->isResponseSuccess()) {

            $result = $response->getResponseData()['data'];
            // 这里入库，返回本地信息
            $attachment = new Attachment();
            $attachment->forceFill([
                'id' => $result['platform_attachment_id'],// 保持和平台ID一致
                'platform_attachment_id' => $result['platform_attachment_id'],
                'filename' => $result['filename'],
                'original_name' => $result['original_name'],
                'real_path' => $result['real_path'],
                'url' => $result['url'],
                'mime' => $result['mime'] ?? '',
                'size' => $file->getSize(),
                'md5' => $result['md5'] ?? '',
                'sha1' => $result['sha1'] ?? '',
                'is_image' => $result['is_image'] ?? 0,
                'corp_id' => $result['corp_id']
            ])->save();
            // 触发上传后事件
            event(new Uploaded($attachment));
            return $attachment->toArray();
        }

        throw new ApiException($response->getResponseData()['message']);
    }
}