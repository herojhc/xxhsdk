<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2020-08-01
 * Time: 17:31
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\Exceptions\ApiException;

class PayableService extends BaseService
{
    /**
     * @param int $page
     * @param int $limit
     * @param array $criteria
     * @param array $include
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function paginate($page = 1, $limit = 20, $criteria = [], $include = ['customer', 'category'], $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->get('/payables', array_merge([
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
    public function show($id, $include = ['customer', 'category'], $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->get('/payables/' . $id, [
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

        $response = $this->getIsvCorpClient($corpId)->post('/payables', $data);
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

        $response = $this->getIsvCorpClient($corpId)->patch('/payables/' . $id, $data);
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

        $response = $this->getIsvCorpClient($corpId)->delete('/payables/' . $id);
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
        $response = $this->getIsvCorpClient($corpId)->post('/payables/batch', [
            'delete' => $ids
        ]);
        if ($response->isResponseSuccess()) {
            return true;
        }

        throw new ApiException($response->getResponseMessage());
    }
}