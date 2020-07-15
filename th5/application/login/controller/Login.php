<?php
namespace app\login\controller;
use app\base\controller\ModuleBaseController;
use think\Request;
// use app\login\controller\WXBizMsgCrypt;

class Login extends ModuleBaseController
{   
    /**
     * redis
     * @var null
     */
    private $redis = null;

    /**
     * header数据
     * @var null
     */
    private $header = null;

    /**
     * token时长
     */
    const TOKEN_EXPIRE_TIME = 7 * 24 * 60 * 60;

    /**
     * 构造函数
     * Index constructor.
     * @param Request|null $request
     */
    public function __construct(Request $request = null){
        parent::__construct($request);
        $this->redis = $this->getRedis();
        $this->header = Request::instance()->header();
    }

    /**
     * 获取redis
     * @return \Redis
     */
    private function getRedis(){
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->auth('123456');
        return $redis;
    }

    /**
     * 获取token
     * @return string
     */
    // public function getToken()//login 操作
    // {
    //     $token = md5(time() + rand(0, 999999));
    //     $this->setToken($token);//保存到数据库
    //     $this->deleteOldToken($oldToken);//oldToken从数据获取
    //     $res = [
    //         'token' => $token,
    //     ];
    //     return json_encode($res);
    // }

    /**
     * 设置缓存到redis
     * @param $token
     */
    private function setToken($token,$res)
    {
        $this->redis->set($token, $res);
        $this->redis->set($token, $res);
        $this->redis->expire($token, self::TOKEN_EXPIRE_TIME);
    }
    
    /**
     * 更新缓存时间
     * @param $token
     */
    private function refreshToken($token)
    {
        $this->redis->expire($token, self::TOKEN_EXPIRE_TIME);
    }

    /**
     * 删除旧token
     * @param $oldToken
     */
    private function deleteOldToken($oldToken)
    {
        $this->redis->del($oldToken);
    }

    /**
     * 验证token
     * @return false|string
     */
    public function validateToken()
    {
        //Request::instance()->header();
        $token = $this->header['access-token'];
        $userData = $this->redis->get($token);
        $this->refreshToken($token);
        //1、判断redis key是否存在；2、判断用户数据是否存在
        $exists = $this->redis->exists($token);
        if (!$exists) {
            return json_encode($this->actionFail());
        }
        if (empty($userData)) {
            return json_encode($this->actionFail());
        }
        return json_encode($this->actionSuccess($userData));
    }

    /**
     * 用户登录
     */
    public function login(){
        //账号
        $acc = getPost()['acc'];
        //手机号
        $phone = getPost()['phone'];
        //密码
        $password = md5(getPost()['password']);
        //最后一次的登录时间
        $lastTime = date("Y-m-d h:i:s");
        //用户所有登录信息
        $where = [
            'acc' => $acc,
            'phone' => $phone,
            'password' => $password
        ];
        //数据库查找相对信息
        $result = db('user') ->where($where)->find ();
        if($result){
            if($result["state"]=='解锁'){
                if($result["credit"] > 80){
                    $userId = $result["id"];
                    //创建一个token
                    $token = md5(time() + rand(0, 999999));
                    $data =[
                        'id' => $userId,
                        'token' => $token   
                    ];
                    $this->setToken($token,json_encode($data));//设置缓存到redis
                    //旧token
                    $oldToken = $result["token"];
                    //找到服务器旧的token，有就删了
                    if($oldToken){
                        $this->deleteOldToken($oldToken);//oldToken从数据获取，删除
                    }
                    //新token
                    $res = [
                        'token' => $token
                    ];
                    //新登录时间,新token存入数据库
                    $newTimeToken = [
                        'last_time' => $lastTime,
                        'token' => $token
                    ];
                    //将时间,新token存进去
                    $userTime = db('user')->where('phone',$phone)->update($newTimeToken);
            
                    echo json_encode($this->actionSuccess($res,1,'登录成功~快去看车吧~'));
                }else{
                    echo json_encode($this->actionFail('您的账号信誉分在80以下,无法登录'));
                }
            }else{
                echo json_encode($this->actionFail('您的账号已被锁定,请联系工作人员进行解锁'));
            }
        }else{
            echo json_encode($this->actionFail('您登录内容有误~'));
        }
    }
    /**
     * [getCode 获取用户个人信息]
     */
    public function getCode(){
       // 前台参数
       $encryptedData = getPost()['encryptedData'];
       $code          = getPost()['code'];
       $iv            = getPost()['iv'];

       // 小程序 appid 和 appsecret
       $appid     = 'wx888ca9a5467e0ed7';
       $appsecret = '2ad88466f0112b2342323d78e45db173';

       // step1
       // 通过 code 用 curl 向腾讯服务器发送请求获取 session_key
       $session_key = $this->sendCode($appid, $appsecret, $code);

       // step2
       // 用过 session_key 用 sdk 获得用户信息
       $save = [];

       // 相关参数为空判断
       if (empty($session_key) || empty($encryptedData) || empty($iv)) {
           $msg = "信息不全";
           return json_encode($this->actionSuccess($save,0,$msg));
       }

       //进行解密 (获取session_key openid)
       $userinfo = $this->getUserInfo($encryptedData, $iv, $session_key, $appid);
       var_dump($userinfo);
       exit;
       // 解密成功判断
       if (isset($userinfo['code']) && 10001 == $userinfo['code']) {
           $msg = "请重试"; // 用户不应看到程序细节
           return json_encode($this->actionSuccess($save,0,$msg));
       }

       session('myinfo', $userinfo);
       $save['openid']    = &$userinfo['openId'];
       $save['uname']     = &$userinfo['nickName'];
       $save['unex']      = &$userinfo['gender'];
       $save['address']   = &$userinfo['city'];
       $save['avatarUrl'] = &$userinfo['avatarUrl'];
       $save['time']      = time();
       $map['openid']     = &$userinfo['openId'];

       $msg = "获取成功";

       //返回用户信息
       return json_encode($this->actionSuccess($save,0,$msg));
    }

  //获取微信用户信息
  private function sendCode($appid, $appsecret, $code){
      // 拼接请求地址
      $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='
          . $appid . '&secret=' . $appsecret . '&js_code='
          . $code . '&grant_type=authorization_code';

      $arr = $this->httpRequest($url);
      $arr = json_decode($arr, true);
      // var_dump($arr);
      // exit;
      return $arr['session_key'];
  }
    
       //信息解密
  private function getUserInfo($encryptedData, $iv, $session_key, $APPID){
    Vendor('phpSDK.wxBizDataCrypt');
    //进行解密
    $pc         = new \wxBizDataCrypt($APPID, $session_key);
    $decodeData = "";
    $errCode    = $pc->decryptData($encryptedData, $iv, $decodeData);
    //判断解密是否成功
    if ($errCode != 0) {
        return [
            'code'    => 10001,
            'message' => 'encryptedData 解密失败',
        ];
    }
    //返回解密数据
    return json_decode($decodeData, true);
  }

  // 获取用户手机号
  public function getPhoneNumber(){
    var_dump($_POST);
  }

    function httpRequest($url,$type = 'GET',$postData = []){
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_HEADER, 0);
      curl_setopt($curl, CURLOPT_VERBOSE, 0);
      curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
      curl_setopt($curl,CURLOPT_URL,$url); 
      curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
      curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
      curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
      curl_setopt($curl,CURLOPT_POST,1);
      curl_setopt($curl,CURLOPT_POSTFIELDS,$postData);
      $result = curl_exec($curl);
      curl_close($curl);
      return $result;
    }
}
