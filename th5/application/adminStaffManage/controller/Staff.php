<?php
namespace app\adminStaffManage\controller;

use app\base\controller\ModuleBaseController;
use think\Model;
use think\Db;
use app\AdminStaffManage\model\StaffManage;


class Staff extends ModuleBaseController{
    /**
     * [getStaffArr 获取员工列表]
     */
    public function getStaffArr(){
        $keyWord = input('post.keyWord');
        $nowPage = input('post.nowPage');
        $limitPage = 8;
        $nowTotal = ($nowPage - 1) * $limitPage;
        $countList = db('staff')->count();
        $res = db("staff s,peanut_role r")->field('s.*,r.id as rid,r.role_name')->where("s.role_id = r.id")->limit($nowTotal,$limitPage)->select();
        if($res){
            echo json_encode($this->actionSuccess($res,$countList,''));
        }else{
            echo json_encode($this->actionFail([],0,'连接异常'));
        }
    }
    /**
     * [getRoleArr 获取角色列表]
     */
    public function getRoleArr(){
        $res = db('role')->select();
        return $res ? json_encode($this->actionSuccess($res,0,'')) : json_encode($this->actionFail('职位表不存在'));
    }
    /**
     * addStaff 添加员工
     */
    public function addStaff(){
        $nickName = input('post.data.name','');
        $staffAcc = input('post.data.account','');
        $staffPsw = input('post.data.password','');
        $staffTel = input('post.data.phone','');
        $staffRole = input('post.data.checkRole','');
        $staffSex = input('post.data.sex','');
        $headImg = input('post.data.head_img','');
        $roleId = db('role')->field('id')->where('role_name',$staffRole)->find();
        $getAcc = StaffManage::where('account', $staffAcc)->value('account');
        $getPhone = StaffManage::where('phone', $staffTel)->value('account');
        if($getAcc){
            echo json_encode(['code'=>1002,'msg'=>'账号重复，请重新选择账号']);
        }else{
            if($getPhone){
                echo json_encode(['code'=>1003,'msg'=>'手机号已被绑定']);
            }else{
                $staff = new staffManage([
                    'account' => $staffAcc,
                    'password' => md5($staffPsw),
                    'phone' => $staffTel,
                    'name' => $nickName,
                    'state' => 1,
                    'sex' => $staffSex,
                    'head_img' => $headImg,
                    'role_id' => $roleId['id']
                ]);
                $res = $staff->save();
                return $res ? json_encode($this->actionSuccess([],0,'添加成功')) : json_encode($this->actionFail('添加失败'));
            }
        }
    }
    /**
     * [editStaff 修改员工信息]
     */
    public function editStaff(){
        $allData = json_decode(file_get_contents('php://input'),true);
        $infos = $allData['data'];
        $where['phone'] = ['=' , $infos['phone']] ;
        $where['account'] = ['<>' , $infos['account']];
        $getPhone = db('staff')->where($where)->find();
        if($getPhone){
            echo json_encode($this->actionFail('账号重复'));
        }else{
            $staff = new staffManage;
            $res = $staff->save([
                'phone' => $infos['phone'],
                'name' => $infos['name'],
                'sex' => $infos['sex'],
                'head_img' => $infos['head_img'],
                'role_id' => $infos['role_id']
            ],['id' => $infos['id']]);
            return $res ? json_encode($this->actionSuccess([],0,'修改成功')) : json_encode($this->actionFail('修改失败'));
        }
    }
    /**
     * [delStaff 单独删除员工]
     */
    public function delStaff(){
        $id = input('post.data');
        $res = StaffManage::destroy(['id' => $id]);
        return $res ? json_encode($this->actionSuccess([],0,'删除成功')) : json_encode($this->actionFail('删除失败'));
    }
    /**
     * [batchDeleteStaff 批量删除员工]
     */
    public function batchDeleteStaff(){
        $idArr = json_decode(input('get.delArr'),1);
        $idList = implode(",",$idArr);
        $res = StaffManage::destroy($idList);
        return $res ? json_encode($this->actionSuccess([],0,'删除成功')) : json_encode($this->actionFail('网络异常，请刷新重试'));
    }
    /**
     * [editState 修改账号状态]
     */
    public function editState(){
        $id = input('post.id');
        $state = input('post.state');
        $res = StaffManage::where('id',$id)->update(['state'=>$state]);
        if($res){
            $state = StaffManage::where('id',$id)->value('state');
            return $state == 0 ? json_encode($this->actionSuccess($state,0,'已锁定')) : json_encode($this->actionSuccess($state,0,'已解锁'));
        }else{
            echo json_encode($this->actionFail('修改异常'));
        }
    }
    /**
     *  [resetPsw 重置密码]
     */ 
    public function resetPsw(){
        $id = $_GET['data'];
        $newPsw = md5('abc123');
        $nowPsw = StaffManage::where('id',$id)->value('password');
        if($nowPsw == $newPsw){
            return json_encode($this->actionFail('密码为初始密码：abc123'));
        }else{
            $res = StaffManage::where('id',$id)->update(['password' => $newPsw]);
            return $res ? json_encode($this->actionSuccess('abc123',0,'重置成功')) : json_encode($this->actionFail('重置失败'));
        }
        
    }
}
