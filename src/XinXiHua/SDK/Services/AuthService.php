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
use XinXiHua\SDK\Models\CorporationPermanentCode;
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

        $permanent_code = CorporationPermanentCode::query()->where([
            ['corp_id', $authCorpId],
            ['agent_id', config('xxh-sdk.agent.agent_id')]
        ])->first();
        if ($permanent_code) {
            $response = $isvClient->get(config('xxh-sdk.agent.corp_info'), [
                'corp_id' => $authCorpId,
                'permanent_code' => $permanent_code->permanent_code
            ]);

            Log::info($response->getResponse());
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
        // 获取参数数量
        $args = func_get_args();

        $num_args = func_num_args();
        if ($num_args == 2) {
            $authCorpId = $args[0];
            $authCode = $args[1];


            $isvCorpClient = $this->getIsvCorpClient($authCorpId);

            $response = $isvCorpClient->get(config('xxh-sdk.agent.corp_user_api'),
                [
                    'auth_code' => $authCode
                ]
            );

            Log::info($response->getResponse());
            if ($response->isResponseSuccess()) {

                $result = $response->getResponseData();

                // 获取用户信息
                if (isset($result['data'])) {
                    $contact = $result['data'];
                    $user = $result['data']['user'];
                }
            }

        } else if ($num_args == 1) {
            $result = $args[0];
            $contact = $result;
            $user = $result['user'];
        }

        if (!empty($user) && !empty($contact)) {
            Contact::query()->updateOrCreate([
                'contact_id' => $contact['contact_id']
            ], $contact);


            User::query()->updateOrCreate([
                'user_id' => $user['user_id']
            ], $user);

            return $user['user_id'];
        }

        return null;

    }
}