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

if(count($argv) < 3){
	echo "error param for start server!\n";
    echo "example: php public/server.php start -p 9501 \n";
    echo "example: php public/server.php stop \n";
    echo "example: php public/server.php reload \n";
    exit();
}
if($argv[1] == 'stop'){

	$httpServer = new \MyApp\Core\HttpServer($argv[3]);
    $httpServer->stopServer();

}

if($argv[1] == 'reload'){//重启服务
	$httpServer = new \MyApp\Core\HttpServer($argv[3]);
    $httpServer->reloadServer();
}

if($argv[1] == 'start' && isset($argv[2]) && $argv[2] == '-p' && isset($argv[3])  && $argv[3] >= 9501 ){
    $httpServer = new \MyApp\Core\HttpServer($argv[3]);
    $httpServer->getInstance($argv[3]);
}
