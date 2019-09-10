<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-07-19
 * Time: 16:20
 */

namespace XinXiHua\SDK;

use Illuminate\Support\Facades\Cache;
use XinXiHua\SDK\Rest\RestClient;

class IsvClient
{

    protected $oauth_tokens = [];

    protected $oauth_tokens_cache_key = 'xxh-rest-isv-client.oauth_tokens';

    protected $service_name;

    protected $use_cache_token = null;

    /**
     * @var RestClient
     */
    protected $client;

    function __construct($service_name = null)
    {
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
        $this->client = new RestClient($this->service_name, false);
    }

    public function getClient()
    {
        $this->client->setOAuthToken($this->client::GRANT_TYPE_CLIENT_CREDENTIALS, $this->getOAuthToken($this->client::GRANT_TYPE_CLIENT_CREDENTIALS));
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
    private function getOAuthToken($grant_type)
    {
        if (!isset($this->oauth_tokens[$grant_type])) {
            // request access token
            $response = $this->client->postRequestAccessToken($grant_type, $this->client->getOAuthGrantRequestData($grant_type));

            // handle access token
            if ($response->getResponse()->getStatusCode() != 200) {
                throw new \RuntimeException('Failed to get access token for grant type [' . $grant_type . ']!');
            }

            $data = $response->getResponseData();
            if (!isset($data['access_token'])) {
                throw new \RuntimeException('"access_token" is not exists in the response data!');
            }
            $access_token = $data['access_token'];
            $this->setOAuthToken($grant_type, $access_token);
        }
        return $this->oauth_tokens[$grant_type];
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
        return $this->oauth_tokens_cache_key . '.' . $this->service_name;
    }
}