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
     * @param array $criteria
     * @param array $include
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function all($criteria = [], $include = ['logo'], $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->get('/products', array_merge([
            'include' => implode(',', $include)
        ], $criteria));
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param int $page
     * @param int $limit
     * @param array $criteria
     * @param array $include
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function paginate($page = 1, $limit = 20, $criteria = [], $include = ['logo'], $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->get('/products', array_merge([
            'page' => $page,
            'limit' => $limit,
            'include' => implode(',', $include)
        ], $criteria));
        if ($response->isResponseSuccess()) {
            return $response->getResponseData();
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $id
     * @param array $include
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function show($id, $include = ['logo'], $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->get('/products/' . $id, [
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
     * @param array $ids
     * @param array $platform
     * @param null $corpId
     * @return bool
     * @throws ApiException
     */
    public function batchOnline(array $ids, array $platform = [], $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/products/batch', [
            'online' => $ids,
            'platform' => $platform
        ]);
        if ($response->isResponseSuccess()) {
            return true;
        }

        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param array $ids
     * @param int $platformId
     * @param null $corpId
     * @return bool
     * @throws ApiException
     */
    public function batchOffline(array $ids, int $platformId = 0, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/products/batch', [
            'offline' => $ids,
            'platform_id' => $platformId
        ]);
        if ($response->isResponseSuccess()) {
            return true;
        }

        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param array $ids
     * @param null $corpId
     * @return bool
     * @throws ApiException
     */
    public function batchCheck(array $ids, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/products/batch', [
            'check' => $ids
        ]);
        if ($response->isResponseSuccess()) {
            return true;
        }

        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param array $ids
     * @param null $corpId
     * @return bool
     * @throws ApiException
     */
    public function batchCancel(array $ids, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/products/batch', [
            'cancel' => $ids
        ]);
        if ($response->isResponseSuccess()) {
            return true;
        }

        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param array $ids
     * @param null $corpId
     * @return bool
     * @throws ApiException
     */
    public function batchRefuse(array $ids, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/products/batch', [
            'refuse' => $ids
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

    /**
     * @param array $ids
     * @param string $content
     * @param null $corpId
     * @return bool
     * @throws ApiException
     */
    public function content(array $ids, string $content, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->post('/products/content', [
            'ids' => $ids,
            'content' => $content
        ]);
        if ($response->isResponseSuccess()) {
            return true;
        }

        throw new ApiException($response->getResponseMessage());
    }
}