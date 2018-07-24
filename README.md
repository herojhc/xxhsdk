---
title: 信息化平台SDK使用说明
tags: 信息化、SDK
grammar_mindmap: true
---


[toc!?direction=lr]

# 环境
laravel 5.5 或 5.6 以及他们需要的环境

# 安装

``` r
composer require herojhc/xxh-sdk
```

# 配置
 
## 发布配置

``` php-cli
php artisan vendor:publish --provider="XinXiHua\SDK\XXHServiceProvider" --tag="xxh-sdk-config"
```

## 运行迁移

``` php-cli
php artisan vendor:publish --provider="XinXiHua\SDK\XXHServiceProvider" --tag="xxh-sdk-migrations"

php artisan migrate
```



## 参数说明
配置环境变量

``` makefile
#-----------
#AppId，具体见应用详情
REST_CLIENT_ID=cd188ae7d6344beb8ccc660643bf886f 
#AppSecret，具体见应用详情
REST_CLIENT_SECRET=138fa802f76c4f0ba472cfe9d9ebf852
#Api 地址
REST_CLIENT_API_URL=https://api.xinxihua.com
#具体API的HOST，不同的API分支对应不同的HOST，具体见信息化平台API总览
REST_CLIENT_API_HOST=api.v2.services.xinxihua.com
#-------------
#应用的ID（信息化平台），具体见应用详情
AUTH_AGENT_ID=10000
#前台回调地址
AUTH_HOME_URL=http://crm.xinxihua.com/callback
#后台回调地址
AUTH_ADMIN_URL=http://crm.xinxihua.com/admin
#信息化平台地址
AUTH_PALTFORM_URL=https://oa.xinxihua.com
#信息化平台授权登陆地址
AUTH_GATEWAY_URL=https://oa.xinxihua.com/sso-login
#应用通讯的TOKEN
AUTH_TOKEN=1QchOH0JQ6PGTCcFSapG
#应用通讯的加密字符串
AUTH_ENCODING_KEY=ZOFV3OSokCBmaC34zP3FM41B88dccl7AC65RuaoyuaB
```


# 使用

## 路由配置
在web路由中配置 

``` php
XXH::routes();
```

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

<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-07-24
 * Time: 11:51
 */

namespace XinXiHua\SDK\Services;


use Illuminate\Support\Facades\Log;
use XinXiHua\SDK\Exceptions\ApiException;

class ContactService extends BaseService
{


    public function all()
    {
        $response = $this->client->get('/contacts');

        Log::info($response->getResponse());
        if ($response->isResponseSuccess()) {
            return $response->getResponseData()['data'];
        }
        throw new ApiException($response->getResponseData()['message']);

    }

}

```




