<?php
namespace app\retrieve\controller;
use app\base\controller\ModuleBaseController;

class Retrieve extends ModuleBaseController
{
    public function retrieve(){
        //手机号
        $phone = getPost()['phone'];
        //密码
        $password = md5(getPost()['password']);
        //新密码存入数据库
        $data = [
            'password' => $password
        ];
        //数据库查找相对信息
        $user = db('user') ->where(['phone' => $phone])->find ();
        //判断
        if($user["state"] == '解锁'){
            //存进去
            $result = db('user')->where('phone',$phone)->update($data);
            if($result){ 
                echo json_encode($this->actionSuccess($data,1,'恭喜您~修改成功~下次别忘咯~'));
            }else{
                echo json_encode($this->actionFail('改密码和您原本密码相同,无法修改'));
            }
        }else{
            echo json_encode($this->actionFail('您的账号已被锁定,请联系工作人员进行解锁'));
        }  
    }
}
