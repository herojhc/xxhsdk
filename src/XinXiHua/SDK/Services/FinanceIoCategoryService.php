<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2020-05-07
 * Time: 19:11
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\Exceptions\ApiException;

class FinanceIoCategoryService extends BaseService
{

    /**
     * @param array $criteria
     * @param array $include
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function all($criteria = [], $include = [], $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->get('/finance/io/categories', array_merge([
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
    public function paginate($page = 1, $limit = 20, $criteria = [], $include = [], $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->get('/finance/io/categories', array_merge([
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
    public function show($id, $include = [], $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->get('/finance/io/categories' . $id, [
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
        $response = $this->getIsvCorpClient($corpId)->post('/finance/io/categories', $data);
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
        $response = $this->getIsvCorpClient($corpId)->patch('/finance/io/categories' . $id, $data);
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
        $response = $this->getIsvCorpClient($corpId)->delete('/finance/io/categories' . $id);
        if ($response->isResponseSuccess()) {
            return $id;
        }
        throw new ApiException($response->getResponseMessage());
    }
}