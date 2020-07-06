<?php
namespace app\adminNotice\controller;

use think\Controller;
use think\Db;
use think\Session;
use app\base\controller\ModuleBaseController;

class Index extends ModuleBaseController
{
    // 查询数据
    public function index()
    {
        $result = db('notice')->select();
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
            'notice_Publisher' => getPost()['publish'],
            'notice_cont' => getPost()['noticeCont'],
        ];
        $result = db('notice')->insert($data);
        $result2 = db('notice')->select();
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
            echo json_encode($this->actionFail('系统默认公告（1、2、3、4），不可修改'));
        }else{
            $where = [
                'id' => $id
            ];
            $update = [
                'notice_Publisher' => getPost()['notice_Publisher'],
                'notice_cont' => getPost()['notice_cont'],
            ];
            $result = db('notice')->where($where)->update($update);
            $result2 = db('notice')->select();
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
            echo json_encode($this->actionFail('系统默认公告（1、2、3、4），不可删除'));
        }else{
            $where = [
                'id' => $id
            ];
            $result = db('notice')->where($where)->delete();
            $result2 = db('notice')->select();
            if ($result) {
                echo json_encode($this->actionSuccess($result2,0,'成功'));
            } else {
                echo json_encode($this->actionFail('失败'));
            }
        }
     }



}