<?php
namespace app\buyCar\controller;

use think\Controller;
use think\Db;
use app\base\controller\ModuleBaseController;
class Buycar extends ModuleBaseController
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
            //搜索的关键字
            $search = $data['search'] ? $data['search'] : '';
            // 排序
            $timeBaseNum = $data['timeBaseNum'] ? $data['timeBaseNum'] : '';
            // 价格排序
            $priceBaseNum = $data['priceBaseNum'] ? $data['priceBaseNum'] : '';
            //省份ID
            $citID = getPost()['citID'] ? getPost()['citID'] : '';
            // 排序条件
            $order = "";
            // 查询车辆条件
            $where = 'v.series_id=s.series_id and s.brand_id=b.brand_id and v.vehicle_state="已上架"';
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
            if($search !=''){
                $where = $where." and v.vehicle_name like '%$search%' ";
            }
            if($citID !=''){
                $where = $where." and v.city_id = $citID ";
            }
            $vehicleArr = db('vehicle v,peanut_brand b,peanut_series s')->field("v.*")->where($where)->orderRaw($order)->select();
        }else{
            $vehicleArr = Db::name('vehicle')->where('vehicle_state','=','已上架')->select();
        }
        if($vehicleArr){
            echo json_encode($this->actionSuccess($vehicleArr));
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
}
