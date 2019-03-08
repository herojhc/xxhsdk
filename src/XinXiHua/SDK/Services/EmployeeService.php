<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-06-11
 * Time: 10:45
 */

namespace XinXiHua\SDK\Services;

use XinXiHua\SDK\Exceptions\ApiException;

class EmployeeService extends BaseService
{

    /**
     * @param array $include
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function all($include = [], $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->get('/employees', [
            'include' => implode(',', $include)
        ]);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseMessage());

    }

    /**
     * @param int $page
     * @param int $limit
     * @param array $include
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function paginate($page = 1, $limit = 20, $include = [], $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->get('/employees', [
            'page' => $page,
            'limit' => $limit,
            'include' => implode(',', $include)
        ]);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
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
        $response = $this->accessToken->getIsvCorpClient($corpId)->get('/employees/' . $id, [
            'include' => implode(',', $include)
        ]);
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
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/employees', $data);
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
        $response = $this->accessToken->getIsvCorpClient($corpId)->patch('/employees/' . $id, $data);
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

        $response = $this->accessToken->getIsvCorpClient($corpId)->delete('/employees/' . $id);
        if ($response->isResponseSuccess()) {
            return $id;
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param array $ids
     * @param null $corpId
     * @return bool
     * @throws ApiException
     */
    public function batchDestroy(array $ids, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/employees/batch', [
            'delete' => $ids
        ]);
        if ($response->isResponseSuccess()) {
            return true;
        }

        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $id
     * @param $userId
     * @param null $corpId
     * @return bool
     * @throws ApiException
     */
    public function active($id, $userId, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/employees/active', [
            'id' => $id,
            'user_id' => $userId
        ]);
        if ($response->isResponseSuccess()) {
            return true;
        }

        throw new ApiException($response->getResponseMessage());
    }
}