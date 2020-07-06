<?php
namespace app\personal\controller;
use app\base\controller\ModuleBaseController;
use think\Request;

class Personal extends ModuleBaseController
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
        $this->redis->delete($oldToken);
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
    //个人中心开局获取用户信息验证
    //个人信息渲染
    public function personal(){
        $token = getPost()['token'];
        $userData = $this->redis->get($token);
        $tokenJson = json_decode ($userData);
        $tokenAdd = $tokenJson->token;
        //用户id
        $userId = $tokenJson->id;
        //更新缓存时间
        $this->refreshToken($token);
        //1、判断redis key是否存在；2、判断用户数据是否存在
        // $exists = $this->redis->exists($token);
        // if (!$exists) {
        //     echo json_encode($this->actionFail('您未登录无法进入'));
        // }
        if ($token == $tokenAdd) {
            //将用户id拿来做判断条件，找出该用户所有信息
            $userDataAdd = [
                'id' => $userId
            ];
            $userContent = db('user')->where($userDataAdd)->find ();
            //发给vue，做渲染
            echo json_encode($this->actionSuccess($userContent,1,'已有用户信息'));
        }else{
            echo json_encode($this->actionFail('您未登录无法进入'));
        }
    }
}
