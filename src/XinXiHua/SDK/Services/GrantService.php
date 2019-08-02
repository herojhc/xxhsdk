<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2019-08-02
 * Time: 10:16
 */

namespace XinXiHua\SDK\Services;


class GrantService extends BaseService
{

    /**
     * @param $userId
     * @param $agentId
     * @param string $scope
     * @param string $authorizeModule
     * @param null $corpId
     * @return bool
     */
    public function auth($userId, $agentId, $scope = 'authorize_contact', $authorizeModule = 'authorize_front', $corpId = null)
    {
        $response = $this->getIsvCorpClient($corpId)->post('/grant/auth', [
            'user_id' => $userId,
            'agent_id' => $agentId,
            'scope' => $scope,
            'authorize_module' => $authorizeModule
        ]);
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }

        return false;
    }
}