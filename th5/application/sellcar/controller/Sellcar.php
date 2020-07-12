<?php
namespace app\sellcar\controller; //模块
use think\Db;

use app\base\controller\ModuleBaseController;
use think\Request;

class Sellcar extends ModuleBaseController{

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

       //接收数据
       public function sellcardata()
       {
           $imgUrl = getPost()['imgUrl'];
           $ruleForm = getPost()['ruleForm'];
           $usertoken = getPost()['usetoken'];
           $auctiontime = getPost()['auctiontime'];
           $utoken = $this->validateToken($usertoken);

           if($utoken){
               $userid = $utoken->id;
            //    $auctiontime = $ruleForm['auction'];
               if($auctiontime == 0){
                    // 添加到车辆表
                    $carinfo=[
                        'city_id' => $ruleForm['name'], //城市id
                        'series_id' => $ruleForm['brand'][1], //系列id
                        'vehicle_distance' => $ruleForm['num'], //行驶距离
                        'introduce' => $ruleForm['resource'], //车辆状况
                        'price' => $ruleForm['salePrice'], //车辆价格
                        'img' => $imgUrl, // 车辆图片
                        'vehicle_state' => '已上架', //拍卖状态
                        'sell_id' => $userid ,//卖家id
                        'vehicle_name' => $ruleForm['carname'], //车辆名称信息
                        'tab_id' => $ruleForm['tab'],//车辆标签
                    ];
                    $res = db('vehicle')->insert($carinfo);
               }else{
                     // 添加到车辆表
                     $carinfo=[
                        'city_id' => $ruleForm['name'], //城市id
                        'series_id' => $ruleForm['brand'][1], //系列id
                        'vehicle_distance' => $ruleForm['num'], //行驶距离
                        'introduce' => $ruleForm['resource'], //车辆状况
                        'price' => $ruleForm['salePrice'], //车辆价格
                        'img' => $imgUrl, // 车辆图片
                        'vehicle_state' => '未审核', //拍卖状态
                        'sell_id' => $userid ,//卖家id
                        'vehicle_name' => $ruleForm['carname'], //车辆名称信息
                        'tab_id' => $ruleForm['tab'],//车辆标签
                        'auction_id'=>$ruleForm['auction']
                    ];
                    $res = db('vehicle')->insert($carinfo);
               }
             
           }
       }

    
    //上传图片到本地
    public function uploadimg()
    {
        //获取表单上传文件
        $file = request()->file('image');

        //放置框架应用根目录uploads下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $url = 'http://127.0.0.1/th5/public/uploads/';
                echo json_encode($url . $info->getSaveName());
            }else{
                //上传失败
                echo $file->getError();
            }
        }
    }

    //获取到城市
    public function getsellcity()
    {
        $get = $_GET;
        $where = [];
        $result = db('city')->where($where)->select();
        return json_encode($result);
    }

    //获取到品牌
    public function getSeries()
    {
      $series = db('series')->field('series_id as value,series_name as label,brand_id')->select();
      $brand = db('brand')->field('brand_id as value,brand_name as label')->select();
      if ($series && $brand) {
        echo json_encode(['series' => $series, 'brand' => $brand]);
      } else {
        echo json_encode($this->actionFail());
      }
    }

    //获取到车辆标签信息
    public function getcartab()
    {
        $get = $_GET;
        $where = [];
        $result = db('tab')->where($where)->select();
        return json_encode($result);
    }

    //获取到拍卖场次
    public function showaction()
    {
        $get = $_GET;
        $where = [];
        $result = db('auction')->where($where)->select();
        return json_encode($result);
    }

 

 
}
