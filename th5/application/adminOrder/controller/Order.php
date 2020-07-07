<?php
namespace app\adminOrder\controller;

use app\base\controller\ModuleBaseController;

use think\Model;
use app\adminOrder\model\OrderManage;

class Order extends ModuleBaseController{
    // 获取订单列表
    public function getOrderArr(){
        $list = OrderManage::all();
        if($list){
            echo json_encode(['code'=>1000,'list'=>$list]);
        }
    }
}
