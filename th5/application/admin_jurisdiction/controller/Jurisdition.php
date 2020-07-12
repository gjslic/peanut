<?php
namespace app\admin_jurisdiction\controller;

use app\base\controller\ModuleBaseController;

use think\Db;
use think\Model;
use app\admin_jurisdiction\model\JurisditionManage;

class Jurisdition extends ModuleBaseController{
  /**
   * [getRoleList 获取所有角色和菜单列表]
   */
  public function getRoleList(){
    $roleRes = db('role')->select();
    $menuRes = json_decode($this->getMenuList(),1);
    return $roleRes && $menuRes ? json_encode($this->actionSuccess(['roleList' => $roleRes , 'jurList' => $menuRes['data']])) : json_encode($this->actionFail('空'));
  }
  /**
   * [getMenuList 获取菜单列表]
   */
  public function getMenuList(){
    $menuRes = db('menu')->field('id,menu_name as label,menu_url,fid,menu_class')->select();
    return $menuRes ? json_encode($this->actionSuccess($menuRes)) : '';
  }
  /** 
   * [getJurisditionList 获取对应权限列表前置判断]
   */
  public function getJurisditionList(){
    $id = input('get.nowId');
    $jurisdition = new JurisditionManage();
    $res = $jurisdition->where('role_id',$id)->select();
    return $res ? json_encode($this->actionSuccess($res,0,'')) : json_encode($this->actionFail('当前职位无任何权限，请配置'));
  }
  /**
   * [getJurisditionTree 获取对应权限列表]
   */
  public function getJurisditionTree(){
    $id = json_decode(input('get.idArr'),1);
    if($id){
      $res = db('menu')->where('id','in',$id)->field('id,menu_name as label,menu_class as icon,menu_url,fid,menu_class')->select();
      return $res ? json_encode($this->actionSuccess($res)) : json_encode($this->actionFail('无权限'));
    }else{
      return json_encode($this->actionFail('无权限'));
    }
  }
  /**
   * [editJurisdition 修改当前角色权限]
   */
  public function editJurisdition(){
    $id = json_decode(input('get.nowJurArr'),1);
    sort($id);
    $nowRoleId = input('get.nowRoleId');
    if (count($id) != count(array_unique($id))) {  
      $newJurArr = array_unique($id);
      $allJurId = implode(',',$newJurArr);
    }else{
      $allJurId = implode(',',$id);
    }
    $nowjur = JurisditionManage::where('role_id',$nowRoleId)->delete();
    $res = JurisditionManage::create([
      'role_id' => $nowRoleId,
      'menu_id' => $allJurId
    ]);
    return $res ? json_encode($this->actionSuccess([],0,'配置成功')) : json_encode($this->actionFail('配置失败，请检查网络配置'));
  }

}
