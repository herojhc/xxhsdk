<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-06-11
 * Time: 10:26
 */

namespace XinXiHua\SDK\Services;

use XinXiHua\SDK\Exceptions\ApiException;

class DepartmentService extends BaseService
{

    /**
     * @param array $include
     * @return mixed
     * @throws ApiException
     */
    public function all($include = [])
    {
        $response = $this->client->get('/departments', [
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
        $response = $this->client->get('/departments', [
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
        $response = $this->client->get('/departments/' . $id, [
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
        $response = $this->client->post('/departments', $data);
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

        $response = $this->client->patch('/departments/' . $id, $data);
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
        $response = $this->client->delete('/departments/' . $id);
        if ($response->isResponseSuccess()) {
            return $id;
        }
        throw new ApiException($response->getResponseMessage());
    }

}