<?php
namespace app\register\controller;
use app\base\controller\ModuleBaseController;

class Register extends ModuleBaseController
{
    public function register(){
        //账号
        $acc = getPost()['acc'];
        //名称
        $name = getPost()['name'];
        //手机号
        $phone = getPost()['phone'];
        //密码
        $password = md5(getPost()['password']);
        //查重
        //找到相同名称
        $whName = db('user') ->where('name',$name)->find ();
        //找到相同手机号
        $whPhone = db('user') ->where('phone',$phone)->find ();
        //找到相同账号
        $whAcc = db('user') ->where('acc',$acc)->find ();
        //查重名称
        if($whName){
            echo json_encode($this->actionFail('您输入的名称已存在'));
        //查重手机号
        }else if($whPhone){
            echo json_encode($this->actionFail('您输入的手机号已存在'));
        //查重账号
        }else if($whAcc){
            echo json_encode($this->actionFail('您输入的账号已存在'));
        }else{
            //存入数据库
            $data = [
                'acc' => $acc,
                'name' => $name,
                'phone' => $phone,
                'password' => $password
            ];
            //存进去
            $result = db('user')->insert($data);
            //判断
            if($result){
                echo json_encode($this->actionSuccess($data,1,'恭喜您~注册成功~,快去登录吧~'));
            }else{
                echo json_encode($this->actionFail('您注册失败~'));
            }
        }   
    }
}
