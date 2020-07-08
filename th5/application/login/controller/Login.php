<?php
namespace app\login\controller;
use app\base\controller\ModuleBaseController;
use think\Request;

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
        return json_encode($this->actionSuccess());
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
                echo json_encode($this->actionFail('您的账号已被锁定,请联系工作人员进行解锁'));
            }
        }else{
            echo json_encode($this->actionFail('您登录内容有误~'));
        }
    }
}
