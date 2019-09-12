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
     * @param null $app
     * @return mixed
     * @throws ApiException
     */
    public function getConfigs($type, $app = null)
    {
        if (empty($app)) {
            $app = config('app.name');
        }

        $response = $this->getIsvCorpClient()->get('/configs/' . $app . '_' . $type . '/type');
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }

        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $type
     * @param array $configs
     * @param null $app
     * @return bool
     * @throws ApiException
     */
    public function setConfigs($type, array $configs, $app = null)
    {
        if (empty($app)) {
            $app = config('app.name');
        }

        $response = $this->getIsvCorpClient()->post('/configs/' . $app . '_' . $type . '/type', $configs);
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