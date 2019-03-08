<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-07-19
 * Time: 16:20
 */

namespace XinXiHua\SDK;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use XinXiHua\SDK\Facades\XXH;
use XinXiHua\SDK\Models\CorporationPermanentCode;

class AccessToken
{

    protected $oauth_tokens = [];

    protected $oauth_tokens_cache_key = 'xxh-rest-corp-client.oauth_tokens';

    protected $service_name;

    protected $use_cache_token = null;

    function __construct($service_name = null)
    {
        // use default service name
        if (empty($service_name)) {
            $service_name = $this->getConfig('default_service_name');
        }

        $this->service_name = $service_name;

        $this->setUp();
    }

    protected function setUp()
    {
        $minutes = $this->getConfig('oauth_tokens_cache_minutes', 10);
        $this->use_cache_token = $minutes > 0;
        $this->useOAuthTokenFromCache();
    }

    public function getIsvClient()
    {
        $client = new Rest\RestClient($this->service_name, false);
        $client->withOAuthTokenTypeClientCredentials();
        return $client;
    }

    public function getIsvCorpClient($authCorpId = null)
    {
        if (empty($authCorpId)) {
            $authCorpId = XXH::id();
        }
        $client = new Rest\RestClient($this->service_name, false);
        $access_token = $this->getIsvCorpAccessToken($authCorpId);
        $client->setOAuthToken($authCorpId, $access_token);
        $client->withOAuthToken($authCorpId);
        return $client;
    }

    //##################获取access_token##################

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function getConfig($key, $default = null)
    {
        return config("xxh-sdk.rest.$key", $default);
    }


    /**
     * 读取缓存
     */
    public function useOAuthTokenFromCache()
    {
        if (!$this->use_cache_token) {
            return;
        }

        $this->oauth_tokens = Cache::get($this->getOauthTokensCacheKey(), []);

    }


    /**
     * @param $authCorpId
     * @return mixed
     */
    public function getIsvCorpAccessToken($authCorpId)
    {

        if (!isset($this->oauth_tokens[$authCorpId])) {
            // request access token
            $permanentCode = CorporationPermanentCode::query()->where([
                ['corp_id', $authCorpId],
                ['agent_id', config('xxh-sdk.agent.agent_id')]
            ])->first();

            if ($permanentCode) {

                $response = $this->postRequestAccessToken($this->getOAuthGrantRequestData($authCorpId, $permanentCode->permanent_code));
                // handle access token
                if ($response->getResponse()->getStatusCode() != 200) {
                    throw new \RuntimeException('Failed to get access token for corporation [' . $authCorpId . ']!');
                }

                $result = $response->getResponseData();
                // 记录返回值
                Log::debug('restResponse', ['response' => $result]);

                if (!isset($result['data']['access_token'])) {
                    throw new \RuntimeException('"access_token" is not exists in the response data!');
                }
                $access_token = $result['data']['access_token'];
                $this->setOAuthToken($authCorpId, $access_token);
            } else {
                throw new \RuntimeException('app is not install');
            }
        }
        return $this->oauth_tokens[$authCorpId];
    }


    /**
     * @param $data
     * @return mixed
     */
    public function postRequestAccessToken($data)
    {
        return $this->getIsvClient()->post(config('xxh-sdk.agent.corp_token_api'), $data);
    }

    /**
     * @param $authCorpId
     * @param $permanentCode
     * @return array
     */
    public function getOAuthGrantRequestData($authCorpId, $permanentCode)
    {
        return [
            'corp_id' => $authCorpId,
            'permanent_code' => $permanentCode
        ];
    }


    /**
     * @param $authCorpId
     * @param $access_token
     */
    public function setOAuthToken($authCorpId, $access_token)
    {
        if (empty($access_token)) {
            unset($this->oauth_tokens[$authCorpId]);
        } else {
            $this->oauth_tokens[$authCorpId] = $access_token;
        }

        // update to cache
        $minutes = $this->getConfig('oauth_tokens_cache_minutes', 10);
        Cache::put($this->getOauthTokensCacheKey(), $this->oauth_tokens, $minutes);
    }


    /**
     * 获取 缓存 键
     *
     * @return string
     */
    protected function getOauthTokensCacheKey()
    {
        return $this->oauth_tokens_cache_key . '.' . $this->service_name;
    }
}