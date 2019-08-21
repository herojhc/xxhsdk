<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-04-04
 * Time: 12:09
 */

namespace XinXiHua\SDK\Services;


use XinXiHua\SDK\Exceptions\ApiException;

class PartnerService extends BaseService
{

    /**
     * @param array $criteria
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function all($criteria = [], $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->get('/partners', $criteria);
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
        $response = $this->getIsvCorpClient($corpId)->get('/partners', array_merge([
            'page' => $page,
            'limit' => $limit
        ], $criteria));
        if ($response->isResponseSuccess()) {
            return $response->getResponseData();
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $partner
     * @param string $type
     * @param null $corpId
     * @return mixed
     * @throws ApiException
     */
    public function sendVerifyCode($partner, $type = 'code', $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->post('/partners/verify-code', [
            'partner' => $partner,
            'type' => $type
        ]);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseMessage());

    }

    /**
     * @param $action
     * @param $partnerId
     * @param $data
     * @param null $corpId
     * @return bool
     * @throws ApiException
     */
    public function sign($action, $partnerId, $data = [], $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->post('/partners/' . $partnerId . '/sign?action=' . $action, $data);
        if ($response->isResponseSuccess()) {
            return true;
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $partnerId
     * @param null $corpId
     * @return bool
     * @throws ApiException
     */
    public function release($partnerId, $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->post('/partners/' . $partnerId . '/release');
        if ($response->isResponseSuccess()) {
            return true;
        }
        throw new ApiException($response->getResponseMessage());
    }

    /**
     * @param $ids
     * @param null $corpId
     * @return bool
     * @throws ApiException
     */
    public function batchCheck($ids, $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->post('/partners/batch', [
            'check' => $ids
        ]);
        if ($response->isResponseSuccess()) {
            return true;
        }
        throw new ApiException($response->getResponseMessage());
    }
}