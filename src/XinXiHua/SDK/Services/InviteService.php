<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-02-22
 * Time: 10:38
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\Exceptions\ApiException;

class InviteService extends BaseService
{

    /**
     *
     * /////////////////////
     *
     * 特殊，返回 TOKEN 等信息
     *
     * ////////////////////
     *
     */

    /**
     * @param $data
     * @param int $attention
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function store($data, $attention = 0, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/invites?attention=' . $attention, $data);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }

        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $id
     * @param $corpId
     * @return mixed
     * @throws ApiException
     */
    public function show($id, $corpId)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->get('/invites/' . $id);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }

        throw new ApiException($response->getResponseMessage());
    }
}