<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2018-12-25
 * Time: 10:26
 */

namespace XinXiHua\SDK\Support\Sign;


class MakeSign
{

    protected $signType = 'sha256';

    public function __construct($signType = null)
    {
        if (!empty($signType)) {
            $this->signType = $signType;
        }
    }

    public function setSingType($signType)
    {
        $this->signType = $signType;
    }

    public function sign($key, $params)
    {
        try {
            $params = $this->decodeParams($params);
            // 字典排序
            ksort($params);
            $unsignedData = "";
            foreach ($params as $paramKey => $paramValue) {
                if (!empty($paramValue)) {
                    $unsignedData .= $paramKey . '=' . trim($paramValue) . '&';
                }
            }
            // 生成签名
            return $this->generateResponseBodySignature($key, rtrim($unsignedData, '&'));
        } catch (\Exception $exception) {
        }
        return false;
    }

    public function check($sign, $key, $params)
    {
        try {
            return $sign === $this->sign($key, $params);
        } catch (\Exception $exception) {
        }
        return false;
    }

    private function generateResponseBodySignature($secretKey, $body)
    {
        return base64_encode(hash_hmac($this->signType, $body, $secretKey, true));
    }

    public function encodeParams(array $params)
    {
        $_params = [];
        foreach ($params as $k => $param) {
            $_params[$k] = rawurlencode($param);
        }
        return $_params;
    }

    public function decodeParams($params)
    {
        $_params = [];
        foreach ($params as $k => $param) {
            $_params[$k] = rawurldecode($param);
        }
        return $_params;
    }
}
