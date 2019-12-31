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
     * @param array $include
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function all($criteria = [], $include = [], $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->get('/contacts', array_merge([
            'include' => implode(',', $include)
        ], $criteria));
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseMessage());

    }

    /**
     * @param int $page
     * @param int $limit
     * @param array $criteria
     * @param array $include
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function paginate($page = 1, $limit = 20, $criteria = [], $include = [], $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->get('/contacts', array_merge([
            'page' => $page,
            'limit' => $limit,
            'include' => implode(',', $include)
        ], $criteria));
        if ($response->isResponseSuccess()) {
            return $response->getResponseData();
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $id
     * @param array $include
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function show($id, $include = [], $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->get('/contacts/' . $id, [
            'include' => implode(',', $include)
        ]);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }

        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $data
     * @param string $storeType
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function store($data, $storeType = 'store', $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->post('/contacts?storeType=' . $storeType, $data);
        if ($response->isResponseSuccess()) {
            if ($storeType == 'batch') {
                return $response->getResponseData()['data']['count'];
            }
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
        $response = $this->getIsvCorpClient($corpId)->patch('/contacts/' . $id, $data);
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
        $response = $this->getIsvCorpClient($corpId)->delete('/contacts/' . $id);
        if ($response->isResponseSuccess()) {
            return $id;
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
        $response = $this->getIsvCorpClient($corpId)->get('/contacts/' . $id . '/invite', [
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
        $response = $this->getIsvCorpClient($corpId)->post('/contacts/supervision', [
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
        $response = $this->getIsvCorpClient($corpId)->get('/contacts/supervision');
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }

        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $userId
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function follow($userId, $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->post('/contacts' . $userId . '/user');
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data']['id'];
        }

        throw new ApiException($response->getResponseMessage());
    }


    /**
     * @param $userId
     * @return mixed
     * @throws ApiException
     */
    public function getByUserId($userId)
    {
        $response = $this->getIsvCorpClient()->get('/contacts/' . $userId . '/user');
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }

        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $ids
     * @param $roleIds
     * @return mixed
     * @throws ApiException
     */
    public function authorize(array $ids, array $roleIds)
    {
        $response = $this->getIsvCorpClient()->post('/contacts/authorize', [
            'ids' => $ids,
            'role_ids' => $roleIds
        ]);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData();
        }

        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function total($corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->get('/contacts/total');
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }

        throw new ApiException($response->getResponseMessage());
    }

}