<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]




/*设置头部*/ 

//跨域接收
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
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



// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');

// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
