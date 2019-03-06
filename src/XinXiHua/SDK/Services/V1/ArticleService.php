<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-02-22
 * Time: 15:44
 */

namespace XinXiHua\SDK\Services\V1;


use XinXiHua\SDK\Exceptions\ApiException;
use XinXiHua\SDK\Services\BaseService;

class ArticleService extends BaseService
{

    /**
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function all($corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->get('/v1/articles');
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseMessage());

    }

    /**
     * @param int $page
     * @param int $limit
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function paginate($page = 1, $limit = 20, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->get('/v1/articles', [
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
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function show($id, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->get('/v1/articles/' . $id);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }

        throw new ApiException($response->getResponseMessage());
    }
}