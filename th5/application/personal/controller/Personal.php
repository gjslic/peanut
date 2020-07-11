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
    // public function validateToken()
    // {
    //     //Request::instance()->header();
    //     $token = $this->header['access-token'];
    //     $userData = $this->redis->get($token);
    //     $this->refreshToken($token);
    //     //1、判断redis key是否存在；2、判断用户数据是否存在
    //     $exists = $this->redis->exists($token);
    //     if (!$exists) {
    //         return json_encode($this->actionFail());
    //     }
    //     if (empty($userData)) {
    //         return json_encode($this->actionFail());
    //     }
    //     return json_encode($this->actionSuccess());
    // }
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
    //个人中心开局获取用户信息验证
    //个人信息渲染
    public function personal(){
        $token = getPost()['token'];
        //使用分装好的验证方法
        $userData = $this->validateToken($token);
        //1、判断redis key是否存在；2、判断用户数据是否存在
        if($userData){
            $tokenAdd = $userData->token;
            //用户id
            $userId = $userData->id;
            //更新缓存时间
            $this->refreshToken($token);
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
    //个人中心修改充值密码
    public function rechargeClick(){
        //账号
        $acc = getPost()['acc'];
        //密码
        $password = md5(getPost()['password']);
        //新充值密码
        $recharge = md5(getPost()['recharge']);
        //找用户id
        $token = getPost()['token'];
        $userData = $this->redis->get($token);
        $tokenJson = json_decode ($userData);
        $tokenAdd = $tokenJson->token;
        //用户id
        $userId = $tokenJson->id;
        //判断
        $user = [
            'id' => $userId
        ];
        //新充值密码存入数据库
        $data = [
            'recharge' => $recharge
        ];
        //数据库查找相对信息
        $userAdd = db('user') ->where($user)->find ();
        //判断用户是否存在
        if($userAdd){
            //存进去
            $result = db('user')->where('id',$userId)->update($data);
            if($result){
                echo json_encode($this->actionSuccess($userAdd,1,'恭喜您~修改充值密码成功~'));
            }else{
                echo json_encode($this->actionFail('充值密码相同,无法修改')); 
            }
        }else{
            echo json_encode($this->actionFail('账号密码有误,无法修改')); 
        }
    }
    //个人中心充值系统
    public function onSubmitClick(){
        $token = getPost()['token'];
        $money = getPost()['money'];
        $recharge = md5(getPost()['recharge']);
        $userData = $this->redis->get($token);
        $tokenJson = json_decode ($userData);
        //用户id
        $userId = $tokenJson->id;
        $user = [
            'id' => $userId
        ];
        $userdb = db('user')->where($user)->find ();
        if($userdb['recharge'] == $recharge){
            $oldMoney = json_decode($userdb['money']);
            $newMoney = json_decode($money);
            $moneyAdd = ($oldMoney + $newMoney);
            if($moneyAdd <= 999999999999){
                //存钱
                $userMoney = db('user')->where('id',$userId)->update(['money' => $moneyAdd]);
                if($userMoney){
                    echo json_encode($this->actionSuccess($moneyAdd,1,'恭喜你~充值成功~'));        
                }else{
                    echo json_encode($this->actionFail('充值失败'));
                }
            }else{
                echo json_encode($this->actionFail('请花完钱再冲,不然太浪费了~'));
            }
        }else{
            echo json_encode($this->actionFail('您未输入充值密码或充值密码不正确~'));      
        }
    }
    //个人中心修改密码
    public function change(){
        //账号
        $acc = getPost()['acc'];
        //密码
        $password = md5(getPost()['password']);
        //新密码
        $newPassword = md5(getPost()['newPassword']);
        //找用户id
        $token = getPost()['token'];
        $userData = $this->redis->get($token);
        $tokenJson = json_decode ($userData);
        $tokenAdd = $tokenJson->token;
        //用户id
        $userId = $tokenJson->id;
        //新密码存入数据库
        $data = [
            'password' => $newPassword
        ];
        //数据库查找相对信息
        $user = db('user') ->where(['id' => $userId])->find ();
        //判断旧密码是否一致
        if($user['password'] == $password){
            //判断
            $result = db('user')->where('id',$userId)->update($data);
            if($result){ 
                echo json_encode($this->actionSuccess($data,1,'恭喜您~修改成功~下次别忘咯~'));
            }else{
                echo json_encode($this->actionFail('改密码和您原本密码相同,无法修改'));
            }
        }else{
            echo json_encode($this->actionFail('您的旧密码输入错误'));
        } 
    }
    //个人中心修改性别
    public function gender(){
        //找用户id
        $token = getPost()['token'];
        $userData = $this->redis->get($token);
        $tokenJson = json_decode ($userData);
        $tokenAdd = $tokenJson->token;
        //用户id
        $userId = $tokenJson->id;
        $sex = getPost()['sex'];
        //换性别
        $userSex = db('user')->where('id',$userId)->update(['sex' => $sex]);
        if($userSex){
            echo json_encode($this->actionSuccess($sex,1,'恭喜你~修改性别成功~'));    
        }else{
            echo json_encode($this->actionFail('修改性别失败~'));
        }
    }
    //修改地址
    public function address(){
        //找用户id
        $token = getPost()['token'];
        $userData = $this->redis->get($token);
        $tokenJson = json_decode ($userData);
        $tokenAdd = $tokenJson->token;
        //用户id
        $userId = $tokenJson->id;
        $add = getPost()['add'];
        //换地址
        $userAdd = db('user')->where('id',$userId)->update(['add' => $add]);
        if($userAdd){
            echo json_encode($this->actionSuccess($add,1,'恭喜你~修改地址成功~'));    
        }else{
            echo json_encode($this->actionFail('修改地址失败~'));
        }
    }
    //头像地址
    public function avatar(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info) {
                $url = 'http://127.0.0.1/th5/public/uploads/';
                echo json_encode($url . $info->getSaveName());
            } else {
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }
    //头像修改
    public function avatarClick(){
        //找用户id
        $token = getPost()['token'];
        $userData = $this->redis->get($token);
        $tokenJson = json_decode ($userData);
        $tokenAdd = $tokenJson->token;
        //用户id
        $userId = $tokenJson->id;
        $head_img = getPost()['head_img'];
        if($head_img == ''){
            echo json_encode($this->actionFail('您未上传头像~'));
        }else{
            //换头像
            $userAdd = db('user')->where('id',$userId)->update(['head_img' => $head_img]);
            if($userAdd){
                echo json_encode($this->actionSuccess($head_img,1,'恭喜你~修改头像成功~'));    
            }else{
                echo json_encode($this->actionFail('修改头像失败~'));
            }
        }
    }
    //打印收藏
    public function collection(){
        //找用户id
        $token = getPost()['token'];
        $userData = $this->redis->get($token);
        $tokenJson = json_decode ($userData);
        $tokenAdd = $tokenJson->token;
        //用户id
        $userId = $tokenJson->id;
        $res = db('collection_category')
        ->alias('o')
        ->join('collection c','o.collection_id = c.id')
        ->join('user u','o.sell_id = u.id')
        ->join('vehicle v','c.vehicle_id = v.vehicle_id')
        ->field('o.id o_id,v.*,u.*,o.*,c.*')
        ->where('c.user_id',$userId)
        ->select();
        if($res){
            echo json_encode($this->actionSuccess($res,1,'查找用户收藏内容成功'));
        }else{
            echo json_encode($this->actionFail('查找用户收藏失败~'));
        }
    }
    //取消收藏
    public function cancel(){
        //找用户id
        $token = getPost()['token'];
        $userData = $this->redis->get($token);
        $tokenJson = json_decode ($userData);
        $tokenAdd = $tokenJson->token;
        //用户id
        $userId = $tokenJson->id;
        //收藏id
        $collectionId = getPost()['collection_id'];
        //车辆id
        $vehicleId = getPost()['vehicle_id'];
        //删除收藏表是该用户并且是这辆车的内容
        
        $Collection = [
            'collection_id' => $collectionId
        ];
        $categoryId = db('collection_category')->where($Collection)->delete();
        $deleteCollection = [
            'id' => $collectionId,
            'user_id' => $userId,
            'vehicle_id' => $vehicleId
            
        ];
        $collectionId = db('collection')->where($deleteCollection)->delete();
        if($categoryId){
            $res = db('collection_category')
            ->alias('o')
            ->join('collection c','o.collection_id = c.id')
            ->join('vehicle v','c.vehicle_id = v.vehicle_id')
            ->join('user u','o.sell_id = u.id')
            ->where('c.user_id',$userId)
            ->select();
            echo json_encode($this->actionSuccess($res,1,'取消收藏成功'));
        }else{
            echo json_encode($this->actionFail('取消收藏失败~'));
        }

    }
    //打印买的车
    public function peanutOrder(){
        //找用户id
        $token = getPost()['token'];
        $userData = $this->redis->get($token);
        $tokenJson = json_decode ($userData);
        $tokenAdd = $tokenJson->token;
        //用户id
        $userId = $tokenJson->id;
        $res = db('order')
        ->alias('o')
        ->join('user u','o.sell_id = u.id')
        ->join('vehicle v','o.vehicle_id = v.vehicle_id')
        ->field('o.state o_state,v.*,u.*,o.*')
        ->where('o.buy_id',$userId)
        ->select();
        if($res){
            echo json_encode($this->actionSuccess($res,1,'查找用户买车内容成功'));
        }else{
            echo json_encode($this->actionFail('查找用户买车失败~'));
        }
    }
    //带验收
    public function primary(){
        //找用户id
        $token = getPost()['token'];
        $userData = $this->redis->get($token);
        $tokenJson = json_decode ($userData);
        $tokenAdd = $tokenJson->token;
        //用户id
        $userId = $tokenJson->id;
        //订单号
        $order_num = getPost()['order_num'];
        //卖家id
        $sell_id = getPost()['sell_id'];
        //买家id
        $buy_id = getPost()['buy_id'];
        //车辆id
        $vehicle_id = getPost()['vehicle_id'];
        //判断用户
        if($buy_id == $userId){
            //换状态
            $res = db('order')->where('buy_id', $buy_id)->where('sell_id', $sell_id)->where('vehicle_id', $vehicle_id)->where('order_num', $order_num)->update(['state' => '待评价']);
            if($res){
                $data = db('order')
                ->alias('o')
                ->join('user u','o.sell_id = u.id')
                ->join('vehicle v','o.vehicle_id = v.vehicle_id')
                ->field('o.state o_state,v.*,u.*,o.*')
                ->where('o.buy_id',$userId)
                ->select();
                echo json_encode($this->actionSuccess($data,1,'验收车辆成功'));
            }else{
                echo json_encode($this->actionFail('验收车辆失败~'));
            }
        }else{
            echo json_encode($this->actionFail('用户id不正确'));
        }

        
    }
    //带退货
    public function danger(){
        //找用户id
        $token = getPost()['token'];
        $userData = $this->redis->get($token);
        $tokenJson = json_decode ($userData);
        $tokenAdd = $tokenJson->token;
        //用户id
        $userId = $tokenJson->id;
        //订单号
        $order_num = getPost()['order_num'];
        //卖家id
        $sell_id = getPost()['sell_id'];
        //买家id
        $buy_id = getPost()['buy_id'];
        //车辆id
        $vehicle_id = getPost()['vehicle_id'];
        //判断用户
        if($buy_id == $userId){
            //换状态
            $res = db('order')->where('buy_id', $buy_id)->where('sell_id', $sell_id)->where('vehicle_id', $vehicle_id)->where('order_num', $order_num)->update(['state' => '退款审核中']);
            if($res){
                $data = db('order')
                ->alias('o')
                ->join('user u','o.sell_id = u.id')
                ->join('vehicle v','o.vehicle_id = v.vehicle_id')
                ->field('o.state o_state,v.*,u.*,o.*')
                ->where('o.buy_id',$userId)
                ->select();
                echo json_encode($this->actionSuccess($data,1,'正在为您退款审核中'));
            }else{
                echo json_encode($this->actionFail('退款审核失败~'));
            }
        }else{
            echo json_encode($this->actionFail('用户id不正确'));
        }

        
    }
    //评价表
    public function evaluateClick(){
        //找用户id
        $token = getPost()['token'];
        $userData = $this->redis->get($token);
        $tokenJson = json_decode ($userData);
        $tokenAdd = $tokenJson->token;
        //用户id
        $userId = $tokenJson->id;
        //订单号
        $order_num = getPost()['order_num'];
        //卖家id
        $sell_id = getPost()['sell_id'];
        //买家id
        $buy_id = getPost()['buy_id'];
        //车辆id
        $vehicle_id = getPost()['vehicle_id'];
        //评价内容
        $content = getPost()['content'];
        //评价分数
        $comment_num = getPost()['comment_num'];
        //判断用户
        if($buy_id == $userId){
            //换状态
            $res = db('order')->where('buy_id', $buy_id)->where('sell_id', $sell_id)->where('vehicle_id', $vehicle_id)->where('order_num', $order_num)->update(['state' => '交易完成']);
            if($res){
                $data = db('order')
                ->alias('o')
                ->join('user u','o.sell_id = u.id')
                ->join('vehicle v','o.vehicle_id = v.vehicle_id')
                ->field('o.state o_state,v.*,u.*,o.*')
                ->where('o.buy_id',$userId)
                ->select();
                if($data){
                    //存入评价数据库
                    $content = [
                        'user_id' => $buy_id,
                        'sell_id' => $sell_id,
                        'content' => $content,
                        'comment_num' =>$comment_num
                    ];
                    //存评价
                    $result = db('comment')->insert($content);
                    if($result){
                        echo json_encode($this->actionSuccess($data,1,'感谢您献上宝贵的评价'));
                    }else{

                    }
                }else{
                    echo json_encode($this->actionFail('评价失败~'));
                }  
            }else{
                echo json_encode($this->actionFail('评价失败~'));
            }
        }else{
            echo json_encode($this->actionFail('用户id不正确'));
        }
    }
    //完成交易
    public function successClick(){
        //找用户id
        $token = getPost()['token'];
        $userData = $this->redis->get($token);
        $tokenJson = json_decode ($userData);
        $tokenAdd = $tokenJson->token;
        //用户id
        $userId = $tokenJson->id;
        //订单号
        $order_num = getPost()['order_num'];
        //卖家id
        $sell_id = getPost()['sell_id'];
        //买家id
        $buy_id = getPost()['buy_id'];
        //车辆id
        $vehicle_id = getPost()['vehicle_id'];
        //判断用户
        if($buy_id == $userId){
            //换状态
            $res = db('order')->where('buy_id', $buy_id)->where('sell_id', $sell_id)->where('vehicle_id', $vehicle_id)->where('order_num', $order_num)->update(['state' => '交易完成']);
            if($res){
                $data = db('order')
                ->alias('o')
                ->join('user u','o.sell_id = u.id')
                ->join('vehicle v','o.vehicle_id = v.vehicle_id')
                ->field('o.state o_state,v.*,u.*,o.*')
                ->where('o.buy_id',$userId)
                ->select();
                echo json_encode($this->actionSuccess($data,1,'恭喜你~完成交易啦'));
            }else{
                echo json_encode($this->actionFail('完成交易失败~'));
            }
        }else{
            echo json_encode($this->actionFail('用户id不正确'));
        }
    }
    //渲染卖车页
    public function sellingCars(){
        //找用户id
        $token = getPost()['token'];
        $userData = $this->redis->get($token);
        $tokenJson = json_decode ($userData);
        $tokenAdd = $tokenJson->token;
        //用户id
        $userId = $tokenJson->id;
        $res = db('order')
        ->alias('o')
        ->join('user u','o.buy_id = u.id')
        ->join('vehicle v','o.vehicle_id = v.vehicle_id')
        ->field('o.state o_state,v.*,u.*,o.*')
        ->where('o.sell_id',$userId)
        ->where('o.state','交易完成')
        ->select();
        if($res){
            echo json_encode($this->actionSuccess($res,1,'查找用户卖车内容成功'));
        }else{
            echo json_encode($this->actionFail('查找用户卖车失败~'));
        }
    }
    //已上架
    public function userOnTheShelf(){
        //找用户id
        $token = getPost()['token'];
        $userData = $this->redis->get($token);
        $tokenJson = json_decode ($userData);
        $tokenAdd = $tokenJson->token;
        //用户id
        $userId = $tokenJson->id;
        $res = db('vehicle')
        ->alias('v')
        ->join('user u','v.sell_id = u.id')
        ->where('v.sell_id',$userId)
        ->where('v.vehicle_state','已上架')
        ->select();
        if($res){
            echo json_encode($this->actionSuccess($res,1,'查找用户上架车辆成功'));
        }else{
            echo json_encode($this->actionFail('查找用户上架车辆失败~'));
        }
    }
    //已下架
    public function userUndercarriage(){
        //找用户id
        $token = getPost()['token'];
        $userData = $this->redis->get($token);
        $tokenJson = json_decode ($userData);
        $tokenAdd = $tokenJson->token;
        //用户id
        $userId = $tokenJson->id;
        $res = db('vehicle')
        ->alias('v')
        ->join('user u','v.sell_id = u.id')
        ->where('v.sell_id',$userId)
        ->where('v.vehicle_state','已下架')
        ->select();
        if($res){
            echo json_encode($this->actionSuccess($res,1,'查找用户下架车辆成功'));
        }else{
            echo json_encode($this->actionFail('查找用户下架车辆失败~'));
        }
    }
    //拍卖中
    public function userAtAuction(){
        //找用户id
        $token = getPost()['token'];
        $userData = $this->redis->get($token);
        $tokenJson = json_decode ($userData);
        $tokenAdd = $tokenJson->token;
        //用户id
        $userId = $tokenJson->id;
        $res = db('vehicle')
        ->alias('v')
        ->join('user u','v.sell_id = u.id')
        ->where('v.sell_id',$userId)
        ->where('v.vehicle_state','拍卖中')
        ->select();
        if($res){
            echo json_encode($this->actionSuccess($res,1,'查找用户拍卖中车辆成功'));
        }else{
            echo json_encode($this->actionFail('查找用户拍卖中车辆失败~'));
        }
    }
    //已拍卖
    public function userAuctioned(){
        //找用户id
        $token = getPost()['token'];
        $userData = $this->redis->get($token);
        $tokenJson = json_decode ($userData);
        $tokenAdd = $tokenJson->token;
        //用户id
        $userId = $tokenJson->id;
        $res = db('vehicle')
        ->alias('v')
        ->join('user u','v.sell_id = u.id')
        ->where('v.sell_id',$userId)
        ->where('v.vehicle_state','已拍卖')
        ->select();
        if($res){
            echo json_encode($this->actionSuccess($res,1,'查找用户已拍卖车辆成功'));
        }else{
            echo json_encode($this->actionFail('查找用户已拍卖车辆失败~'));
        }
    }
    //未审核
    public function userNotReviewed(){
        //找用户id
        $token = getPost()['token'];
        $userData = $this->redis->get($token);
        $tokenJson = json_decode ($userData);
        $tokenAdd = $tokenJson->token;
        //用户id
        $userId = $tokenJson->id;
        $res = db('vehicle')
        ->alias('v')
        ->join('user u','v.sell_id = u.id')
        ->where('v.sell_id',$userId)
        ->where('v.vehicle_state','未审核')
        ->select();
        if($res){
            echo json_encode($this->actionSuccess($res,1,'查找用户未审核车辆成功'));
        }else{
            echo json_encode($this->actionFail('查找用户未审核车辆失败~'));
        }
    }
}
