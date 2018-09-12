<?


//todo token 加密密钥 上线前需要变更
define('TOKEN_CRYPT_KEY','fhiashfsol');
//todo 随机加密参数  上线前需要变更
define('NONSTR','随机字符');
//token名  
define('TOKEN_NAME','sys_token');
//cookie 路径
define('PATH','/');
//token 过期时间
define('EXPIRES',time()+3600*24*30);

//微信服务号  上线前需要填写
define("APPID",'');
define("APPSECRET",'');
