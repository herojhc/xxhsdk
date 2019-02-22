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
     * @return mixed
     * @throws ApiException
     */
    public function all()
    {
        $response = $this->client->get('/contacts');
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseMessage());

    }

    /**
     * @param int $page
     * @param int $limit
     * @return mixed
     * @throws ApiException
     */
    public function paginate($page = 1, $limit = 20)
    {
        $response = $this->client->get('/contacts', [
            'page' => $page,
            'limit' => $limit
        ]);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $id
     * @return mixed
     * @throws ApiException
     */
    public function show($id)
    {
        $response = $this->client->get('/contacts/' . $id);
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
        $response = $this->client->post('/contacts', $data);
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
        $response = $this->client->patch('/contacts/' . $id, $data);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data']['id'];
        }

        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $id
     * @param int $attention
     * @return mixed
     * @throws ApiException
     */
    public function invite($id, $attention = 0)
    {
        $response = $this->client->get('/contacts/' . $id . '/invite', [
            'attention' => $attention
        ]);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }

        throw new ApiException($response->getResponseMessage());
    }

}