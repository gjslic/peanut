<?php
namespace app\homepage\controller; //模块
use think\Db;

use app\base\controller\ModuleBaseController;
use think\Request;
// 控制器
class Homepage extends ModuleBaseController
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
    public function validateToken($token){
        //Request::instance()->header();
        $userData = $this->redis->get($token);
        //1、判断redis key是否存在；2、判断用户数据是否存在
        $exists = $this->redis->exists($token);
        if (!$exists) {
            return false;
        }
        if (empty($userData)) {
            return false;
        }
        return json_decode($userData);
    }






    //获取到用户的头像和名称
    public function getuserinfo()
    {
        $token = getPost()['usertoken'];
        $utoken = $this->validateToken($token);

        if($utoken){
            $userid = $utoken->id;

            //到数据库获取数据
            $where = [
                'id'=>$userid
            ];
            $result = db('user')->where($where)->find();
            return json_encode($result);
            // var_dump($result);
            // exit();
        }
    }

    //获取到城市
    public function getcity()
    {
        $get = $_GET;
        $where = [];
        $result = db('city')->where($where)->select();
        return json_encode($result);
    }
    // 获取到车标方法
    public function carbran()
    {
        $get = $_GET;
       $where = [];
       $result = db('brand')->where($where)->select();
       return json_encode($result);
    }

    //获取到价格范围
    public function carprice()
    {
        $get = $_GET;
        $where = [];
        $result = db('price')->where($where)->select();
        return json_encode($result);
    }

     //获取到车辆类型
     public function carstyle()
     {
         $get = $_GET;
         $where = [];
         $result = db('style')->where($where)->select();
         return json_encode($result);
     }

     //点击获取到推荐车辆
     public function reccar()
     {
         //获取到传来的参数
        $tab_num = getPost()['tab_num'];

        //判断参数
        switch($tab_num){
            case 'first':
            $where =[
                'tab_id' =>1,
            ];
            $result = db('vehicle')->where($where)->limit(8)->select();
            return json_encode($result);
        break;

        case 'second':
            $where =[
                'tab_id' =>2,
            ];
            $result = db('vehicle')->where($where)->limit(8)->select();
            return json_encode($result);
        break;

        case 'third':
            $where =[
                'tab_id' =>3,
            ];
            $result = db('vehicle')->where($where)->limit(8)->select();
            return json_encode($result);
        break;

        case 'fourth':
            $where =[
                'tab_id' =>4,
            ];
            $result = db('vehicle')->where($where)->limit(8)->select();
            return json_encode($result);
        break;

        case 'fifth':
            $where =[
                'tab_id' =>5,
            ];
            $result = db('vehicle')->where($where)->limit(8)->select();
            return json_encode($result);
        break;
        }
     }

     //开始获取推荐车辆
     public function getreccar()
     {
         $get = $_GET;
         $where = [];
         $result = db('vehicle')->where($where)->limit(8)->select();
        return json_encode($result);
     }
    
     //传递车标
     public function passbrand()
     {
        //获取到传来的参数
        //到车辆表比对车标id
        $brandid = getPost()['brandid'];
  
        $result = 
        Db::table('peanut_vehicle')
        ->alias('v')
        ->join('peanut_series s','v.series_id = s.series_id')
        ->where('brand_id',$brandid)
        ->select();

        if($result){
            return json_encode($result);
        }
        // 路由跳轉
        Route::get('view',function(){
            return view('视图模板名称');
        });
            
     }

     //传递推荐车辆
     public function passReccar()
     {
        //获取到传来的参数
        //到车辆表比对车标id
        $reccarid = getPost()['reccarid'];

        $where = [
            'tab_id' => $reccarid
        ];
        $result = db('vehicle')->where($where)->select();
        
        return json_encode($result);

     }

     //传递城市
     public function passCity()
     {
        $passCityid = getPost()['passCityid'];

        
        $where = [
            'city_id' => $passCityid
        ];
        $result = db('vehicle')->where($where)->select();
        
        return json_encode($result);
     }

     //買車頁城市
     public function getsellingCity(){
        $get = $_GET;
        $where = [];
        $result = db('city')->where($where)->select();
        return json_encode($result);
     }

     //卖车页品牌
     public function getsellingbrand()
     {
        $get = $_GET;
        $where = [];
        $result = db('brand')->where($where)->select();
        return json_encode($result);
     }
}