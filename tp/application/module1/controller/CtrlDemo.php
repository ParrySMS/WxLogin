<?php
namespace app\module1\controller;

use think\Controller;
use think\Db;

class CtrlDemo extends Controller
{
    public function index($name3='name')
    {
        echo __DIR__.'<br/>';
        $this->assign('name',$name3);//绑定变量名
        return $this->fetch('hello');//使用模板
//        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
    }

    public function hello($name = 'ThinkPHP5')
    {
//        echo __DIR__;
        $this->assign('name',$name);
        return $this->fetch();

    }

    /**
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function db_customers()
    {
        $data = Db::name('db_customers')->find();
        $this->assign('res',$data);

        return $this->fetch('db');


    }
    protected function profunc()
    {
        return 'protect';
    }

    private function prifunc()
    {
        return 'private';
    }
}