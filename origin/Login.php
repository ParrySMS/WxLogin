<?php
/**
 * Created by PhpStorm.
 * User: L
 * Date: 2018-8-13
 * Time: 10:10
 */

class Login
{

    const  WXSNS_URL = 'https://api.weixin.qq.com/sns/';

    private $app_id;
    private $app_secret;

    public $crypt;

    /**
     * Login constructor.
     * @param string $app_id
     * @param string $app_secret
     * @param Crypt $crypt
     */
    public function __construct($app_id = APPID, $app_secret = APPSECRET,Crypt $crypt)
    {
        $this->app_id = $app_id;
        $this->app_secret = $app_secret;
        $this->crypt = $crypt;
    }


    /**网页授权1 用code换去access_token包 内含openid
     * @param $code
     * @param string $appid
     * @param string $appsecret
     * @return null
     */
    public function getAccessToken($code)
    {

        //appid 和 appsecret在配置文件中
        //根据code获得Access Token 与 openid
        $access_token_url = $this::WXSNS_URL .'oauth2/access_token';
        $data = [
            'appid' => $this->app_id,
            'secret' => $this->app_secret,
            'code' => $code,
            'grant_type' => 'authorization_code'
        ];

        $access_token_json = $this->get($access_token_url,$data);
        $access_token_object = json_decode($access_token_json);
        //var_dump($access_token_array);
        if (isset($access_token_object->errmsg)) {
            $errmsg = $access_token_object->errmsg;
            $errcode = $access_token_object->errmsg;
            throw new Exception("wxsns unauthorized: $errcode -> $errmsg", 501);
        }
        return empty($access_token_object) ? null : $access_token_object;
        /**
         * 正常返回
        access_token	网页授权接口调用凭证,注意：此access_token与基础支持的access_token不同
        expires_in	access_token接口调用凭证超时时间，单位（秒）
        refresh_token	用户刷新access_token
        openid	用户唯一标识，请注意，在未关注公众号时，用户访问公众号的网页，也会产生一个用户和公众号唯一的OpenID
        scope	用户授权的作用域，使用逗号（,）分隔
         */
    }

    /** 授权$access_token刷新  不刷新则7200s过期
     * @param $access_token
     * @return mixed|null
     * @throws Exception
     */
    public function refresh_token($access_token){
        $refresh_url = $this::WXSNS_URL .'oauth2/refresh_token';
        $data = [
            'appid' => $this->app_id,
            'refresh_token' => $access_token,
            'grant_type' => 'refresh_token'
        ];
        $refresh_json = $this->get($refresh_url,$data);
        $refresh_object = json_decode($refresh_json);
        if (isset($refresh_object->errmsg)) {
            $errmsg = $refresh_object->errmsg;
            $errcode = $refresh_object->errcode;
            throw new Exception("wxsns unauthorized: $errcode -> $errmsg", 501);
        }
        return empty($refresh_object) ? null : $refresh_object;
        /**
         * 正常返回
        access_token	网页授权接口调用凭证,注意：此access_token与基础支持的access_token不同
        expires_in	access_token接口调用凭证超时时间，单位（秒）
        refresh_token	用户刷新access_token
        openid	用户唯一标识
        scope	用户授权的作用域，使用逗号（,）分隔
         */
    }


    /** 拉取用户信息
     * @param $access_token
     * @param $openid
     * @return mixed|null
     * @throws Exception
     *
     */
    public function getUserinfo($access_token,$openid){
        $userinfo_url = $this::WXSNS_URL.'userinfo';
        $data = [
            'access_token' => $access_token,
            'openid' => $openid,
            'lang' => 'zh_CN'
        ];
        $userinfo_json = $this->get($userinfo_url,$data);
        $userinfo_object = json_decode($userinfo_json);
        if (isset($userinfo_object->errmsg)) {
            $errmsg = $userinfo_object->errmsg;
            $errcode = $userinfo_object->errcode;
            throw new Exception("wxsns unauthorized: $errcode -> $errmsg", 501);
        }
        return empty($userinfo_object) ? null : $userinfo_object;
        /**   正常返回
        openid	用户的唯一标识
        nickname	用户昵称
        sex	用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
        province	用户个人资料填写的省份
        city	普通用户个人资料填写的城市
        country	国家，如中国为CN
        headimgurl	用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空。若用户更换头像，原有头像URL将失效。
        privilege	用户特权信息，json 数组，如微信沃卡用户为（chinaunicom）
        unionid	只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段。
         */
    }


    /**
     * 模拟get进行url请求
     * @param string $url
     * @param array $post_data
     */
    public function get($url, array $data = [])
    {
        $ch = curl_init();
        /* 设置验证方式 */
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept:text/plain;charset=utf-8',
            'Content-Type:application/x-www-form-urlencoded',
            'charset=utf-8'
        ));
        /* 设置返回结果为流 */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        /* 设置超时时间*/
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        /* 设置通信方式 */
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if (!empty($data)) {
            $url = $url . '?' . http_build_query($data);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if ($result === false) {
            throw new Exception('Curl error: ' . $error, 501);
//            echo 'Curl error: ' . $error;
//            return null;
        } else {
            return $result;
        }
    }

    /**
     * 模拟post进行url请求
     * @param string $url
     * @param array $post_data
     */
    public function post($url, array $data = [])
    {
        $ch = curl_init();
        /* 设置验证方式 */
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept:text/plain;charset=utf-8',
            'Content-Type:application/x-www-form-urlencoded',
            'charset=utf-8'
        ));
        /* 设置返回结果为流 */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        /* 设置超时时间*/
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        /* 设置通信方式 */
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        $result = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if ($result === false) {
            throw new Exception('Curl error: ' . $error, 501);
//            return null;
        } else {
            return $result;
        }
    }

    /** 获取用户ip
     * @return array|false|string
     */
    public function getIP()
    {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
            $ip = getenv("REMOTE_ADDR");
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
            $ip = "unknown";
        return ($ip);
    }

    /** 获取客户端信息
     * @return string
     */
    public function getAgent()
    {
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            return $_SERVER['HTTP_USER_AGENT'];
        }else{
            return 'unknown';
        }
    }


    /** 生成token
     * @param $uid
     * @param $openid
     * @param string $key
     * @return mixed
     */
    public function createToken($uid,$openid,$key = TOKEN_CRYPT_KEY,$nonstr =NONSTR){
        $str = $this->crypt->getTokenStr($uid,$openid,$this->getIP(),$nonstr);
        return $this->crypt->encrypt($str,$key);
    }
}