<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-06-23
 * Time: 8:43
 */

namespace XinXiHua\SDK\Services\V2;

use XinXiHua\SDK\Exceptions\ApiException;
use XinXiHua\SDK\Services\BaseService;

class TeamService extends BaseService
{

    /**
     * @param $data
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function store($data, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/v2/teams', $data);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data']['id'];
        }
        throw new ApiException($response->getResponseMessage());

    }

    /**
     * @param $data
     * @param $id
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function update($data, $id, $corpId = null)
    {

        $response = $this->accessToken->getIsvCorpClient($corpId)->patch('/v2/teams/' . $id, $data);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data']['id'];
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $id
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function destroy($id, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->delete('/v2/teams/' . $id);
        if ($response->isResponseSuccess()) {
            return $id;
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $id
     * @param array $contactIds
     * @param null $corpId
     * @return bool
     * @throws ApiException
     */
    public function join($id, array $contactIds, $corpId = null)
    {

        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/v2/teams/' . $id . '/contacts', [
            'contact_ids' => $contactIds
        ]);
        if ($response->isResponseSuccess()) {
            return true;
        }

        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $id
     * @param $contactId
     * @param null $corpId
     * @return bool
     * @throws ApiException
     */
    public function leave($id, $contactId, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->delete('/v2/teams/' . $id . '/contacts/' . $contactId);
        if ($response->isResponseSuccess()) {
            return true;
        }

        throw new ApiException($response->getResponseMessage());
    }
}