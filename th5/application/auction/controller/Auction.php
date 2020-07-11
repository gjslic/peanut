<?php
namespace app\auction\controller;

use think\Controller;
use think\Db;
use app\base\controller\ModuleBaseController;
use think\Request;
class Auction extends ModuleBaseController
{

    /**
     * redis
     * @var null
     */
    private $redis = null;

    /**
     * 声明存储哈希的表名
     * @var null
     */
    private $collectionUser = 'collection';
    /**
     * 构造函数
     * Index constructor.
     * @param Request|null $request
     */
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->redis = $this->getRedis();
    }


    /**
     * 获取redis
     * @return \Redis
     */
    private function getRedis()
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->auth('123456');
        return $redis;
    }


    /**
     * 设置缓存到redis
     * @param $token
     */
    private function setAuction($collectionArr)
    {
        return $this->redis->set($this->collectionUser, json_encode($collectionArr));
    }

    /**
     * 获取auction
     * @param $token
     */
    private function getAuction()
    {
        return json_decode($this->redis->get($this->collectionUser));
    }

    /**
     * 删除旧缓存
     * @param $oldToken
     */
    private function deleteOldToken()
    {
        $this->redis->del($this->collectionUser);
    }

    /**
     * 验证token
     * @return false|string
     */
    public function validateToken($token)
    {
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
    public function queryCar()
    {
        $brandArr = Db::name('brand')->select();
        $seriesArr = Db::name('series')->order('sales_volume desc')->limit(5)->select();
        $priceArr = Db::name('price')->select();
        $dataArr[] = $brandArr ? $brandArr : [];
        $dataArr[] = $seriesArr ? $seriesArr : [];
        $dataArr[] = $priceArr ? $priceArr : [];
        echo json_encode($this->actionSuccess($dataArr));
    }
    // public function queryCar()
    // {
    //     $brandArr = Db::name('brand')->select();
    //     $seriesArr = Db::name('series')->order('sales_volume desc')->limit(5)->select();
    //     $priceArr = Db::name('price')->select();
    //     $dataArr[] = $brandArr ? $brandArr : [];
    //     $dataArr[] = $seriesArr ? $seriesArr : [];
    //     $dataArr[] = $priceArr ? $priceArr : [];
    //     echo json_encode($this->actionSuccess($dataArr));
    // }
    //查询车辆
    public function vehicle(){
        $data = getPost();
        //判断是否查询
            if($data){
            //解析数据
            //品牌ID 
            $brandID = $data['brandID'] ? $data['brandID'] : '';
            //系类ID
            $seriesID = $data['seriesID'] ? $data['seriesID'] : '';
            // 价格范围
            $price = $data['price'] ? $data['price'] : '';
            // 排序
            $timeBaseNum = $data['timeBaseNum'] ? $data['timeBaseNum'] : '';
            // 价格排序
            $priceBaseNum = $data['priceBaseNum'] ? $data['priceBaseNum'] : '';
            // 排序条件
            $order = "";
            
            // 查询车辆条件
            $where = 'v.series_id=s.series_id and s.brand_id=b.brand_id and v.vehicle_state="拍卖中" and a.id=v.auction_id and v.sell_id=u.id';
            if($brandID != ''){
                $where = $where.' and b.brand_id='.$brandID;
            }
            if($seriesID != ''){
                $where = $where.' and v.series_id='.$seriesID;
            }
            if($price != ''){
                if($price==50){
                    $where = $where.' and v.price>'.$price;
                }else{
                    $where = $where.' and (v.price>'.substr($price,0,strpos($price, '-')).' and v.price <'.substr($price,strpos($price,'-')+1).')';
                }
            }
            if($timeBaseNum != ''){
                $order = $timeBaseNum == 1 ? 'v.vehicle_time desc' : 'v.vehicle_time asc';
            }
            if($priceBaseNum != ''){
                $order = $priceBaseNum == 1 ? 'v.price desc' : 'v.price asc';
            }
            $vehicle = db('vehicle v,peanut_brand b,peanut_series s,peanut_auction a,peanut_user u')->field("v.*,a.*,u.id userID")->where($where)->orderRaw($order)->select();
        }else{
            $where = ' v.auction_id=a.id and v.vehicle_state = "拍卖中"';
            $vehicle = Db::name('vehicle v,peanut_auction a')->where($where)->select();
        }
        $vehicleArr = [];
        if(count($vehicle)>0){
            $flag = 0;
            if(getPost()['token']){
                $token = getPost()['token'];
                $userArr = $this->validateToken($token);
                if($userArr){
                    $collection = db('collection')->where("user_id = $userArr->id")->select();
                }else{
                    $collection = [];
                }
            }else{
                $collection = [];
            }
            //判断拍卖商品的时间段
            foreach($vehicle as $key=>$val){
                //当前时间
                $nowTime = strtotime(date("Y-m-d H:i:s"));
                //开始时间
                $startTime = strtotime(date("Y-m-d").' '.$val['start_time']);
                //结束时间
                $endTime = strtotime(date("Y-m-d").' '.$val['end_time']);
                
                if($startTime<$nowTime && $endTime>$nowTime){
                    
                    $vehicleArr[] = $vehicle[$key];
                    $surplusTime = $endTime-$nowTime;
                    $flag = 1;
                    $collectionFlag = 0;
                    foreach($collection as $v){
                        if($v['vehicle_id']==$val['vehicle_id']){
                            $collectionFlag = 1;
                        }
                    }
                    if($collectionFlag==1){
                        $vehicleArr[count($vehicleArr)-1][] = [
                            'collection'=>1,
                            'flag'=>false
                        ];
                    }else{
                        $vehicleArr[count($vehicleArr)-1][] = [
                            'collection'=>0,
                            'flag'=>false
                        ];
                    }
                }
            }
            if($flag==1){
                //剩余时间
                echo json_encode(["code"=>1,"data"=>$vehicleArr,"surplusTime"=>$surplusTime]);
            }else{
                echo json_encode($this->actionSuccess($vehicleArr));
            }
        }else{
            echo json_encode($this->actionSuccess($vehicleArr));
        }
    }
    //     if($data){
    //         //解析数据
    //         //品牌ID 
    //         $brandID = $data['brandID'] ? $data['brandID'] : '';
    //         //系类ID
    //         $seriesID = $data['seriesID'] ? $data['seriesID'] : '';
    //         // 价格范围
    //         $price = $data['price'] ? $data['price'] : '';
    //         // 排序
    //         $timeBaseNum = $data['timeBaseNum'] ? $data['timeBaseNum'] : '';
    //         // 价格排序
    //         $priceBaseNum = $data['priceBaseNum'] ? $data['priceBaseNum'] : '';
    //         // 排序条件
    //         $order = "";
    //         // 查询车辆条件
    //         $where = 'v.series_id=s.series_id and s.brand_id=b.brand_id and v.vehicle_state="拍卖中" and a.id=v.auction_id and v.sell_id=u.id';
    //         if($brandID != ''){
    //             $where = $where.' and b.brand_id='.$brandID;
    //         }
    //         if($seriesID != ''){
    //             $where = $where.' and v.series_id='.$seriesID;
    //         }
    //         if($price != ''){
    //             if($price==50){
    //                 $where = $where.' and v.price>'.$price;
    //             }else{
    //                 $where = $where.' and (v.price>'.substr($price,0,strpos($price, '-')).' and v.price <'.substr($price,strpos($price,'-')+1).')';
    //             }
    //         }
    //         if($timeBaseNum != ''){
    //             $order = $timeBaseNum == 1 ? 'v.vehicle_time desc' : 'v.vehicle_time asc';
    //         }
    //         if($priceBaseNum != ''){
    //             $order = $priceBaseNum == 1 ? 'v.price desc' : 'v.price asc';
    //         }
    //         $vehicle = db('vehicle v,peanut_brand b,peanut_series s,peanut_auction a,peanut_user u')->field("v.*,a.*,u.id userID")->where($where)->orderRaw($order)->select();
    //     }else{
    //         $where = ' v.auction_id=a.id and v.vehicle_state = "拍卖中"';
    //         $vehicle = Db::name('vehicle v,peanut_auction a')->where($where)->select();
    //     }
    //     $vehicleArr = [];
    //     if(count($vehicle)>0){
    //         $flag = 0;
    //         $collection = db('collection')->where("user_id = 1")->select();
    //         //判断拍卖商品的时间段
    //         foreach($vehicle as $key=>$val){
    //             //当前时间
    //             $nowTime = strtotime(date("Y-m-d H:i:s"));
    //             //开始时间
    //             $startTime = strtotime(date("Y-m-d").' '.$val['start_time']);
    //             //结束时间
    //             $endTime = strtotime(date("Y-m-d").' '.$val['end_time']);
                
    //             if($startTime<$nowTime && $endTime>$nowTime){
                    
    //                 $vehicleArr[] = $vehicle[$key];
                    
    //                 $surplusTime = $endTime-$nowTime;
    //                 $flag = 1;
    //                 $collectionFlag = 0;
    //                 foreach($collection as $v){
    //                     if($v['vehicle_id']==$val['vehicle_id']){
    //                         $collectionFlag = 1;
    //                     }
    //                 }
    //                 if($collectionFlag==1){
    //                     $vehicleArr[count($vehicleArr)-1][] = [
    //                         'collection'=>1
    //                     ];
    //                 }else{
    //                     $vehicleArr[count($vehicleArr)-1][] = [
    //                         'collection'=>0
    //                     ];
    //                 }
    //             }
    //         }
            
    //         if($flag==1){
    //             //剩余时间
    //             echo json_encode(["code"=>1,"data"=>$vehicleArr,"surplusTime"=>$surplusTime]);
    //         }else{
    //             echo json_encode($this->actionSuccess($vehicleArr));
    //         }
    //     }else{
    //         echo json_encode($this->actionSuccess($vehicleArr));
    //     }
    // }
    // //查询系类
    public function seriesSel(){
        $brandID = getPost()['brandID'];
        if($brandID==0){
            $seriesArr = Db::name('series')->where('')->orderRaw('sales_volume desc')->limit(5)->select();
        }else{
            $seriesArr = Db::name('series')->where("brand_id = $brandID")->select();
        }
        if($seriesArr){
            echo json_encode($this->actionSuccess($seriesArr));
        }else{
            echo json_encode([]);
        }
    }
    //获取用户金额
    private function userMoney($id){
        return Db::name('user')->field('money,credit')->where("id = $id")->select();
    }
    //获取拍卖 出价的价格 修改
    public function Price(){
        $id = getPost()['id'];
        $price = getPost()['price'];
        $sellID = getPost()['sellID'];
        $token = getPost()['token'];
        $userArr = $this->validateToken($token);
        if($userArr){
            $money = $this->userMoney($userArr->id);
            if($money[0]['credit']>=80){
                if($sellID!=$userArr->id){
                    if($money[0]['money']>($price*10000)){
                        // $AuctionArr = $this->getAuction();
                        // if($AuctionArr){
                            // $flag = 0;
                            // foreach($AuctionArr as $key=>$val){
                            //     if($val->vehicle_id == $id){
                            //         $flag = 1;
                            //         $AuctionArr[$key] = [
                            //             'vehicle_id'=>$id,
                            //             'buy_id'=>$userArr->id
                            //         ];
                            //     }
                            // }
                            $surplusMoney = number_format($money[0]['money'],0,'','')-($price*10000);
                            // $userRes = Db::name('user')->where("id = $userArr->id")->update(['price' => $price,'buy_id'=>$userArr->id,'vehicle_state'=>'已拍卖']);
                            // $res = Db::name('vehicle')->where("vehicle_id = $id")->update(['price' => $price,'buy_id'=>$userArr->id,'vehicle_state'=>'已拍卖']);
                            //订单数据
                            $orderData = [
                                'order_num'=>"HS".time().mt_rand(1,100),
                                'sell_id'=>$sellID,
                                'buy_id'=>$userArr->id,
                                'vehicle_id'=>$id,
                                'transaction_time'=>date('Y-m-d H:i:s', time()),
                                'state'=>'待验收'
                            ];
                            $collection = Db::name('collection')->where("vehicle_id = $id")->select();
                            $where = ' 1=2 ';
                            foreach ($collection as $key => $value) {
                                $where .= " or collection_id = ".$value['id'];
                            };
                            $affairBool = true;
                            Db::startTrans();
                            try{

                                Db::name('user')->where("id = $userArr->id")->update(['money'=>$surplusMoney]);
                                Db::name('vehicle')->where("vehicle_id = $id")->update(['price' => $price,'buy_id'=>$userArr->id,'vehicle_state'=>'已拍卖']);
                                Db::name('order')->insert($orderData);
                                Db::name('collection')->where("vehicle_id = $id")->delete();
                                Db::name('collection_category')->where($where)->delete();
                                // 提交事务
                                Db::commit();    
                            } catch (\Exception $e) {
                                // 回滚事务
                                Db::rollback();
                                $affairBool = false;
                                $msg = $e->getMessage();
                            }
                            if($affairBool){
                                echo json_encode(["code"=>1,'msg'=>'出价成功,为您跳转个人中心']);
                            }else{
                                echo json_encode(["code"=>0,'msg'=>'服务器繁忙，请稍后在试']);
                            }
                            // if($flag == 1){
                            //     $res = $this->setAuction($AuctionArr);
                            // }else{
                            //     $AuctionArr[] = [
                            //         'vehicle_id'=>$id,
                            //         'buy_id'=>$userArr->id
                            //     ];
                            //     $res = $this->setAuction($AuctionArr); 
                            // }
                        // }else{
                        //     $collectionArr[]=[
                        //         'vehicle_id'=>$id,
                        //         'buy_id'=>$userArr->id
                        //     ];
                        //     $res = $this->setAuction($collectionArr);
                        // }
                    }else{
                        echo json_encode(["code"=>0,'msg'=>'金额不足，请先充值']);
                    }
                }else{
                    echo json_encode(["code"=>0,'msg'=>'不能 购买自己卖的车辆']);
                }
                    
                
            }else{
                echo json_encode(["code"=>0,'msg'=>'您的信誉值不知，暂不能消费']);
                echo json_encode($this->actionFail());
            }


        }else{
            echo json_encode(["code"=>0,'msg'=>'请先登录']);
        }

    }
    
    // //获取用户信息
    // public function getUsetInfo(){
    //     $res = Db::name('vehicle')->where('id', 1)->update($update);
    //     return $res ? $res : false;
    // }
    //收藏
    public function collection(){
        $data = getPost();
        if(!empty($data)){
            $vehicleID = $data['vehicleID'];
            $flag = $data['flag']; 
            $sellID = $data['sellID'];
            if(getPost()['token']){
                $token = getPost()['token'];
                $userArr = $this->validateToken($token);
                if($userArr){
                    if($flag==0){
                        $data = [
                            'vehicle_id'=>$vehicleID,
                            'user_id'=>$userArr->id
                        ];
                        $collectionID = Db::name('collection')->insertGetId($data);
                        if($collectionID){
                            $categoryData = [
                                'collection_id'=>$collectionID,
                                'sell_id'=>$sellID
                            ];
                            $res = Db::name('collection_category')->insert($categoryData);
                            if($res){
                                echo json_encode($this->actionSuccess());
                            }else{
                                echo json_encode($this->actionFail());
                            }
                        }else{
                            echo json_encode($this->actionFail());
                        }
                    }else{
                        $data = [
                            'vehicle_id'=>$vehicleID,
                            'user_id'=>$userArr->id
                        ];
                        $collection = Db::name('collection')->where($data)->find();
                        if($collection){
                            $delColl = Db::name('collection')->where('id',$collection['id'])->delete();
                            if($delColl){
                                $res = Db::name('collection_category')->where('collection_id',$collection['id'])->delete();
                                if($res){
                                    echo json_encode($this->actionSuccess());
                                }else{
                                    echo json_encode($this->actionFail());
                                }
                            }else{
                                echo json_encode($this->actionFail());
                            }
                        }
                    }
                }else{
                    echo json_encode(["code"=>0,"msg"=>'请重新登录']);
                }
           
            }else{
                echo json_encode(["code"=>0,"msg"=>'请先登录']);
            }
        }
    }
}
