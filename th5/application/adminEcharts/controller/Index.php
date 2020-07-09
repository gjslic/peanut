<?php
namespace app\adminEcharts\controller;


use think\Controller;
use think\Db;
use think\Session;
use app\base\controller\ModuleBaseController;
use think\Request;

class Index extends ModuleBaseController
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }


    // 用户数据
    public function user()
    {
        $result = Db::query("select count(*) as sum , MONTH(register_time) as months from peanut_user  where 1 = 1 GROUP BY months");
        if($result){
            $newArr = array();//新数组
		    foreach ($result as $key => $value) {
                //构建以key为月份  value为具体数值的新数组
                $newArr[$value['months']] = $value['sum'];
            }
            $info = array(
                //array_keys获取数组key集合
                "categories"=>array_keys($newArr),
                //array_values获取数组的value集合
                "data"=> array_values($newArr)
            );
		    echo json_encode(array("code"=>1000,"msg"=>"成功","data"=>$info));
        }else{
            echo json_encode(array("code"=>1001,"msg"=>'失败'));
        }
    }


    //销量
    public function sale()
    {
        $result = Db::query("select count(*) as sum , MONTH(transaction_time) as months from peanut_order  where 1 = 1 GROUP BY months");
        if($result){
            $newArr = array();//新数组
		    foreach ($result as $key => $value) {
                //构建以key为月份  value为具体数值的新数组
                $newArr[$value['months']] = $value['sum'];
            }
            $info = array(
                //array_keys获取数组key集合
                "categories"=>array_keys($newArr),
                //array_values获取数组的value集合
                "data"=> array_values($newArr)
            );
		    echo json_encode(array("code"=>1000,"msg"=>"成功","data"=>$info));
        }else{
            echo json_encode(array("code"=>1001,"msg"=>'失败'));
        }
    }
}
