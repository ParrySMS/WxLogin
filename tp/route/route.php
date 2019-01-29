<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5555555555!';
});

Route::get('hello/:name', 'index/hello');


return [
   // 添加路由规则

//    定义路由规则后，如果开启强制路由转化，原来的URL地址将会失效，变成非法请求。

    ''=>'index/index/index', //首页

    'index/[:name]' => 'module1/ctrl_demo/index',
    'hello2' => 'module1/ctrl_demo/hello',
];
