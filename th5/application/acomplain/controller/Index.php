<?php
namespace app\acomplain\controller;

use think\Controller;
use think\Db;
use think\Session;
use app\base\controller\ModuleBaseController;

class Index extends ModuleBaseController
{
    // 查询数据
    public function index()
    {   
        $result= db('comment c,peanut_user u,peanut_user d')
        ->field('c.*,u.name as uName,d.name as dName,d.credit as dCredit')
        ->where('c.user_id = u.id and c.sell_id = d.id')
        ->order('id desc')
        ->select();
        if ($result) {
            echo json_encode($this->actionSuccess($result,0,'成功'));
        } else {
            echo json_encode($this->actionFail('失败'));
        }
    }


    // 修改数据
    public function update()
    {
        $id = getPost()['id'];
        $sellId = getPost()['sellId'];
        $credit = getPost()['credit'];
        $where = [
            'id' => $sellId//商家id
        ];
        $update = [
            'credit' => $credit
        ];
        $update2 = [
            'comment_state' => '已审'
        ];
        $where2 = [
            'id' => $id//订单的id
        ];
        $result = db('user')->where($where)->update($update);
        $result2 = db('comment')->where($where2)->update($update2);
        if ($result) {
            echo json_encode($this->actionSuccess($result,1,'成功'));
        } else {
            echo json_encode($this->actionFail('失败'));
        }  
    }


    


   


}