<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2017-08-25
 * Time: 16:10
 */

namespace XinXiHua\SDK\Support\Crypto;


class Prpcrypt
{
    public $key;

    function __construct($k)
    {
        $this->key = base64_decode($k . "=");
    }


    public function encrypt($text, $corpid)
    {
        try {
            //获得16位随机字符串，填充到明文之前
            $random = $this->getRandomStr();
            $text = $random . pack("N", strlen($text)) . $text . $corpid;
            $iv = substr($this->key, 0, 16);
            $pkc_encoder = new PKCS7Encoder;
            $text = $pkc_encoder->encode($text);
            $encrypted = openssl_encrypt($text, 'AES-256-CBC', substr($this->key, 0, 32), OPENSSL_ZERO_PADDING, $iv);
            return array(ErrorCode::$OK, $encrypted);
        } catch (\Exception $e) {
            return array(ErrorCode::$EncryptAESError, null);
        }
    }


    /**
     * 对密文进行解密
     * @param string $encrypted 需要解密的密文
     * @return string|array 解密得到的明文
     */
    public function decrypt($encrypted, $corpid)
    {
        try {
            $iv = substr($this->key, 0, 16);
            $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', substr($this->key, 0, 32), OPENSSL_ZERO_PADDING, $iv);
        } catch (\Exception $e) {
            return array(ErrorCode::$DecryptAESError, null);
        }
        try {
            //去除补位字符
            $pkc_encoder = new PKCS7Encoder;
            $result = $pkc_encoder->decode($decrypted);
            //去除16位随机字符串,网络字节序和AppId
            if (strlen($result) < 16)
                return "";
            $content = substr($result, 16, strlen($result));
            $len_list = unpack("N", substr($content, 0, 4));
            $xml_len = $len_list[1];
            $xml_content = substr($content, 4, $xml_len);
            $from_corpid = substr($content, $xml_len + 4);
        } catch (\Exception $e) {
            return array(ErrorCode::$DecryptAESError, null);
        }
        if ($from_corpid != $corpid)
            return array(ErrorCode::$ValidateSuiteKeyError, null);
        return array(0, $xml_content);
    }


    function getRandomStr()
    {

        $str = "";
        $str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($str_pol) - 1;
        for ($i = 0; $i < 16; $i++) {
            $str .= $str_pol[mt_rand(0, $max)];
        }
        return $str;
    }

}