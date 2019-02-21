<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-07-23
 * Time: 18:02
 */

namespace XinXiHua\SDK\Services;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use XinXiHua\SDK\Events\Installed;
use XinXiHua\SDK\Events\Uninstalled;
use XinXiHua\SDK\Models\Contact;
use XinXiHua\SDK\Models\Corporation;
use XinXiHua\SDK\Models\CorporationPermanentCode;
use XinXiHua\SDK\Models\User;

class IsvService
{
    public function install($eventMsg)
    {

        $corpId = $eventMsg->CorpId;
        $corpName = $eventMsg->CorpName;
        $permanentCode = $eventMsg->PermanentCode;
        $authUser = $eventMsg->AuthUser;

        try {
            Log::info('installingLog', [
                'log' =>
                    [
                        $corpId,
                        $permanentCode,
                        $authUser
                    ]
            ]);
        } catch (\Throwable $exception) {

        }

        try {
            //
            DB::beginTransaction();
            $authInfo = CorporationPermanentCode::query()->where([
                ['corp_id', $corpId],
                ['agent_id', config('xxh-sdk.agent.agent_id')]
            ])->first();

            if ($authInfo) {

                $authInfo->permanent_code = $permanentCode;
                $authInfo->saveOrFail();

                return 'success';
            }


            $arr['permanent_code'] = $permanentCode;

            $arr['corp_id'] = $corpId;
            $arr['agent_id'] = config('xxh-sdk.agent.agent_id');
            $arr['name'] = $corpName;

            if (CorporationPermanentCode::forceCreate($arr)) {

                // 添加一条记录到 corporations表
                Corporation::query()->updateOrCreate([
                    'corp_id' => $corpId
                ],
                    [
                        'name' => $corpName,
                        'status' => 1,
                        'user_id' => $authUser->user_id
                    ]);

                // 添加管理员记录
                User::query()->updateOrCreate([
                    'user_id' => $authUser->user_id
                ], [
                    'name' => $authUser->name,
                    'mobile' => $authUser->mobile,
                    'avatar' => $authUser->avatar,
                    'mobile_validated' => 1
                ]);

                // 添加联系人
                Contact::query()->updateOrCreate([
                    'contact_id' => $authUser->contact_id
                ], [
                    'code' => $authUser->code,
                    'name' => $authUser->name,
                    'mobile' => $authUser->mobile,
                    'avatar' => $authUser->avatar,
                    'corp_id' => $corpId,
                    'user_id' => $authUser->user_id,
                    'is_admin' => 1
                ]);


                $corp = Corporation::query()->find($corpId);
                $contact = Contact::query()->find($authUser->contact_id);
                $user = User::query()->find($authUser->user_id);

                event(new Installed($corp, $user, $contact));

                DB::commit();
                // 一定要返回success
                return 'success';

            }
        } catch (\Throwable $throwable) {
            Log::info($throwable->getMessage(), ['exception' => $throwable]);
            DB::rollBack();
        }

        return 'error';

    }

    public function uninstall($eventMsg)
    {

        // 这里最好是 软删除

        try {

            CorporationPermanentCode::query()->where([
                ['corp_id', $eventMsg->CorpId],
                ['agent_id', config('auth.agent.agent_id')]
            ])->delete();

            $corp = Corporation::query()->find($eventMsg->CorpId);
            event(new Uninstalled($corp));
            return 'success';

        } catch (\Throwable $throwable) {
            Log::info($throwable->getMessage(), ['exception' => $throwable]);

        }

        return 'error';
    }
}