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
     * @param string $indexType
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function all($indexType = 'index', $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->get('/product/corp/categories', [
            'indexType' => $indexType
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

        $response = $this->getIsvCorpClient($corpId)->post('/product/corp/categories', $data);
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

        $response = $this->getIsvCorpClient($corpId)->patch('/product/corp/categories/' . $id, $data);
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

        $response = $this->getIsvCorpClient($corpId)->delete('/product/corp/categories/' . $id);
        if ($response->isResponseSuccess()) {
            return $id;
        }
        throw new ApiException($response->getResponseMessage());
    }

}