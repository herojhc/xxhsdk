<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-02-21
 * Time: 19:24
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\Exceptions\ApiException;

class TagService extends BaseService
{

    /**
     * @param $data
     * @return mixed
     * @throws ApiException
     */
    public function store($data)
    {
        $response = $this->client->post('/tags', $data);
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

        $response = $this->client->patch('/tags/' . $id, $data);
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
        $response = $this->client->delete('/tags/' . $id);
        if ($response->isResponseSuccess()) {
            return $id;
        }
        throw new ApiException($response->getResponseMessage());
    }
}