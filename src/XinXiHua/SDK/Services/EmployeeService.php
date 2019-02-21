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
     * @return mixed
     * @throws ApiException
     */
    public function all($include = [])
    {
        $response = $this->client->get('/employees', [
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
     * @return mixed
     * @throws ApiException
     */
    public function paginate($page = 1, $limit = 20, $include = [])
    {
        $response = $this->client->get('/employees', [
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
     * @return mixed
     * @throws ApiException
     */
    public function show($id, $include = [])
    {
        $response = $this->client->get('/employees/' . $id, [
            'include' => implode(',', $include)
        ]);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }

        throw new ApiException($response->getResponseMessage());
    }


    /**
     * @param $data
     * @return mixed
     * @throws ApiException
     */
    public function store($data)
    {
        $response = $this->client->post('/employees', $data);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data']['id'];
        }
        throw new ApiException($response->getResponseMessage());

    }

    /**
     * @param $data
     * @param $id
     * @return mixed
     * @throws ApiException
     */
    public function update($data, $id)
    {
        $response = $this->client->patch('/employees/' . $id, $data);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data']['id'];
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $id
     * @return mixed
     * @throws ApiException
     */
    public function destroy($id)
    {

        $response = $this->client->delete('/employees/' . $id);
        if ($response->isResponseSuccess()) {
            return $id;
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param array $ids
     * @return bool
     * @throws ApiException
     */
    public function batchDestroy(array $ids)
    {
        $response = $this->client->post('/employees/batch', [
            'delete' => $ids
        ]);
        if ($response->isResponseSuccess()) {
            return true;
        }

        throw new ApiException($response->getResponseMessage());
    }
}