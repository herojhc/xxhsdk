<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-09-12
 * Time: 9:42
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\Exceptions\ApiException;

class ConfigService extends BaseService
{
    /**
     * @param $type
     * @return mixed
     * @throws ApiException
     */
    public function getConfigs($type)
    {
        $response = $this->getIsvCorpClient()->get('/configs/' . config('app.name') . '_' . $type . '/type');
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }

        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $type
     * @param array $configs
     * @return bool
     * @throws ApiException
     */
    public function setConfigs($type, array $configs)
    {
        $response = $this->getIsvCorpClient()->post('/configs/' . config('app.name') . '_' . $type . '/type', $configs);
        if ($response->isResponseSuccess()) {
            return true;
        }

        throw new ApiException($response->getResponseMessage());
    }

    public function getSysConfigs()
    {
        $response = $this->getIsvCorpClient()->get('/configs/system');
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        return [];
    }
}