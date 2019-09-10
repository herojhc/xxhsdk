<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-07-19
 * Time: 16:20
 */

namespace XinXiHua\SDK;

use Illuminate\Support\Facades\Cache;
use XinXiHua\SDK\Facades\XXH;
use XinXiHua\SDK\Models\CorporationPermanentCode as Repository;
use XinXiHua\SDK\Rest\RestClient;

class CorpClient
{

    protected $oauth_tokens = [];

    protected $oauth_tokens_cache_key = 'xxh-rest-corp-client.oauth_tokens';

    protected $service_name;

    protected $use_cache_token = null;

    protected $auth_corp_id;

    /**
     * @var RestClient
     */
    protected $client;

    function __construct($service_name = null, $auth_corp_id = null)
    {
        // use default service name
        if (empty($service_name)) {
            $service_name = $this->getConfig('default_service_name');
        }

        $this->service_name = $service_name;
        $this->auth_corp_id = $auth_corp_id;
        $this->setUp();
    }

    protected function setUp()
    {
        if (empty($this->auth_corp_id)) {
            $this->auth_corp_id = XXH::id();
        }
        $minutes = $this->getConfig('oauth_tokens_cache_minutes', 10);
        $this->use_cache_token = $minutes > 0;
        $this->useOAuthTokenFromCache();
        $this->client = new RestClient($this->service_name, false);
    }

    public function getClient($auth_corp_id = null)
    {
        if (!empty($auth_corp_id)) {
            $this->auth_corp_id = $auth_corp_id;
            $this->useOAuthTokenFromCache();
        }
        $this->client->setOAuthToken($this->client::GRANT_TYPE_CLIENT_CREDENTIALS, $this->getOauthToken($this->client::GRANT_TYPE_CLIENT_CREDENTIALS));
        $this->client->withOAuthTokenTypeClientCredentials();
        return $this->client;
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
     * @param $grant_type
     * @return mixed
     */
    public function getOAuthToken($grant_type)
    {

        if (!isset($this->oauth_tokens[$grant_type])) {
            // request access token
            $permanentCode = Repository::query()->where([
                ['corp_id', $this->auth_corp_id],
                ['agent_id', config('xxh-sdk.agent.agent_id')]
            ])->first();

            if ($permanentCode) {
                $response = $this->postRequestAccessToken($this->getOAuthGrantRequestData($permanentCode->permanent_code));
                // handle access token
                if ($response->getResponse()->getStatusCode() != 200) {
                    throw new \RuntimeException('Failed to get access token for corporation [' . $this->auth_corp_id . ']!');
                }

                $result = $response->getResponseData();
                if (!isset($result['data']['access_token'])) {
                    throw new \RuntimeException('"access_token" is not exists in the response data!');
                }
                $access_token = $result['data']['access_token'];
                $this->setOAuthToken($grant_type, $access_token);
            } else {
                throw new \RuntimeException('app is not install');
            }
        }
        return $this->oauth_tokens[$grant_type];
    }


    /**
     * @param $data
     * @return mixed
     */
    public function postRequestAccessToken($data)
    {
        return (new IsvClient($this->service_name))->getClient()->post(config('xxh-sdk.agent.corp_token_api'), $data);
    }

    /**
     * @param $permanentCode
     * @return array
     */
    public function getOAuthGrantRequestData($permanentCode)
    {
        return [
            'corp_id' => $this->auth_corp_id,
            'permanent_code' => $permanentCode
        ];
    }


    /**
     * @param $grant_type
     * @param $access_token
     */
    public function setOAuthToken($grant_type, $access_token)
    {
        if (empty($access_token)) {
            unset($this->oauth_tokens[$grant_type]);
        } else {
            $this->oauth_tokens[$grant_type] = $access_token;
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
        return $this->oauth_tokens_cache_key . '.' . $this->service_name . '.' . $this->auth_corp_id;
    }
}