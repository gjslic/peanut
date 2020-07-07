<?php
namespace app\admin_order\controller;

use app\base\controller\ModuleBaseController;

use think\Model;
use app\admin_order\model\OrderManage;

class Backorder extends ModuleBaseController{
    // 获取订单列表
    public function getOrderArr(){
        $type = input('post.showType');
        $endOrder = "'交易完成','退款完成','已过期','退款失败'";
        $unfinish = "'待付款','待验收','待评价','退款审核中'";
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
        // $where = '1 = 1';
        $order = db('order')->alias('o')
        ->join('peanut_user u','o.buy_id = u.id')
        ->join('peanut_vehicle v','o.vehicle_id = v.vehicle_id')
        ->field('o.id,o.order_num as orderNum,o.state,o.transaction_time as orderTime,u.acc as buyer,u.phone,u.head_img,u.name as uName,v.price,v.vehicle_name as carName,v.img as carImg')
        ->where($where)
        ->select();

        if($order){
            return json_encode($this->actionSuccess($order));
        }else{
            echo json_encode($this->actionFail('空数据'));
        }
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
     * @pramas $money => 退款
     */
    public function editState(){
        $orderId = input('post.nowId');
        $buyerAcc = input('post.buyId');
        $price = input('post.price');
        $seller = db('order o , peanut_user u')->field('u.acc,u.money')->where("o.id = '$orderId' and o.sell_id = u.id")->find();
        $buyer = db('user')->field('money')->where("acc = '$buyerAcc'")->find();
        $money = ($price * 10000) - ($price * 0.05 * 10000);
        if($seller['money'] - $money > 0){
            $editSell = db('user')->where('acc',$seller['acc'])->setDec('money',$money);
            $editbuyer = db('user')->where('acc',$buyerAcc)->setInc('money',$money);
            $creditBuyer = db('user')->where('acc',$buyerAcc)->setDec('credit',7);
            $editState = db('order')->where('id',$orderId)->setField('state','退款成功');
            echo json_encode($this->actionSuccess([],0,'退款成功'));
        }else{
            $creditSeller = db('user')->where('acc',$seller['acc'])->setDec('credit',7);
            $editState = db('order')->where('id',$orderId)->setField('state','退款失败');
            echo json_encode($this->actionFail('卖家余额不足，退款失败'));
        }
    }
}
