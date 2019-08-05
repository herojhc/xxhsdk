<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-08-05
 * Time: 16:45
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\Exceptions\ApiException;

class ProductPlatformCategoryService extends BaseService
{
    /**
     * @param string $indexType
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function all($indexType = 'index', $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->get('/product/platform/categories', [
            'indexType' => $indexType
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
        $response = $this->getIsvCorpClient($corpId)->get('/product/platform/categories/' . $id);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseMessage());
    }
}