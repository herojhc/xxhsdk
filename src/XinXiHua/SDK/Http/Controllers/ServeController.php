<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-07-23
 * Time: 23:58
 */

namespace XinXiHua\SDK\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use XinXiHua\SDK\Services\IsvService;
use XinXiHua\SDK\Support\Crypto\XxhCrypt;

class ServeController extends Controller
{

    protected $isv;

    protected $token;
    protected $encoding_key;
    protected $suite_key;

    public function __construct(IsvService $isv)
    {

        $this->isv = $isv;
        $this->token = config('xxh-sdk.agent.token');
        $this->encoding_key = config('xxh-sdk.agent.encoding_key');
        $this->suite_key = config('xxh-sdk.rest.shared_service_config.oauth2_credentials.client_id');

    }


    public function serve(Request $request)
    {
        $signature = $request->get("signature");
        $timeStamp = $request->get("timestamp");
        $nonce = $request->get("nonce");

        $encrypt = $request->input('encrypt');
        $crypt = new XxhCrypt($this->token, $this->encoding_key, $this->suite_key);

        $msg = "";
        $errCode = $crypt->DecryptMsg($signature, $timeStamp, $nonce, $encrypt, $msg);

        if ($errCode != 0) {
            Log::debug('errCode', [$errCode]);
            return 'error';
        } else {

            try {
                $eventMsg = json_decode($msg);
                $eventType = $eventMsg->EventType;

                $code = 'error';
                switch ($eventType) {
                    case 'permanent_code':

                        $code = $this->install($eventMsg);
                        break;
                    case 'del_permanent_code':

                        $code = $this->uninstall($eventMsg);
                        break;

                }
                return $code;
            } catch (\Exception $exception) {
                Log::error($exception->getMessage(), ['exception' => $exception]);
                return 'error';
            }
        }
    }


    /**
     * 安装
     *
     * @param $eventMsg
     * @return string
     *
     */
    private function install($eventMsg)
    {
        return $this->isv->install($eventMsg);
    }

    /**
     * 卸载
     *
     * @param $eventMsg
     * @return string
     *
     */
    private function uninstall($eventMsg)
    {
        return $this->isv->uninstall($eventMsg);
    }

}