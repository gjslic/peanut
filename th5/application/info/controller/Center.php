<?php

namespace app\info\controller;
use think\Controller;
use app\alogin\controller\Index;
use SQL;
use think\console\output\descriptor\Console;
use think\Db;

class Center extends Index
{
  
  /**
   * [获取员工信息]
   */
  public function get()
  {
    $token = getPost()['token'];
    // redis取数据
    $data = json_decode($this->redis->get($token)) ; 
    $id = $data[0]->id;
    $res = db('staff')
      ->alias('s')
      ->join('role r', 'r.id = s.role_id')
      ->where('s.id', $id)
      ->field('s.*,r.role_name')
      ->select();
    if ($res) {
      echo json_encode($this->actionSuccess($res));
    } else {
      echo json_encode($this->actionFail());
    }
  }
  /**
   * editName[修改姓名]
   */
  public function editName(){
    $id = getPost()['id'];
    $name = getPost()['name'];
    $res = db('staff')
    ->where('id', $id)
    ->data(['name' => $name])
    ->update();
    
    if ($res) {
      echo json_encode($this->actionSuccess());
    } else {
      echo json_encode($this->actionFail());
    }
  }
  /**
   * [editPhone修改手机号]
   */
  public function editPhone(){
    $id = getPost()['id'];
    $phone = getPost()['phone'];
    $res = db('staff')
    ->where('id', $id)
    ->data(['phone' => $phone])
    ->update();
    
    if ($res) {
      echo json_encode($this->actionSuccess());
    } else {
      echo json_encode($this->actionFail());
    }
  }
  /**
   * [editPass修改密码]
   */
  public function editPass(){
    $id = getPost()['id'];
    $pass = md5(getPost()['pass']);
    $res = db('staff')
    ->where('id', $id)
    ->data(['password' => $pass])
    ->update();
    
    if ($res) {
      echo json_encode($this->actionSuccess());
    } else {
      echo json_encode($this->actionFail());
    }
  }
  /**
   * [修改性别editSex]
   */
  public function editSex(){
    $id = getPost()['id'];
    $sex = getPost()['sex'];
    $res = db('staff')
    ->where('id', $id)
    ->data(['sex' => $sex])
    ->update();
    
    if ($res) {
      echo json_encode($this->actionSuccess());
    } else {
      echo json_encode($this->actionFail());
    }
  }
  /**
   * [editAvatar修改头像]
   */
  public function editAvatar(){
    $id = getPost()['id'];
    $imgUrl = getPost()['imgUrl'];
    $res = db('staff')
    ->where('id', $id)
    ->data(['head_img' => $imgUrl])
    ->update();
    
    if ($res) {
      echo json_encode($this->actionSuccess());
    } else {
      echo json_encode($this->actionFail());
    }
  }
  /**
   * getMenu渲染权限菜单
   */
  public function getMenu(){
    $rid = getPost()['rid'];
    $res = db('jurisdiction')
    ->where('role_id',$rid)
    ->select();
    $menu = db('menu')->select();
    if ($res && $menu) {
      echo json_encode(['menu'=>$menu,'data'=>$res,'code'=>1]);
    } else {
      echo json_encode($this->actionFail());
    }
  }
  /**
   * [chat聊天]
   */
  public function chat(){
    $sql = "select * from peanut_chat where receiver='adminServer' or sender='adminServer'";
    $res = Db::query($sql);
    if ($res) {
      echo json_encode($this->actionSuccess($res));
    } else {
      echo json_encode($this->actionFail());
    }
  }
}