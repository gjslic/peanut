<?php
namespace app\arole\controller;

use think\Controller;
use think\Db;
use think\Session;
use app\base\controller\ModuleBaseController;

class Index extends ModuleBaseController
{
    // 查询数据
    public function index()
    {
        $result = db('role')->select();
        if ($result) {
            echo json_encode($this->actionSuccess($result,0,'成功'));
        } else {
            echo json_encode($this->actionFail('失败'));
        }
    }


    // 添加数据
    public function add()
    {
        $data = [
            'role_name' => getPost()['roleName'],
            'desc' => getPost()['roleDesc'],
        ];
        $result = db('role')->insert($data);
        $result2 = db('role')->select();
        if ($result) {
            echo json_encode($this->actionSuccess($result2,0,'成功'));
        } else {
            echo json_encode($this->actionFail('失败'));
        }
    }
    

    // 修改数据
    public function update()
    {
        $id = getPost()['id'];
        if($id<5){
            echo json_encode($this->actionFail('系统默认角色（1、2、3、4），不可修改'));
        }else{
            $where = [
                'id' => $id
            ];
            $update = [
                'role_name' => getPost()['role_name'],
                'desc' => getPost()['desc'],
            ];
            $result = db('role')->where($where)->update($update);
            $result2 = db('role')->select();
            if ($result) {
                echo json_encode($this->actionSuccess($result2,0,'成功'));
            } else {
                echo json_encode($this->actionFail('失败'));
            }  
        }
    }


     // 删除数据
     public function delete()
     {
        $id = getPost()['id'];
        if($id<5){
            echo json_encode($this->actionFail('系统默认角色（1、2、3、4），不可删除'));
        }else{
            $where = [
                'id' => $id
            ];
            $result = db('role')->where($where)->delete();
            $result2 = db('role')->select();
            if ($result) {
                echo json_encode($this->actionSuccess($result2,0,'成功'));
            } else {
                echo json_encode($this->actionFail('失败'));
            }
        }
     }



}
