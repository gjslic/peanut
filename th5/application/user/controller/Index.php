<?php

namespace app\user\controller;

use app\base\controller\ModuleBaseController;
use think\Controller;
use think\Db;

class Index extends ModuleBaseController
{
  
  public function get()
  {
    $errorCode = config('ErrorCode');
    $res = db('user')->select();
    if ($res) {
      echo json_encode($this->actionSuccess($res));
    } else {
      echo json_encode($this->actionFail());
    }
  }
  /**
   * [修改状态 state]
   */
  public function state()
  {
    $id = getPost()['id'];
    $res = db('user')->where('id', $id)->find();
    if ($res['state'] == '解锁') {
      $result = db('user')->where('id', $id)->update(['state' => '锁定']);
    } else{
      $result = db('user')->where('id', $id)->update(['state' => '解锁']);
    }
    if($result){
      $data = db('user')->select();
      echo json_encode($this->actionSuccess($data));
    }else {
      echo json_encode($this->actionFail());
    }
    
  }
  /**
   * [reset 重置密码]
   */
  public function reset(){
    $id = getPost()['id'];
    // 新密码
    $newPwd = md5('q11');
    
    $res = db('user')->update(['password' => $newPwd,'id'=>$id]);
    
    if($res){
      echo json_encode($this->actionSuccess([],0,'重置成功'));
    }else {
      echo json_encode($this->actionFail('重置失败,已经是初始密码'));
    }
  }
}
