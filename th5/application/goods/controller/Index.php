<?php

namespace app\goods\controller;

use app\base\controller\ModuleBaseController;
use SQL;
use think\Controller;
use think\Db;

class Index extends ModuleBaseController
{

  public function get()
  {
    // 四表联查出车辆信息
    $res = db('vehicle')
      ->alias('v')
      ->join('series s', 'v.series_id = s.series_id')
      ->join('brand b', 's.brand_id = b.brand_id')
      ->join('user u', 'u.id = v.sell_id')
      ->where('vehicle_state', '已上架')
      ->whereOr('vehicle_state', '已下架')
      ->select();
    if ($res) {
      echo json_encode($this->actionSuccess($res));
    } else {
      echo json_encode($this->actionFail());
    }
  }
  /**
   * [getTime 获取活动时间]
   */
  public function getTime()
  {
    $res = db('auction')->select();
    if ($res) {
      echo json_encode($this->actionSuccess($res));
    } else {
      echo json_encode($this->actionFail());
    }
  }
  /**
   * [getCity获取城市]
   */
  public function getCity()
  {
    $res = db('city')->select();
    if ($res) {
      echo json_encode($this->actionSuccess($res));
    } else {
      echo json_encode($this->actionFail());
    }
  }
  /**
   * [getTab获取车辆标签]
   */
  public function getTab()
  {
    $res = db('tab')->select();
    if ($res) {
      echo json_encode($this->actionSuccess($res));
    } else {
      echo json_encode($this->actionFail());
    }
  }
  /**
   * [getSeries 获取品牌车系]
   */
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
  /**
   * [stateClass 分类]
   */
  public function stateClass()
  {
    $state = getPost()['state'];
    $res = db('vehicle')
      ->alias('v')
      ->join('series s', 'v.series_id = s.series_id')
      ->join('brand b', 's.brand_id = b.brand_id')
      ->join('user u', 'u.id = v.sell_id')
      ->where('vehicle_state', $state)
      ->select();
    if ($res) {
      echo json_encode($this->actionSuccess($res));
    } else {
      echo json_encode($this->actionFail());
    }
  }
  /**
   * [search 搜索]
   */
  public function search()
  {
    $condition = getPost()['condition'];
    $keyword = !empty(getPost()['keyword']) ? explode(" ", getPost()['keyword']) : null; //分割关键词
    if (!$keyword) {
      $where = "1=1";
    } else {
      $where = "1=0";
      foreach ($keyword as $value) {
        if ($value) {
          $where .= " or {$condition} like '%$value%'";
        }
      }
    }
    $sql = "SELECT * FROM peanut_vehicle v INNER JOIN peanut_series s ON v.series_id=s.series_id INNER JOIN peanut_brand b ON s.brand_id=b.brand_id INNER JOIN peanut_user u ON u.id=v.sell_id WHERE (vehicle_state = '已上架' OR  vehicle_state = '已下架' ) and ({$where})";
    $res = Db::query($sql);
    if ($res) {
      echo json_encode($this->actionSuccess($res));
    } else {
      echo json_encode($this->actionFail('暂无查到相关信息'));
    }
  }
  /**
   * [out 下架]
   */
  public function out()
  {
    $id = getPost()['id'];
    $res = db('vehicle')
      ->update(['vehicle_state' => '已下架', 'vehicle_id' => $id]);
    if ($res) {
      $data = db('vehicle')
        ->alias('v')
        ->join('series s', 'v.series_id = s.series_id')
        ->join('brand b', 's.brand_id = b.brand_id')
        ->join('user u', 'u.id = v.sell_id')
        ->select();
      echo json_encode($this->actionSuccess($data));
    } else {
      echo json_encode($this->actionFail());
    }
  }
  /**
   * [del删除]
   */
  public function del()
  {
    $id = getPost()['id'];
    $res = db('vehicle')->where('vehicle_id', $id)->delete();
    if ($res) {
      echo json_encode($this->actionSuccess([], 0, '删除成功'));
    } else {
      echo json_encode($this->actionFail('删除失败'));
    }
  }
  /**
   * [getSale 请求拍卖表]
   */
  public function getSale()
  {
    $res = db('vehicle')
      ->alias('v')
      ->join('auction a', 'v.auction_id = a.id')
      ->join('user u', 'u.id = v.sell_id')
      ->where('vehicle_state', '拍卖中')
      ->whereOr('vehicle_state', '未审核')
      ->whereOr('vehicle_state', '已拍卖')
      ->select();
    if ($res) {
      echo json_encode($this->actionSuccess($res));
    } else {
      echo json_encode($this->actionFail());
    }
  }
  /**
   * stateFind[拍卖状态分类]
   */
  public function stateFind()
  {
    $state = getPost()['state'];
    $res = db('vehicle')
      ->alias('v')
      ->join('auction a', 'v.auction_id = a.id')
      ->join('user u', 'u.id = v.sell_id')
      ->where('vehicle_state', $state)
      ->select();
    if ($res) {
      echo json_encode($this->actionSuccess($res));
    } else {
      echo json_encode($this->actionFail());
    }
  }
  /**
   * [pass审核]
   */
  public function pass()
  {
    $id = getPost()['id'];
    $res = db('vehicle')
      ->update(['vehicle_state' => '拍卖中', 'vehicle_id' => $id]);
    if ($res) {
      $data = db('vehicle')
        ->alias('v')
        ->join('auction a', 'v.auction_id = a.id')
        ->join('user u', 'u.id = v.sell_id')
        ->where('vehicle_state', '拍卖中')
        ->whereOr('vehicle_state', '未审核')
        ->whereOr('vehicle_state', '已拍卖')
        ->select();
      echo json_encode($this->actionSuccess($data));
    } else {
      echo json_encode($this->actionFail());
    }
  }
  /**
   * [saleOut下架拍卖]
   */
  public function saleOut()
  {
    $id = getPost()['id'];
    $res = db('vehicle')
      ->update(['vehicle_state' => '已下架', 'vehicle_id' => $id]);
    if ($res) {
      $data = db('vehicle')
        ->alias('v')
        ->join('auction a', 'v.auction_id = a.id')
        ->join('user u', 'u.id = v.sell_id')
        ->where('vehicle_state', '拍卖中')
        ->whereOr('vehicle_state', '未审核')
        ->whereOr('vehicle_state', '已拍卖')
        ->select();
      
      echo json_encode($this->actionSuccess($data));
    } else {
      echo json_encode($this->actionFail());
    }
  }
  /**
   * delSale[删除拍卖商品]
   */
  public function delSale()
  {
    $id = getPost()['id'];
    $res = db('vehicle')->where('vehicle_id', $id)->delete();
    if ($res) {
      echo json_encode($this->actionSuccess());
    } else {
      echo json_encode($this->actionFail());
    }
  }
  /**
   * [searchSale拍卖搜索]
   */
  public function searchSale()
  {
    $condition = getPost()['condition'];
    $keyword = !empty(getPost()['keyword']) ? explode(" ", getPost()['keyword']) : null; //分割关键词
    if (!$keyword) {
      $where = "1=1";
    } else {
      $where = "1=0";
      foreach ($keyword as $value) {
        if ($value) {
          $where .= " or {$condition} like '%$value%'";
        }
      }
    }
    $sql = "SELECT * FROM peanut_vehicle v INNER JOIN peanut_auction a ON v.auction_id=a.id INNER JOIN peanut_user u ON u.id=v.sell_id WHERE (vehicle_state = '拍卖中' OR ( vehicle_state = '未审核' OR vehicle_state = '已拍卖' ) ) and ({$where})";
    $res = Db::query($sql);
    if ($res) {
      echo json_encode($this->actionSuccess($res));
    } else {
      echo json_encode($this->actionFail('暂无查到相关信息'));
    }
  }
  /**
   * [upload 上传]
   */
  public function upload()
  {
    // 获取表单上传文件 例如上传了001.jpg
    $file = request()->file('image');

    // 移动到框架应用根目录/public/uploads/ 目录下
    if ($file) {
      $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
      if ($info) {
        // 成功上传后 获取上传信息
        // 输出 jpg
        // echo $info->getExtension();
        // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
        $url = 'http://127.0.0.1/th5/public/uploads/';
        echo json_encode($url . $info->getSaveName());
        // echo $info->getSaveName();
        // 输出 42a79759f284b767dfcb2a0197904287.jpg
        // echo $info->getFilename(); 
      } else {
        // 上传失败获取错误信息
        echo $file->getError();
      }
    }
  }
  /**
   * issue[发布拍卖车辆]
   */
  public function issue()
  {
    $imgUrl = getPost()['imgUrl'];
    $ruleForm = getPost()['ruleForm'];
    // 添加到车辆表
    $list = [
      'series_id' => $ruleForm['value'][1],
      'price' => $ruleForm['salePrice'],
      'sell_id' => '19',
      'vehicle_name' => $ruleForm['name'],
      'vehicle_distance' => $ruleForm['way'],
      'introduce' => $ruleForm['desc'],
      'city_id' => $ruleForm['region'],
      'tab_id' => $ruleForm['resource'],
      'vehicle_state' => '未审核',
      'img' => $imgUrl,
      'auction_id' => $ruleForm['date1'],
    ];
    $res = db('vehicle')->insert($list);
    if ($res) {
      $data = db('vehicle')
        ->alias('v')
        ->join('auction a', 'v.auction_id = a.id')
        ->join('user u', 'u.id = v.sell_id')
        ->where('vehicle_state', '拍卖中')
        ->whereOr('vehicle_state', '未审核')
        ->whereOr('vehicle_state', '已拍卖')
        ->select();
      echo json_encode($this->actionSuccess($data,'','发布成功,待审核'));
    } else {
      echo json_encode($this->actionFail('发布失败'));
    }
  }
}
