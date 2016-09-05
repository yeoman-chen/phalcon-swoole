<?php

/**
 * Swoole HttpServer 服务器
 * @author Yeoman 
 * @since 2016-09-05
 *
 */
//define('APP_PATH', realpath('..'));
define('APP_PATH', realpath(dirname(__FILE__).'/../'));

class HttpServer
{

	public static $instance;
	private $http;
	private $application;
	
	public function __construct(){
		// 创建swoole_http_server对象
		$this->http = new swoole_http_server('127.0.0.1',9501);
		//设置参数
		$this->http->set(
				array(
					'worker_num' => 16,
					'deamonize' => false,
					'max_request' => 10000,
					'dispatch_mode' => 1
				)
			);
		$this->http->setGlobal(HTTP_GLOBAL_ALL);
		// 绑定WorkerStart
		$this->http->on('WorkerStart',array($this,'onWorkStart'));
		// 绑定request
		$this->http->on('request',array($this,'onRequest'));
		// 开启服务器
		$this->http->start();

	}
	// WorkStart 回调
	public function onWorkStart()
	{
		require APP_PATH . '/app/configs/services.php';
		$this->application = new \Phalcon\Mvc\Application;
		$this->application->setDI($di);
	}
	//处理http请求
	public function onRequest($request,$response){

		ob_start();
		try{
			$_GET['_url'] = $request->server['request_uri'];
			echo $this->application->handle()->getContent();
		} catch (Exception $e){
			echo $e->getMessage();
		}
		$result = ob_get_contents();
		ob_end_clean();
		$response->end($result);
	}
	//获取实例对象
	public static function getInstance(){
		if(!self::$instance) {

			self::$instance = new HttpServer();
		} 

		return self::$instance;
	}
}

HttpServer::getInstance();