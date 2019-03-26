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
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function all($include = ['logo'], $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->get('/products', [
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
    public function paginate($page = 1, $limit = 20, $include = ['logo'], $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->get('/products', [
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
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function store($data, $corpId = null)
    {

        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/products', $data);
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

        $response = $this->accessToken->getIsvCorpClient($corpId)->patch('/products/' . $id, $data);
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

        $response = $this->accessToken->getIsvCorpClient($corpId)->delete('/products/' . $id);
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
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/products/batch', [
            'delete' => $ids
        ]);
        if ($response->isResponseSuccess()) {
            return true;
        }

        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param int $id
     * @param array $platform
     * @param null $corpId
     * @return bool
     * @throws ApiException
     */
    public function online(int $id, array $platform = [], $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/products/' . $id . '/online', [
            'platform' => $platform
        ]);
        if ($response->isResponseSuccess()) {
            return true;
        }

        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param int $id
     * @param int $platformId
     * @param null $corpId
     * @return bool
     * @throws ApiException
     */
    public function offline(int $id, $platformId = 0, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/products/' . $id . '/offline', [
            'platform_corp_id' => $platformId
        ]);
        if ($response->isResponseSuccess()) {
            return true;
        }

        throw new ApiException($response->getResponseMessage());
    }
}