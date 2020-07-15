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
    $endOrder = "'交易完成','退款完成','退款失败'";
    $unfinish = "'待验收','待评价','退款审核中'";
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
        $where = 'o.state = "退款审核中"';
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
   * $carState     => 当前车辆状态
   * $editCarState => 修改当前车辆状态
   * $editbuyer    => 买家余额修改
   * $creditBuyer  => 扣除买家信誉分结果
   * $editState    => 修改订单状态
   */
  public function editState(){
    $orderId = input('post.nowId');
    $buyerAcc = input('post.buyId');
    $price = input('post.price');
    $buyer = db('user')->field('money')->where("acc = '$buyerAcc'")->find();
    $carState = db('vehicle v,peanut_order o')->field('v.vehicle_state')->where("v.vehicle_id = o.vehicle_id and o.id = '$orderId'")->find();
    if($carState['vehicle_state'] == '已拍卖'){
      $state = '拍卖中';
    }else if($carState['vehicle_state'] == '已下架'){
      $state = '已上架';
    }
    $editCarState = db('vehicle v,peanut_order o')->where("v.vehicle_id = o.vehicle_id and o.id = '$orderId'")->update(['v.vehicle_state' => $state , 'v.buy_id' => null]);
    $editbuyer = db('user')->where('acc',$buyerAcc)->setInc('money',$price);
    $orderWhere = "acc = $buyerAcc AND credit >= 80";
    $creditBuyer = db('user')->where($orderWhere)->setDec('credit',3);
    $editState = db('order')->where('id',$orderId)->setField('state','退款完成');
    echo json_encode($this->actionSuccess([],0,'退款成功'));
  }

  /**
   * [reasonRefund 查看退款理由]
   */
  public function reasonRefund(){
    $id = input('post.nowId');
    $orderNum = db('order')->field('order_num')->where('id',$id)->find();
    $res = db('report')->where('order_num',$orderNum['order_num'])->find();
    if($res){
      echo json_encode($this->actionSuccess($res));
    }else{
      echo json_encode($this->actionSuccess(['id'=>$id,'order_num'=>$orderNum['order_num'],'img_content'=>'','report_time'=>'','text_content'=>'']));
    }
  }
  /**
   * [cancel 拒绝退款]
   */
  public function cancel(){
    $id = input('post.nowId');
    $res = db('order')->where('id',$id)->update(['state'=>'退款失败']);
    return $res ? json_encode($this->actionSuccess($res)) : json_encode($this->actionFail('网络异常，请刷新重试'));
  }
}
