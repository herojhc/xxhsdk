<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-07-06
 * Time: 10:51
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\Exceptions\ApiException;

class ProductCorpCategoryService extends BaseService
{

    /**
     * @param $data
     * @return mixed
     * @throws ApiException
     */
    public function store($data)
    {

        $response = $this->client->post('/product/corp/categories', $data);
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

        $response = $this->client->patch('/product/corp/categories/' . $id, $data);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data']['id'];
        }

        throw new ApiException($response->getResponseMessage());

    }

    /**
     * @param $id
     * @return bool
     * @throws ApiException
     */
    public function destroy($id)
    {

        $response = $this->client->delete('/product/corp/categories/' . $id);
        if ($response->isResponseSuccess()) {
            return $id;
        }
        throw new ApiException($response->getResponseMessage());
    }

}