<?php
namespace app\admin_order\controller;

use app\base\controller\ModuleBaseController;

use think\Model;
use app\admin_order\model\OrderManage;

class Backorder extends ModuleBaseController{
  /**
   * [getOrderArr 获取订单列表]
   */ 
  public function getOrderArr(){
    $type = input('post.showType');
    $orderNum = input('post.searchInfo') ?? '';
    $endOrder = "'交易完成','退款完成'";
    $unfinish = "'待验收','待评价','待确认','退款审核中'";
    switch($type){
      case '全部订单':
        $where = ' 1 = 1';
      break;
      case '未完成':
        $where = "o.state in ($unfinish)";
      break;
      case '已完成':
        $where = "o.state in ($endOrder)";
      break;
      case '待审核':
        $where = 'o.state = "退款审核中" or o.state = "待确认"';
      break;
    }
    if($orderNum){
      $where .= " and o.order_num = '$orderNum'";
    }
    $order = db('order')->alias('o')
    ->join('peanut_user u','o.buy_id = u.id')
    ->join('peanut_vehicle v','o.vehicle_id = v.vehicle_id')
    ->field('o.id,o.order_num as orderNum,o.state,o.transaction_time as orderTime,u.acc as buyer,u.phone,u.head_img,u.name as uName,v.price,v.vehicle_name as carName,v.img as carImg')
    ->where($where)
    ->select();
    return $order ? json_encode($this->actionSuccess($order)) : json_encode($this->actionFail('空数据'));
  }

  /**
   * [getNowOrder 获取当前订单卖家]
   */
  public function getNowOrder(){
    $id = input('post.nowId');
    $seller = db('user u,peanut_order o')->field('u.acc as seller,u.phone as sellerTel,u.name as sName')->where("o.sell_id = u.id AND o.id = '$id'")->find();
    return $seller ? json_encode($this->actionSuccess($seller)) : json_encode($this->actionFail('网络异常，请刷新重试'));
  }
  /**
   * [editState 修改订单状态]
   * @pramas 
   * $orderId      => 当前订单id
   * $buyerAcc     => 买家账号
   * $price        => 车辆价格
   * $seller       => 卖家信息（账号，余额）
   * $buyer        => 买家余额
   * $money        => 退款
   * $carState     => 当前车辆状态
   * $editCarState => 修改当前车辆状态
   * $editSell     => 卖家余额修改
   * $editbuyer    => 买家余额修改
   * $creditBuyer  => 扣除买家信誉分结果
   * $editState    => 修改订单状态
   */
  public function editState(){
    $orderId = input('post.nowId');
    $carState = db('vehicle v,peanut_order o')->field('v.vehicle_state')->where("v.vehicle_id = o.vehicle_id and o.id = '$orderId'")->find();
    $buyerAcc = input('post.buyId');
    $price = input('post.price');
    $seller = db('order o , peanut_user u')->field('u.acc,u.money')->where("o.id = '$orderId' and o.sell_id = u.id")->find();
    $buyer = db('user')->field('money')->where("acc = '$buyerAcc'")->find();
    $money = ($price * 10000) - ($price * 0.05 * 10000);
    if($seller['money'] - $money > 0){
      $carState = db('vehicle v,peanut_order o')->field('v.vehicle_state')->where("v.vehicle_id = o.vehicle_id and o.id = '$orderId'")->find();
      if($carState['vehicle_state'] == '已拍卖'){
        $state = '拍卖中';
      }else if($carState['vehicle_state'] == '已下架'){
        $state = '已上架';
      }
      $editCarState = db('vehicle v,peanut_order o')->where("v.vehicle_id = o.vehicle_id and o.id = '$orderId'")->update(['v.vehicle_state' => $state , 'v.buy_id' => null]);
      $editSell = db('user')->where('acc',$seller['acc'])->setDec('money',$money);
      $editbuyer = db('user')->where('acc',$buyerAcc)->setInc('money',$money);
      $orderWhere = "acc = $buyerAcc AND credit >= 80";
      $creditBuyer = db('user')->where($orderWhere)->setDec('credit',3);
      $editState = db('order')->where('id',$orderId)->setField('state','退款完成');
      echo json_encode($this->actionSuccess([],0,'退款成功'));
    }else{
      $sellerAcc = $seller['acc'];
      $orderWhere = "acc = '$sellerAcc' AND credit >= 80";
      $creditSeller = db('user')->where($orderWhere)->setDec('credit',3);
      $editState = db('order')->where('id',$orderId)->setField('state','待确认');
      echo json_encode($this->actionFail('卖家余额不足，请提醒卖家尽快充值'));
    }
  }
}
