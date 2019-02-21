<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-02-21
 * Time: 14:31
 */

namespace XinXiHua\SDK\Services;

use XinXiHua\SDK\Exceptions\ApiException;

class ProductService extends BaseService
{
    /**
     * @param array $include
     * @return mixed
     * @throws ApiException
     */
    public function all($include = ['logo'])
    {
        $response = $this->client->get('/products', [
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
    public function paginate($page = 1, $limit = 20, $include = ['logo'])
    {
        $response = $this->client->get('/products', [
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
     * @param $data
     * @return mixed
     * @throws ApiException
     */
    public function store($data)
    {

        $response = $this->client->post('/products', $data);
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

        $response = $this->client->patch('/products/' . $id, $data);
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

        $response = $this->client->delete('/products/' . $id);
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
        $response = $this->client->post('/products/batch', [
            'delete' => $ids
        ]);
        if ($response->isResponseSuccess()) {
            return true;
        }

        throw new ApiException($response->getResponseMessage());
    }
}