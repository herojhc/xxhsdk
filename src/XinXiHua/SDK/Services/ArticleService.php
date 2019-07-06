<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-02-22
 * Time: 15:44
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\Exceptions\ApiException;

class ArticleService extends BaseService
{

    /**
     * @param array $criteria
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function all($criteria = [], $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->get('/articles', $criteria);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseMessage());

    }

    /**
     * @param int $page
     * @param int $limit
     * @param array $criteria
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function paginate($page = 1, $limit = 20, $criteria = [], $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->get('/articles', array_merge([
            'page' => $page,
            'limit' => $limit
        ], $criteria));
        if ($response->isResponseSuccess()) {
            return $response->getResponseData();
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $id
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function show($id, $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->get('/articles/' . $id);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }

        throw new ApiException($response->getResponseMessage());
    }
}