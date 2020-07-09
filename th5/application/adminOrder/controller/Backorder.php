<?php
namespace app\adminOrder\controller;

use app\base\controller\ModuleBaseController;

use think\Model;
use app\adminOrder\model\OrderManage;

class Backorder extends ModuleBaseController{
    // 获取订单列表
    public function getOrderArr(){
        // 卖家账号
        // $seller = db('order o , user u')->field('u.phone')->where('o.sell_id = u.id')->select();
        // 买家账号
        // $buyer = db('order o , user u')->field('u.phone')->where('o.buy_id = u.id')->select();
        // 车辆信息
        // $carInfo = db('order o , vehicle v')->field('v.price,v.vehicle_name as carName,v.img as carImg')->where('o.vehicle_id = v.id')->find();

    }
}
