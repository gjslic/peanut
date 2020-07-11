<?php

namespace app\detail\controller;

use app\base\controller\ModuleBaseController;
use SQL;
use think\Controller;
use think\Db;

class Index extends ModuleBaseController
{
  /**
   * 汽车详情信息
   */
  public function get()
  {
    $id = getPost()['id'];
    $res = db('vehicle')
      ->alias('v')
      ->join('city c', 'v.city_id = c.city_id')
      ->join('user u', 'u.id = v.sell_id')
      ->where('v.vehicle_id', $id)
      ->select();
    if ($res) {
      echo json_encode($this->actionSuccess($res));
    } else {
      echo json_encode($this->actionFail());
    }
  }
  /**
   * 得到评价表
   */
  public function remark()
  {
    $uid = getPost()['uid'];
    $res = db('comment')
      ->alias('c')
      ->join('user u', 'u.id = c.user_id')
      ->where('c.sell_id', $uid)
      ->select();
    if ($res) {
      echo json_encode($this->actionSuccess($res));
    } else {
      echo json_encode($this->actionFail());
    }
  }
  /**
   * like[猜你喜欢]
   */
  public function like()
  {
    $tid = getPost()['tid'];
    $res = db('vehicle')
      ->where('tab_id', $tid)
      ->where('vehicle_state', '已上架')
      ->limit(4)
      ->select();
    if ($res) {
      echo json_encode($this->actionSuccess($res));
    } else {
      echo json_encode($this->actionFail());
    }
  }
  /**
   * [order下单判断钱]
   */

  public function order()
  {
    $uid = getPost()['uid']; //卖家
    $vid = getPost()['vid']; //汽车
    $userId = getPost()['userId']; //买家
    $price = getPost()['price'];
    // q取出钱
    $res = db('user')->field('money')->where('id', $userId)->select();

    if ($res) {
      if ($res[0]['money'] > ($price * 10000)) {
        // var_dump($res[0]['money']);
        // exit;


        //订单数据
        $orderData = [
          'order_num' => "HS" . time() . mt_rand(1, 100),
          'sell_id' => $uid,
          'buy_id' => $userId,
          'vehicle_id' => $vid,
          'transaction_time' => date('Y-m-d H:i:s', time()),
          'state' => '待验收'
        ];
        // print_r($orderData) ; die;
        $result = db('order')->insert($orderData);


        if ($result) {
          // 扣钱
          $surplusMoney = $res[0]['money'] - ($price * 10000);
          // var_dump($res[0]['money']);exit;
          // $sql = "UPDATE peanut_user SET money={$surplusMoney} WHERE id = {$userId}";
          // $lastMoney = Db::query($sql);
          $lastMoney = db('user')->update(['money' => $surplusMoney, 'id' => $userId]);

          if ($lastMoney) {
            // 下架商品
            $lastState =  db('vehicle')->where('vehicle_id', $vid)->update(['vehicle_state' => '已下架', 'buy_id' => $userId]);
            // var_dump($lastState);exit;
            echo json_encode($this->actionSuccess([], 0, '下单成功，前往个人中心查看吧！'));
          } else {
            echo json_encode($this->actionFail());
          }
        } else {
          echo json_encode($this->actionFail());
        }
      } else {
        echo json_encode($this->actionFail('下单失败，您的余额不足，请前往个人中心充值'));
      }
    }
  }
  /**
   * isloginAnd是否收藏
   */
  public function isloginAnd(){
    $userId = getPost()['id']; //买家
    $vid = getPost()['vid']; //汽车
    $res = db('collection')
      ->where('user_id', $userId)
      ->where('vehicle_id', $vid)
      ->find();
    if($res){
      echo json_encode($this->actionSuccess());
    }else{
      echo json_encode($this->actionFail());
    }
  }
  /**
   * [collect收藏]
   */
  public function collect()
  {
    $vid = getPost()['vid']; //汽车
    $userId = getPost()['userId']; //买家
    $isCollect = getPost()['isCollect'];
    $uid = getPost()['uid']; //卖家
    // 点击收藏
    if ($isCollect) {
      $data = ['vehicle_id' => $vid, 'user_id' => $userId];
      $res = db('collection')->insert($data);
      // 添加收藏分类表
      $findRes = db('collection')
        ->where('user_id', $userId)
        ->where('vehicle_id', $vid)
        ->find();
      $classRes = db('collection_category')->insert(['collection_id' => $findRes['id'], 'sell_id' => $uid]);
        
      if ($res && $classRes) {
        echo json_encode($this->actionSuccess([], 0, '收藏成功!'));
      } else {
        echo json_encode($this->actionFail('收藏失败!'));
      }
    } else {
      $findRes = db('collection')
        ->where('user_id', $userId)
        ->where('vehicle_id', $vid)
        ->find();
      // 取消收藏
      $result = db('collection')
        ->where('user_id', $userId)
        ->where('vehicle_id', $vid)
        ->delete();
      $resultClass = db('collection_category')
        ->where('collection_id',$findRes['id'])
        ->delete();
      //  删除分类收藏
      if ($result && $resultClass) {
        echo json_encode(['msg' => '取消成功!', 'code' => 2]);
      } else {
        echo json_encode($this->actionFail('取消失败!'));
      }
    }
  }
  /**
   * [getUser用户信息]
   */
  public function getUser(){
    $id = getPost()['id']; 
    $res = db('user')->where('id',$id)->find();
    if ($res) {
      echo json_encode($this->actionSuccess($res));
    } else {
      echo json_encode($this->actionFail());
    }
  }
}
