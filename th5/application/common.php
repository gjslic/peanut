<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
<<<<<<< HEAD
function getPost(){
    return json_decode(file_get_contents("php://input"),true);
}
=======

namespace app\common\behavior;

use think\Response;
class Cors{
  public function run(&$dispatch){
    header("Access-Control-Allow-Origin:*");
    $host_name = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : "*";
    $headers = [
      "Access-Control-Allow-Origin" => $host_name,
      "Access-Control-Allow-Credentials" => 'true',
      "Access-Control-Allow-Headers" => "Content-Type, Authorization, X-Requested-With"
    ];
    if($dispatch instanceof Response) {
      $dispatch->header($headers);
    } else if($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
      $dispatch['type'] = 'response';
      $response = new Response('', 200, $headers);
      $dispatch['response'] = $response;
    }
  }
}
>>>>>>> long
