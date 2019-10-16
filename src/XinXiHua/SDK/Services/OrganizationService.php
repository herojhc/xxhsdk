<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-10-16
 * Time: 18:31
 */

namespace XinXiHua\SDK\Services;


class OrganizationService extends BaseService
{

    public function getBasicDetailsByName($keyword, $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->post('/organization/detail/basic', [
            'keyword' => $keyword
        ]);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        return [];
    }

    public function getFullDetailsByName($keyword, $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->post('/organization/detail/full', [
            'keyword' => $keyword
        ]);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        return [];
    }
}