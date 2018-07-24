<?php
/**
 * Created by PhpStorm.
 * User: JHC
 * Date: 2017-08-25
 * Time: 15:32
 */

namespace XinXiHua\SDK\Support\Crypto;


class SHA1
{


    public function getSHA1($token, $timestamp, $nonce, $encrypt_msg)
    {
        try {
            $array = array($encrypt_msg, $token, $timestamp, $nonce);
            sort($array, SORT_STRING);
            $str = implode($array);
            return array(ErrorCode::$OK, sha1($str));
        } catch (\Exception $e) {
            print $e . "\n";
            return array(ErrorCode::$ComputeSignatureError, null);
        }
    }

}