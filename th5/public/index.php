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
if ( strtolower( $_SERVER[ 'REQUEST_METHOD' ] ) == 'options' ) {
  header( "Access-Control-Allow-Origin:*" );
  header( 'Access-Control-Allow-Methods:OPTIONS , GET, PUT, POST, DELETE' );
  header( 'Access-Control-Allow-Headers:Origin, Content-Type, X-Auth-Token , Authorization , Access-Token , X-Requested-With' );
  header( 'Accept: application/json' );
  header( 'Content-Type: application/json' );
  exit;
}

//跨域接收
// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods: GET, POST, PUT');
// header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");


// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');

// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
