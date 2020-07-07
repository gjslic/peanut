<?php
namespace app\homepage\controller; //模块
use think\Db;
// 控制器
class Homepage
{
    //获取到城市
    public function getcity()
    {
        $get = $_GET;
        $where = [];
        $result = db('city')->where($where)->select();
        return json_encode($result);
    }
    // 获取到车标方法
    public function carbran()
    {
        $get = $_GET;
       $where = [];
       $result = db('brand')->where($where)->select();
       return json_encode($result);
    }

    //获取到价格范围
    public function carprice()
    {
        $get = $_GET;
        $where = [];
        $result = db('price')->where($where)->select();
        return json_encode($result);
    }

     //获取到车辆类型
     public function carstyle()
     {
         $get = $_GET;
         $where = [];
         $result = db('style')->where($where)->select();
         return json_encode($result);
     }

     //点击获取到推荐车辆
     public function reccar()
     {
         //获取到传来的参数
        $tab_num = getPost()['tab_num'];

        //判断参数
        switch($tab_num){
            case 'first':
            $where =[
                'tab_id' =>1,
            ];
            $result = db('vehicle')->where($where)->limit(4)->select();
            return json_encode($result);
        break;

        case 'second':
            $where =[
                'tab_id' =>2,
            ];
            $result = db('vehicle')->where($where)->limit(4)->select();
            return json_encode($result);
        break;

        case 'third':
            $where =[
                'tab_id' =>3,
            ];
            $result = db('vehicle')->where($where)->limit(4)->select();
            return json_encode($result);
        break;

        case 'fourth':
            $where =[
                'tab_id' =>4,
            ];
            $result = db('vehicle')->where($where)->limit(4)->select();
            return json_encode($result);
        break;

        case 'fifth':
            $where =[
                'tab_id' =>5,
            ];
            $result = db('vehicle')->where($where)->limit(4)->select();
            return json_encode($result);
        break;
        }
     }

     //开始获取推荐车辆
     public function getreccar()
     {
         $get = $_GET;
         $where = [];
         $result = db('vehicle')->where($where)->limit(4)->select();
        return json_encode($result);
     }
    
     //传递车标
     public function passbrand()
     {
        //获取到传来的参数
        //到车辆表比对车标id
        $brandid = getPost()['brandid'];
        $where = [
            
        ];
     }

     //传递价格
     public function passprice()
     {
        //获取到传来的参数
        //到车辆表比对车标id
        $price_id = getPost()['price_id'];
     }

     //传递车型
     public function passstyle()
     {
        //获取到传来的参数
        //到车辆表比对车标id
        $style_id = getPost()['style_id'];
     }

     //传递推荐车辆
     public function passReccar()
     {
        //获取到传来的参数
        //到车辆表比对车标id
        $reccarid = getPost()['reccarid'];
     }

     //传递城市
     public function passCity()
     {
        $passCityid = getPost()['passCityid'];
     }
}