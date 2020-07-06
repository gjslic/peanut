<?php

namespace app\adminLogin\controller;

use think\Controller;
use think\Db;
use think\Session;
use app\base\controller\ModuleBaseController;
use think\Request;

class Index extends ModuleBaseController
{
  private $redis = null;


  private $header = null;


  const TOKEN_EXPIRE_TIME = 7 * 24 * 60 * 60;


  public function __construct(Request $request = null)
  {
    parent::__construct($request);
    $this->redis = $this->getRedis();
    $this->header = Request::instance()->header();
  }


  private function getRedis()
  {
    $redis = new \Redis();
    $redis->connect('127.0.0.1', 6379);
    $redis->auth('123456');
    return $redis;
  }


  // 测试redis存取
  // function test(){
  //     $this->redis->set('aaa','bbb');
  //    $res =  $this->redis->get('aaa');
  //    return $res;
  // }


  public function index()
  {
    // 接收前端传的值
    $userAcc = getPost()['userName'];
    $userPwd = getPost()['password'];
    // 判断该账号登陆状态值
    $where = [
      'account' => $userAcc,
      'password' => $userPwd,
      'state' => 0,
    ];
    $resultLimt = db('staff')->where($where)->find();
    if ($resultLimt) {
      echo json_encode($this->actionFail('账号已被被锁定'));
    } else {
      // 判断该账号密码
      $where2 = [
        'account' => $userAcc,
        'password' => $userPwd
      ];
      $result = db('staff')->where($where2)->find();
      if ($result) {
        // 生成token随机数key
        $token = md5(time() + rand(0, 999999));
        // 获取该账号所有值value
        $allResult = db('staff')->where($where2)->select();
        // 设置缓存到redis
        $this->redis->set($token, json_encode($allResult));
        // 更新缓存时间
        $this->redis->expire($token, self::TOKEN_EXPIRE_TIME);
        // 获取旧token
        $oldToken = db('staff')->field('token')->where($where2)->find();
        // 删除旧token
        if ($oldToken) {
          $this->redis->del($oldToken);
        }
        // 更新新token
        $tk = [
          'token' => $token
        ];
        // redis取数据
        // $res =  $this->redis->get($token);
        // var_dump($res);
        // exit();
        $result2 = db('staff')->where($where2)->update($tk);
        // 给前端返回token
        echo json_encode($this->actionSuccess($tk, 0, '登录成功'));
      } else {
        echo json_encode($this->actionFail('账号或密码错误'));
      }
    }
  }


  // 更新缓存时间
  private function refreshToken($token)
  {
    $this->redis->expire($token, self::TOKEN_EXPIRE_TIME);
  }


  // 验证token
  public function validateToken()
  {
    
    //Request::instance()->header();
    $token = $this->header['access-token'];
    // redis取数据
    $userData = $this->redis->get($token);
    // 更新缓存时间
    $this->refreshToken($token);
    // 1、判断redis key是否存在
    $exists = $this->redis->exists($token);
    if (!$exists) {
      return json_encode($this->actionFail('redis不存在key'));
    }
    // 2、判断用户数据是否存在
    if (empty($userData)) {
      return json_encode($this->actionFail('redis不存在value'));
    }
    return json_encode($this->actionSuccess($userData, 0, '查到后台redis数据'));
  }
  // 退出
  public function delToken(){
    $token = $this->header['access-token'];
    if ($token) {
      $this->redis->del($token);
      return json_encode($this->actionSuccess([], 0, '退出成功'));
    }
  }
}
