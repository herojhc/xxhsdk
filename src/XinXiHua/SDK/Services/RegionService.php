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

    /**
     * @return array
     */
    public function getProvinces()
    {
        $response = $this->client->get('provinces');
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        return [];
    }

    /**
     * @param $provinceId
     * @return array
     */
    public function getCities($provinceId)
    {
        $response = $this->client->get('provinces/' . $provinceId . '/cities');
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        return [];
    }

    /**
     * @param $cityId
     * @return array
     */
    public function getAreas($cityId)
    {
        $response = $this->client->get('cities/' . $cityId . '/areas');
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        return [];
    }


}