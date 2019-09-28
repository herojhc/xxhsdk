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
use XinXiHua\SDK\Models\CorporationPermanentCode as Code;
use XinXiHua\SDK\Models\User;

class IsvService
{
    public function install($eventMsg)
    {
        try {
            Log::debug('installEvent', [
                'eventMsg' => $eventMsg
            ]);
        } catch (\Throwable $exception) {

        }

        try {
            //
            DB::transaction(function () use ($eventMsg) {
                $corpId = $eventMsg->CorpId;
                $corpName = $eventMsg->CorpName;
                $permanentCode = $eventMsg->PermanentCode;
                $authUser = $eventMsg->AuthUser;
                $agentId = $eventMsg->AppId;
                $code = (new Code())->newQuery()->where([
                    ['corp_id', $corpId],
                    ['agent_id', $agentId]
                ])->first();

                if ($code) {

                    $code->permanent_code = $permanentCode;
                    $code->saveOrFail();
                    return;
                }


                $arr['permanent_code'] = $permanentCode;
                $arr['corp_id'] = $corpId;
                $arr['agent_id'] = $agentId;
                $arr['name'] = $corpName;

                if ((new Code())->newQuery()->forceCreate($arr)) {

                    // 添加一条记录到 corporations表
                    (new Corporation())->newQuery()->updateOrCreate([
                        'corp_id' => $corpId
                    ],
                        [
                            'name' => $corpName,
                            'status' => 1,
                            'user_id' => $authUser->user_id
                        ]);

                    // 添加管理员记录
                    (new User())->newQuery()->updateOrCreate([
                        'user_id' => $authUser->user_id
                    ], [
                        'name' => $authUser->name,
                        'mobile' => $authUser->mobile,
                        'avatar' => $authUser->avatar,
                        'mobile_validated' => 1
                    ]);

                    // 添加联系人
                    (new Contact())->newQuery()->updateOrCreate([
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

                    $corp = (new Corporation())->newQuery()->find($corpId);
                    $contact = (new Contact())->newQuery()->find($authUser->contact_id);
                    $user = (new User())->newQuery()->find($authUser->user_id);
                    // 触发事件
                    event(new Installed($corp, $user, $contact));
                }
            });

            // 一定要返回success
            return 'success';

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['exception' => $throwable]);
        }

        return 'error';

    }

    public function uninstall($eventMsg)
    {
        try {

            (new Code())->newQuery()->where([
                ['corp_id', $eventMsg->CorpId],
                ['agent_id', $eventMsg->AppId]
            ])->delete();

            $corp = (new Corporation())->newQuery()->find($eventMsg->CorpId);
            event(new Uninstalled($corp));
            // 返回success
            return 'success';

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage(), ['exception' => $throwable]);
        }

        return 'error';
    }
}