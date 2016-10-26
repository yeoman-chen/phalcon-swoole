<?php

/**
 * Swoole HttpServer 服务器
 * @author Yeoman
 * @since 2016.09.05
 *
 */

date_default_timezone_set('Asia/Shanghai');
define('APP_PATH', realpath(dirname(__FILE__) . '/../'));
include APP_PATH . "/core/HttpServer.php";

//print_r($argv);die;

if(isset($argv[1]) && $argv[1] == '-p' && isset($argv[2])  && $argv[2] >= 9501 ){
    HttpServer::getInstance($argv[2]);
}else{
    echo "error param for start server!\n";
    echo "example: php public/server.php -p 9501 \n";
}
