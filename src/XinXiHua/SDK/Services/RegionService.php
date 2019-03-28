<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2017-11-18
 * Time: 9:34
 */

namespace XinXiHua\SDK\Services;


class RegionService extends BaseService
{

    public function all($columns = ['*'], $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->get('/regions', [
            'columns' => $columns
        ]);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        return [];
    }

    /**
     * @param null $corpId
     * @return array
     */
    public function getProvinces($corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->get('/provinces');
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        return [];
    }

    /**
     * @param $provinceId
     * @param null $corpId
     * @return array
     */
    public function getCities($provinceId, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->get('/provinces/' . $provinceId . '/cities');
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        return [];
    }

    /**
     * @param $cityId
     * @param null $corpId
     * @return array
     */
    public function getAreas($cityId, $corpId = null)
    {
        $response = $this->accessToken->getIsvCorpClient($corpId)->get('/cities/' . $cityId . '/areas');
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        return [];
    }


}