<?php
/**
 * Created by PhpStorm.
 * User: L
 * Date: 2018-8-13
 * Time: 15:00
 */

class Mcrypt implements Crypt
{


    public function getTokenStr($uid, $openid, $ip,$nonstr)
    {
        return $uid . "+" . md5($openid) . "+" . $ip . "+" . date("M-d H:i:s"). $nonstr;
    }

    /** openssl的加密方法
     * @param $data
     * @param $key
     * @return string
     */
    public function encrypt($data, $key)
    {
        $l = strlen($key);
        if ($l < 16) {
            $key = str_repeat($key, ceil(16 / $l));
        }

        if ($m = strlen($data) % 8) {
            $data .= str_repeat("\x00", 8 - $m);
        }

        $val = openssl_encrypt($data, 'BF-ECB', $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING);

        return $val;
    }

    /** openssl的解密方法
     * @param $data
     * @param $key
     * @return string
     */
    public function decrypt($data, $key)
    {
        $l = strlen($key);
        if ($l < 16) {
            $key = str_repeat($key, ceil(16 / $l));
        }

        $val = openssl_decrypt($data, 'BF-ECB', $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING);

        return $val;
    }

}