<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-06-23
 * Time: 8:43
 */

namespace XinXiHua\SDK\Services;

use XinXiHua\SDK\Exceptions\ApiException;

class TeamService extends BaseService
{

    /**
     * @param $data
     * @return mixed
     * @throws ApiException
     */
    public function store($data)
    {
        $response = $this->client->post('/teams', $data);
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

        $response = $this->client->patch('/teams/' . $id, $data);
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
        $response = $this->client->delete('/teams/' . $id);
        if ($response->isResponseSuccess()) {
            return $id;
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $id
     * @param array $contactIds
     * @return bool
     * @throws ApiException
     */
    public function join($id, array $contactIds)
    {

        $response = $this->client->post('/teams/' . $id . '/contacts', [
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
     * @return bool
     * @throws ApiException
     */
    public function leave($id, $contactId)
    {
        $response = $this->client->delete('/teams/' . $id . '/contacts/' . $contactId);
        if ($response->isResponseSuccess()) {
            return true;
        }

        throw new ApiException($response->getResponseMessage());
    }
}