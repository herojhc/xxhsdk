<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-07-24
 * Time: 11:51
 */

namespace XinXiHua\SDK\Services;

use XinXiHua\SDK\Exceptions\ApiException;

class ContactService extends BaseService
{


    /**
     * @param array $criteria
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function all($criteria = [], $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->get('/contacts', $criteria);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseMessage());

    }

    /**
     * @param int $page
     * @param int $limit
     * @param array $criteria
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function paginate($page = 1, $limit = 20, $criteria = [], $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->get('/contacts', array_merge([
            'page' => $page,
            'limit' => $limit
        ], $criteria));
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $id
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function show($id, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->get('/contacts/' . $id);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }

        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $data
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function store($data, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/contacts', $data);
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
        $response = $this->accessToken->getIsvCorpClient($corpId)->patch('/contacts/' . $id, $data);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data']['id'];
        }

        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $id
     * @param int $attention
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function invite($id, $attention = 0, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->get('/contacts/' . $id . '/invite', [
            'attention' => $attention
        ]);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }

        throw new ApiException($response->getResponseMessage());
    }


    /**
     * @param array $ids
     * @param bool $clear
     * @param null $corpId
     * @return bool
     * @throws ApiException
     */
    public function setSupervision(array $ids, bool $clear = false, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/contacts/supervision', [
            'ids' => $ids,
            'clear' => $clear
        ]);
        if ($response->isResponseSuccess()) {
            return true;
        }

        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function getSupervision($corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->get('/contacts/supervision');
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }

        throw new ApiException($response->getResponseMessage());
    }


}