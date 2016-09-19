<?php

/**
 * Swoole HttpServer 服务器
 * @author Yeoman
 * @since 2016.09.05
 *
 */

define('APP_PATH', realpath(dirname(__FILE__) . '/../'));

class HttpServer
{

    public static $instance;
    private $http;
    private $application;

    /**
     * 初始化
     */
    public function __construct()
    {
        // 创建swoole_http_server对象
        $this->http = new swoole_http_server('0.0.0.0', 9501);
        // 设置参数
        $this->http->set(
            array(
                'worker_num'    => 16,
                'deamonize'     => false,
                'max_request'   => 10000,
                'dispatch_mode' => 1,
            )
        );
        // 绑定WorkerStart
        $this->http->on('WorkerStart', array($this, 'onWorkStart'));
        // 绑定request
        $this->http->on('request', array($this, 'onRequest'));
        // 开启服务器
        $this->http->start();

    }

    /**
     * WorkStart 回调
     */
    public function onWorkStart()
    {
        require APP_PATH . '/app/configs/services.php';
        $this->application = new \Phalcon\Mvc\Application;
        $this->application->setDI($di);
    }

    /**
     * 处理http请求
     */
    public function onRequest($request, $response)
    {
        //注册捕获错误函数
        register_shutdown_function(array($this, 'handleFatal'));
        if ($request->server['request_uri'] == '/favicon.ico' || $request->server['path_info'] == '/favicon.ico') {
            return $response->end();
        }

        $_SERVER = $request->server;

        //构造url请求路径,phalcon获取到$_GET['_url']时会定向到对应的路径，否则请求路径为'/'
        $_GET['_url'] = $request->server['request_uri'];
        if ($request->server['request_method'] == 'GET') {
            foreach ($request->get as $key => $value) {
                $_GET[$key] = $value;
            }
        }
        if ($request->server['request_method'] == 'POST') {
            foreach ($request->post as $key => $value) {
                $_POST[$key] = $value;
            }
        }
        //处理请求
        ob_start();
        try {

            echo $this->application->handle()->getContent();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        $result = ob_get_contents();
        ob_end_clean();
        $response->end($result);
    }

    /**
     * 获取实例对象
     */
    public static function getInstance()
    {
        if (!self::$instance) {

            self::$instance = new HttpServer();
        }

        return self::$instance;
    }

    /**
     * 捕获Server运行期致命错误
     */
    public function handleFatal()
    {
        $error = error_get_last();
        if (isset($error['type'])) {
            switch ($error['type']) {
                case E_ERROR:
                case E_PARSE:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                    $message = $error['message'];
                    $file    = $error['file'];
                    $line    = $error['line'];
                    $log     = "$message ($file:$line)\nStack trace:\n";
                    $trace   = debug_backtrace();
                    foreach ($trace as $i => $t) {
                        if (!isset($t['file'])) {
                            $t['file'] = 'unknown';
                        }
                        if (!isset($t['line'])) {
                            $t['line'] = 0;
                        }
                        if (!isset($t['function'])) {
                            $t['function'] = 'unknown';
                        }
                        $log .= "#$i {$t['file']}({$t['line']}): ";
                        if (isset($t['object']) and is_object($t['object'])) {
                            $log .= get_class($t['object']) . '->';
                        }
                        $log .= "{$t['function']}()\n";
                    }
                    if (isset($_SERVER['REQUEST_URI'])) {
                        $log .= '[QUERY] ' . $_SERVER['REQUEST_URI'];
                    }
                    //error_log($log);
                    //$serv->send($this->currentFd, $log);
                    $this->application->logger->info('error log: ' . $log);
                    $this->response->end($this->currentFd . '_' . $log);
                default:
                    break;
            }
        }
    }
}

HttpServer::getInstance();
