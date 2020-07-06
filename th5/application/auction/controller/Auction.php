<?php
namespace app\auction\controller;

use think\Controller;
use think\Db;
use app\base\controller\ModuleBaseController;
class Auction extends ModuleBaseController
{
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
            $where = 'v.series_id=s.series_id and s.brand_id=b.brand_id and v.vehicle_state="拍卖中" and a.id=v.vehicle_id and a.sell_id=u.id';
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
            $collection = db('collection')->where("user_id = 1")->select();
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
                            'collection'=>1
                        ];
                    }else{
                        $vehicleArr[count($vehicleArr)-1][] = [
                            'collection'=>0
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
    //查询系类
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
    //获取拍卖 出价的价格 修改
    public function Price(){
        $id = getPost()['id'];
        $price = getPost()['price'];
        $userArr = $this->getUsetInfo();
        if($userArr->money>($price*10000)){
            if(!empty($id) || !empty($price)){
                $update = [
                    'price' => $price,
                    'buy_id' => 1
                ];
                $res = Db::name('vehicle')->where('vehicle_id', $id)->update($update);
                if($res){
                    echo json_encode($this->actionSuccess());
                }else{
                    echo json_encode($this->actionFail());
                }
            }else{
                echo json_encode($this->actionFail());
            }
        }else{
            echo json_encode(["code"=>2,'msg'=>'金额不足，请先充值']);
        }
    }
    //获取用户信息
    public function getUsetInfo(){
        $res = Db::name('vehicle')->where('id', 1)->update($update);
        return $res ? $res : false;
    }
    //收藏
    public function collection(){
        $data = getPost();
        if(!empty($data)){
            $vehicleID = $data['vehicleID'];
            $flag = $data['flag']; 
            $sellID = $data['sellID'];
            if($flag==0){
                $data = [
                    'vehicle_id'=>$vehicleID,
                    'user_id'=>1
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
                    'user_id'=>1
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
                }else{
                    echo json_encode($this->actionFail());
                }
            }
        }else{
            echo json_encode($this->actionFail());
        }
    }
}
