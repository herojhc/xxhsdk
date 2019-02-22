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
     * @return mixed
     * @throws ApiException
     */
    public function all()
    {
        $response = $this->client->get('/articles');
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseMessage());

    }

    /**
     * @param int $page
     * @param int $limit
     * @return mixed
     * @throws ApiException
     */
    public function paginate($page = 1, $limit = 20)
    {
        $response = $this->client->get('/articles', [
            'page' => $page,
            'limit' => $limit
        ]);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $id
     * @return mixed
     * @throws ApiException
     */
    public function show($id)
    {
        $response = $this->client->get('/articles/' . $id);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }

        throw new ApiException($response->getResponseMessage());
    }
}