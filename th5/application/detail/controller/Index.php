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
  public function remark(){
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
  public function like(){
    $tid = getPost()['tid'];
    $res = db('vehicle')
      ->where('tab_id', $tid)
      ->limit(4)
      ->select();
    if ($res) {
      echo json_encode($this->actionSuccess($res));
    } else {
      echo json_encode($this->actionFail());
    }
  }
}
