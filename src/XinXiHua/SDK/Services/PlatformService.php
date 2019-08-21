<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-04-04
 * Time: 12:09
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\Exceptions\ApiException;

class PlatformService extends BaseService
{

    /**
     * @param array $criteria
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function all($criteria = [], $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->get('/platforms', $criteria);
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
        $response = $this->getIsvCorpClient($corpId)->get('/platforms', array_merge([
            'page' => $page,
            'limit' => $limit
        ], $criteria));
        if ($response->isResponseSuccess()) {
            return $response->getResponseData();
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $platform
     * @param string $type
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function apply($platform, $type = 'code', $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->post('/platforms/apply', [
            'platform' => $platform,
            'type' => $type
        ]);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data']['id'];
        }
        throw new ApiException($response->getResponseMessage());

    }

    /**
     * @param $platformId
     * @param null $corpId
     * @return bool
     * @throws ApiException
     */
    public function release($platformId, $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->post('/platforms/' . $platformId . '/release');
        if ($response->isResponseSuccess()) {
            return true;
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $platformId
     * @param null $corpId
     * @return bool
     * @throws ApiException
     */
    public function destroy($platformId, $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->delete('/platforms/' . $platformId);
        if ($response->isResponseSuccess()) {
            return true;
        }
        throw new ApiException($response->getResponseMessage());
    }
}