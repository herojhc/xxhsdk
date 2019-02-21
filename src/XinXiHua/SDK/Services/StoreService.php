<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-02-02
 * Time: 10:13
 */

namespace XinXiHua\SDK\Services;

use XinXiHua\SDK\Exceptions\ApiException;

class StoreService extends BaseService
{
    /**
     * @param $data
     * @return mixed
     * @throws ApiException
     */
    public function store($data)
    {
        $response = $this->client->post('/stores', $data);
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

        $response = $this->client->patch('/stores/' . $id, $data);
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
        $response = $this->client->delete('/stores/' . $id);
        if ($response->isResponseSuccess()) {
            return $id;
        }
        throw new ApiException($response->getResponseMessage());
    }
}