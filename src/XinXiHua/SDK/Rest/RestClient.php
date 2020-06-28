<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-07-24
 * Time: 9:49
 */

namespace XinXiHua\SDK\Rest;

use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class RestClient
{
    /**
     * @var string
     */
    private $service_name;

    /**
     * @var array
     */
    protected $service_config;

    /**
     * @var array
     */
    protected $shared_service_config;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $guzzle_response;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var array
     */
    protected $oauth_tokens = [];

    // Grant Types
    const GRANT_TYPE_CLIENT_CREDENTIALS = 'client_credentials';
    const GRANT_TYPE_AUTHORIZATION_CODE = 'authorization_code';
    const GRANT_TYPE_PASSWORD = 'password';
    const GRANT_TYPE_REFRESH_TOKEN = 'refresh_token';

    protected $use_oauth_token_grant_type = null;

    protected $oauth_grant_request_data = [
        self::GRANT_TYPE_CLIENT_CREDENTIALS => [],
        self::GRANT_TYPE_AUTHORIZATION_CODE => [],
        self::GRANT_TYPE_PASSWORD => [],
        self::GRANT_TYPE_REFRESH_TOKEN => [],
    ];

    /**
     * @var bool
     */
    protected $debug_mode = false;

    /**
     * @var string
     */
    protected $environment;

    /**
     * Create a new RestClient Instance
     * @param $service_name
     * @param null $debug_mode
     */
    public function __construct($service_name = null, $debug_mode = null)
    {
        $this->environment = $this->getConfig('environment', 'production');
        $this->shared_service_config = $this->getConfig('shared_service_config');
        $this->debug_mode = $debug_mode !== null ? $debug_mode : $this->getConfig('debug_mode');
        $services = $this->getConfig('services');

        // use default service name
        if (empty($service_name)) {
            $service_name = $this->getConfig('default_service_name');
        }

        $this->service_name = $service_name;

        // choose service environment
        if (!isset($services[$this->environment])) {
            throw new RuntimeException("Rest Client Error: Service for environment [{$this->environment}] is not found in config.");
        }
        $services = $services[$this->environment];

        // check service configs
        if (!isset($services[$service_name])) {
            throw new RuntimeException("Rest Client Error: Service [$service_name] is not found in environment [{$this->environment}] config.");
        }

        $this->printLine("--------");
        $this->printLine("REST CLIENT SERVICE: " . $service_name . ", ENVIRONMENT: " . $this->environment);

        $this->setServiceConfig($services[$service_name]);

        $this->setUp();
    }

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
     * @param $service_config
     */
    private function setServiceConfig($service_config)
    {
        $shared_service_config = $this->shared_service_config;

        $this->service_config = $this->mergeConfig($shared_service_config, $service_config);
    }

    /**
     * @param $service_config
     */
    public function addServiceConfig(array $service_config)
    {
        $this->service_config = $this->mergeConfig($this->service_config, $service_config);
    }

    /**
     * @param array $headers
     */
    public function addHeaders(array $headers)
    {
        $service_config['headers'] = $headers;
        $this->addServiceConfig($service_config);
    }

    /**
     *
     * New Config will override Base Config if both present
     *
     * @param array $baseConfig
     * @param array $newConfig
     * @return array
     */
    private function mergeConfig($baseConfig = [], $newConfig = [])
    {
        $combined_service_config = $newConfig;
        foreach ($baseConfig as $key => $config) {
            if (is_array($config) && isset($combined_service_config[$key])) {
                $combined_service_config[$key] = array_merge($config, $combined_service_config[$key]);
            } else if (!isset($combined_service_config[$key])) {
                $combined_service_config[$key] = $config;
            }
        }
        return $combined_service_config;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getServiceConfig($key)
    {
        return $this->service_config[$key];
    }

    /**
     *  Set Up Client
     */
    public function setUp()
    {
        $base_uri = $this->getServiceConfig('base_uri');
        $guzzle_client_config = $this->getConfig('guzzle_client_config', []);
        $base_uri = rtrim($base_uri, '\/') . './';
        $this->printLine("REST CLIENT BASE URI: " . $base_uri);
        $this->client = new Client(array_merge($guzzle_client_config, [
            'base_uri' => $base_uri,
            'exceptions' => false,
        ]));
    }

    /**
     * @param boolean $debug_mode
     */
    public function setDebugMode($debug_mode)
    {
        $this->debug_mode = $debug_mode;
    }

    /**
     * @return mixed
     */
    protected function getClientData()
    {
        return $this->getServiceConfig('oauth2_credentials');
    }

    /**
     * @param $grant_type
     * @param array $data
     */
    public function setOAuthGrantRequestData($grant_type, array $data)
    {
        $this->oauth_grant_request_data[$grant_type] = $data;
    }

    /**
     * @param $grant_type
     * @return array
     */
    public function getOAuthGrantRequestData($grant_type)
    {
        if (!isset($this->oauth_grant_request_data[$grant_type])) {
            throw new RuntimeException('Request Data was not found for grant type [' . $grant_type . '] in "oauth_grant_request_data"');
        }
        $data = $this->oauth_grant_request_data[$grant_type];
        return array_merge($this->getClientData(), $data);
    }

    /**
     * @param $grant_type
     * @param $data
     * @return $this;
     */
    public function postRequestAccessToken($grant_type, $data)
    {
        $url = $this->getServiceConfig('oauth2_access_token_url');
        return $this->post($url, array_merge($data, [
            'grant_type' => $grant_type,
        ]), [], false);
    }

    /**
     * @param $options
     * @return array
     */
    private function configureOptions($options)
    {
        $headers = $this->getServiceConfig('headers');

        // add client ip to header
        $request = request();
        $clientIp = $request->getClientIp();
        $headers['X-Client-Ip'] = $clientIp;
        $headers['X-Forwarded-For'] = $clientIp;
        $headers['Accept-Language'] = $request->header('Accept-Language', app()->getLocale());

        if ($this->use_oauth_token_grant_type) {
            $headers['Authorization'] = 'Bearer ' . $this->getOAuthToken($this->use_oauth_token_grant_type);
        }

        if (isset($options['headers'])) {
            $headers = array_merge($headers, $options['headers']);
            unset($options['headers']);
        }

        return array_merge([
            'headers' => $headers,
        ], $options);
    }

    /**
     * @param $grant_type
     * @return mixed
     */
    private function getOAuthToken($grant_type)
    {
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
    }

    /**
     * @param $grant_type
     * @param array|null $requestData
     * @return $this
     */
    public function withOAuthToken($grant_type, $requestData = null)
    {
        if ($requestData !== null) {
            $this->setOAuthGrantRequestData($grant_type, $requestData);
        }
        $this->getOAuthToken($grant_type);
        $this->use_oauth_token_grant_type = $grant_type;
        return $this;
    }

    /**
     * @param array|null $requestData
     * @return RestClient
     */
    public function withOAuthTokenTypePassword($requestData = null)
    {
        return $this->withOAuthToken(self::GRANT_TYPE_PASSWORD, $requestData);
    }

    /**
     * @param array|null $requestData
     * @return RestClient
     */
    public function withOAuthTokenTypeClientCredentials($requestData = null)
    {
        return $this->withOAuthToken(self::GRANT_TYPE_CLIENT_CREDENTIALS, $requestData);
    }

    /**
     * @param array|null $requestData
     * @return RestClient
     */
    public function withOAuthTokenTypeAuthorizationCode($requestData = null)
    {
        return $this->withOAuthToken(self::GRANT_TYPE_AUTHORIZATION_CODE, $requestData);
    }

    /**
     * @return $this
     */
    public function withoutOAuthToken()
    {
        $this->use_oauth_token_grant_type = null;
        return $this;
    }

    /**
     * @param string $uri
     * @param array $query
     * @param array $options
     * @param bool $api
     * @return $this ;
     */
    public function get($uri, array $query = [], array $options = [], $api = true)
    {
        $options = $this->configureOptions($options);
        $uri = $api ? $this->getServiceConfig('api_url') . $uri : $uri;
        $this->printArray($options);
        $response = $this->client->get($uri, array_merge($options, [
            'query' => $query,
        ]));
        $this->setGuzzleResponse($response);
        return $this;
    }

    /**
     * @param string $uri
     * @param array $data
     * @param array $options
     * @param bool $api
     * @return $this;
     */
    public function post($uri, array $data = [], array $options = [], $api = true)
    {
        $options = $this->configureOptions($options);
        $uri = $api ? $this->getServiceConfig('api_url') . $uri : $uri;
        $response = $this->client->post($uri, array_merge($options, [
            'form_params' => $data,
        ]));
        $this->setGuzzleResponse($response);
        return $this;
    }

    /**
     * @url http://docs.guzzlephp.org/en/latest/quickstart.html#sending-form-files
     * @param $uri
     * @param array $multipart
     * @param array $options
     * @param bool $api
     * @return $this;
     */
    public function postMultipart($uri, array $multipart = [], array $options = [], $api = true)
    {
        $options = $this->configureOptions($options);
        $uri = $api ? $this->getServiceConfig('api_url') . $uri : $uri;
        $response = $this->client->post($uri, array_merge($options, [
            'multipart' => $multipart,
        ]));
        $this->setGuzzleResponse($response);
        return $this;
    }

    /**
     * @param $uri
     * @param array $data
     * @param array $options
     * @param bool $api
     * @return $this;
     */
    public function postMultipartSimple($uri, array $data = [], array $options = [], $api = true)
    {
        $options = $this->configureOptions($options);
        $uri = $api ? $this->getServiceConfig('api_url') . $uri : $uri;
        $multipart = [];
        foreach ($data as $key => $value) {
            $multipart[] = [
                'name' => $key,
                'contents' => $value,
            ];
        }
        $response = $this->client->post($uri, array_merge($options, [
            'multipart' => $multipart,
        ]));
        $this->setGuzzleResponse($response);
        return $this;
    }

    /**
     * @param string $uri
     * @param array $data
     * @param array $options
     * @param bool $api
     * @return $this;
     */
    public function head($uri, array $data = [], array $options = [], $api = true)
    {
        $uri = $api ? $this->getServiceConfig('api_url') . $uri : $uri;
        $response = $this->client->head($uri, array_merge($options, [
            'body' => $data,
        ]));
        $this->setGuzzleResponse($response);
        return $this;
    }

    /**
     * @param string $uri
     * @param array $data
     * @param array $options
     * @param bool $api
     * @return $this;
     */
    public function put($uri, array $data = [], array $options = [], $api = true)
    {
        $options = $this->configureOptions($options);
        $uri = $api ? $this->getServiceConfig('api_url') . $uri : $uri;
        $response = $this->client->put($uri, array_merge($options, [
            'form_params' => $data,
        ]));
        $this->setGuzzleResponse($response);
        return $this;
    }

    /**
     * @param string $uri
     * @param array $data
     * @param array $options
     * @param bool $api
     * @return $this;
     */
    public function patch($uri, array $data = [], array $options = [], $api = true)
    {
        $options = $this->configureOptions($options);
        $uri = $api ? $this->getServiceConfig('api_url') . $uri : $uri;
        $response = $this->client->patch($uri, array_merge($options, [
            'form_params' => $data,
        ]));
        $this->setGuzzleResponse($response);
        return $this;
    }

    /**
     * @param string $uri
     * @param array $data
     * @param array $options
     * @param bool $api
     * @return $this;
     */
    public function delete($uri, array $data = [], array $options = [], $api = true)
    {
        $options = $this->configureOptions($options);
        $uri = $api ? $this->getServiceConfig('api_url') . $uri : $uri;
        $response = $this->client->delete($uri, array_merge($options, [
            'form_params' => $data,
        ]));
        $this->setGuzzleResponse($response);
        return $this;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getGuzzleResponse()
    {
        return $this->guzzle_response;
    }

    /**
     * @param ResponseInterface $response
     */
    public function setGuzzleResponse(ResponseInterface $response)
    {
        $this->guzzle_response = $response;
        $this->setResponse(new Response($response->getBody(), $response->getStatusCode(), $response->getHeaders()));
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
        // 记录下返回信息
        Log::debug($response);
        $statusCode = $this->response->getStatusCode();
        if ($statusCode >= 300 && $this->debug_mode) {
            echo "\nResponse STATUS CODE is $statusCode:\n";
            $responseData = $this->getResponseData();
            if ($responseData) {
                $this->printArray($responseData);
            } else {
                $this->printLine($this->getResponse());
            }
        }
    }

    /**
     * Response is success if status code is < 300
     *
     * @return bool
     */
    public function isResponseSuccess()
    {
        return $this->getResponse()->getStatusCode() < 300;
    }

    /**
     * @param $status_code
     * @return bool
     */
    public function isResponseStatusCode($status_code)
    {
        return $this->getResponse()->getStatusCode() == $status_code;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param bool $assoc
     * @return mixed
     */
    public function getResponseAsJson($assoc = true)
    {
        return json_decode($this->getResponse()->getContent(), $assoc);
    }

    /**
     * @return mixed
     */
    public function getResponseData()
    {
        return $this->getResponseAsJson();
    }

    /**
     * @return array|mixed|null
     */
    public function getResponseErrors()
    {
        $responseData = $this->getResponseData();
        if (is_array($responseData) && isset($responseData['errors'])) {
            return $responseData['errors'];
        } else {
            return null;
        }
    }

    /**
     * @return string|mixed|null
     */
    public function getResponseMessage()
    {
        $responseData = $this->getResponseData();
        if (is_array($responseData) && isset($responseData['message'])) {
            return $responseData['message'];
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function printResponseData()
    {
        print_r($this->getResponseData());
        return $this;
    }

    /**
     * @return $this
     */
    public function printResponseOriginContent()
    {
        print_r((string)$this->response->getOriginalContent());
        return $this;
    }

    /**
     * @param $string
     */
    protected function printLine($string)
    {
        if ($this->debug_mode) {
            echo $string . "\n";
        }
    }

    /**
     * @param $array
     */
    protected function printArray($array)
    {
        if ($this->debug_mode) {
            print_r($array);
        }
    }

}