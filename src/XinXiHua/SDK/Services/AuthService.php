<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-07-24
 * Time: 0:12
 */

namespace XinXiHua\SDK\Services;

use Illuminate\Support\Facades\Log;
use XinXiHua\SDK\Models\Contact;
use XinXiHua\SDK\Models\Corporation;
use XinXiHua\SDK\Models\CorporationPermanentCode as Install;
use XinXiHua\SDK\Models\User;
use XinXiHua\SDK\Services\Traits\RestClient;

class AuthService
{

    use RestClient;

    /**
     * @param $authCorpId
     * @return bool|null
     */
    public function setAuthInfo($authCorpId)
    {
        $isvClient = $this->getIsvClient();

        // 获取永久授权码
        $install = Install::query()->where([
            ['corp_id', $authCorpId],
            ['agent_id', config('xxh-sdk.agent.agent_id')]
        ])->first();
        if ($install) {
            $response = $isvClient->get(config('xxh-sdk.agent.corp_info'), [
                'corp_id' => $authCorpId,
                'permanent_code' => $install->permanent_code
            ]);
            Log::debug($response->getResponse());
            if ($response->isResponseSuccess()) {
                $result = $response->getResponseData();
                if (isset($result['data'])) {
                    Corporation::query()->updateOrCreate([
                        'corp_id' => $result['data']['corp_id']
                    ], $result['data']);

                    return true;
                }
            }
        }
        return null;
    }


    /**
     * @return User|null
     */
    public function setUserInfo()
    {

        $contact = [];
        $user = [];
        $roles = [];
        // 获取参数数量
        $args = func_get_args();
        $numArgs = func_num_args();
        if ($numArgs == 2) {
            $authCorpId = $args[0];
            $authCode = $args[1];
            $isvCorpClient = $this->getIsvCorpClient($authCorpId);
            $response = $isvCorpClient->get(config('xxh-sdk.agent.corp_user_api'),
                [
                    'auth_code' => $authCode
                ]
            );
            Log::debug($response->getResponse());
            if ($response->isResponseSuccess()) {

                $result = $response->getResponseData();
                // 获取用户信息
                if (isset($result['data'])) {
                    $contact = $result['data'];
                    $user = $result['data']['user'];
                    $roles = $result['data']['roles'] ?? [];
                }
            }

        } else if ($numArgs == 1) {
            $result = $args[0];
            $contact = $result;
            $user = $result['user'];
            $roles = $result['roles'] ?? [];
        }

        if (!empty($user) && !empty($contact)) {
            Contact::query()->updateOrCreate([
                'contact_id' => $contact['contact_id']
            ], $contact);


            User::query()->updateOrCreate([
                'user_id' => $user['user_id']
            ], $user);

            // 角色
            \DB::table('contact_roles')->where([
                ['contact_id', $contact['contact_id']],
                ['corp_id', $contact['corp_id']]
            ])->delete();
            if (is_array($roles) && count($roles) > 0) {
                $insertOfRoles = [];
                foreach ($roles as $role) {
                    $insertOfRoles[] = [
                        'contact_id' => $contact['contact_id'],
                        'role_id' => $role['role_id'],
                        'corp_id' => $contact['corp_id']
                    ];
                }
                \DB::table('contact_roles')->insert($insertOfRoles);
            }

            return $user['user_id'];
        }

        return null;

    }

    public function getOauthUser($corpId, $authCode)
    {
        $isvCorpClient = $this->getIsvCorpClient($corpId);
        $response = $isvCorpClient->get(config('xxh-sdk.agent.corp_oauth_user_api'),
            [
                'auth_code' => $authCode
            ]
        );
        Log::debug($response->getResponse());
        if ($response->isResponseSuccess()) {
            $result = $response->getResponseData();
            return $result['data'] ?? [];
        }
        return [];
    }
}