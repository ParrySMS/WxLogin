<?php
/**
 * Created by PhpStorm.
 * User: haier
 * Date: 2017-11-6
 * Time: 11:44
 */


/** 若安全性要求增加 可以更换更复杂的加密算法
 * Interface Crypt
 */
interface Crypt
{
    /*
     * 加密
     */
    public function encrypt($data, $key);

    /*
     * 解密
     */
    public function decrypt($data, $key);

    /*
     * 生成加密前的字串
     */
    public function getTokenStr($uid,$openid,$ip,$nonstr);

}