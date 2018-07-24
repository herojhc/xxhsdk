---
title: 信息化平台SDK使用说明
tags: 信息化、SDK
grammar_mindmap: true
---


[toc!?direction=lr]

# 环境
laravel 5.5 或 5.6 以及他们需要的环境

# 安装
composer require herojhc/xxh-sdk

# 配置
 
## 发布配置

php artisan vendor:publish

## 运行迁移

php artisan migrate

## 参数说明
> * rest.shared_service_config.oauth2_credentials  配置应用的 APPID 和APPSECRET，可以应用详情中查看
> * rest.services 中 base_uri 配置 api 的地址，headers 中的Host 配置 具体的api HOST
> * home.url 配置为授权前台的url
> * admin.url 配置为授权后台的url
> * agent 配置应用的具体信息，具体信息查看 应用详情


# 使用

## 路由配置
在web路由中配置 
XXH::routes();

将 serve 添加到 VerifyCsrfToken 中间中

``` php
<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'serve'
    ];
}
```



## 用法

``` php

namespace XinXiHua\SDK\Services;


use Illuminate\Support\Facades\Log;
use XinXiHua\SDK\AccessToken;
use XinXiHua\SDK\Exceptions\ApiException;

class ContactService
{

    protected $accessToken;

    function __construct(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function all()
    {
        $isvCorpClient = $this->accessToken->getIsvCorpClient();

        $response = $isvCorpClient->get('/contacts');

        Log::info($response->getResponse());
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseData()['message']);

    }

}

```




