<?php

/**
 * Swoole HttpServer 服务器
 * @author Yeoman
 * @since 2016.09.05
 *
 */
namespace MyApp\Core;

defined('APP_PATH') or define('APP_PATH', realpath(dirname(__FILE__) . '/../'));

class HttpServer
{

    public static $instance;
    private $httpServer;
    private $application;
    public $pidFile; //进程文件
    public $psName; //进程前缀名称
    public $config = [ 
                'worker_num'    => 4, //启动的worker进程数
                'deamonize'     => false, 
                'max_request'   => 10000,//worker进程的最大任务数,超过任务数后自动退出
                'task_worker_num' => 4, //设置task worker数量
                'dispatch_mode'   => 1,
                'log_file'        => APP_PATH . '/app/runtime/httpserver.log'
                 ];

    /**
     * 初始化
     *
     */
    public function __construct($port)
    {
        $this->pidFile = APP_PATH . "/app/runtime/sw-{$port}.pid";
        $this->psName = 'pha_swoole_http_server';

    }
     /**
     * 初始化
     *
     */
    private function startServer($port)
    {
         // 创建swoole_http_server对象
        $this->httpServer = new \swoole_http_server('0.0.0.0', $port);
        // 设置参数
        $this->httpServer->set(
            array(
                'worker_num'    => $this->config['worker_num'], //启动的worker进程数
                'deamonize'     => $this->config['deamonize'], 
                'max_request'   => $this->config['max_request'],//worker进程的最大任务数,超过任务数后自动退出
                'task_worker_num' => $this->config['task_worker_num'], //设置task worker数量
                'dispatch_mode'   => $this->config['dispatch_mode'],
                'log_file'        => $this->config['log_file'],
            )
        );
        // 绑定master Start
        $this->httpServer->on('start', array($this, 'onStart'));
        // 绑定 Manager Start
        $this->httpServer->on('managerStart', array($this, 'onManagerStart'));
        // 绑定WorkerStart
        $this->httpServer->on('workerStart', array($this, 'onWorkStart'));
        // 绑定request
        $this->httpServer->on('request', array($this, 'onRequest'));
        // 绑定task
        $this->httpServer->on('task', array($this, 'onTask'));
        // 绑定finish
        $this->httpServer->on('finish', array($this, 'onFinish'));
        // 绑定shutdown
        $this->httpServer->on('shutdown', array($this, 'onShutdown'));
        // 开启服务器
        $this->httpServer->start();
    }
    /**
     * swoole-server master start
     * 主进程启动回调
     * @param $server
     */
    public function onStart($server)
    {
        echo 'Date:' . date('Y-m-d H:i:s') . "\t swoole_http_server master worker start \n";
        $this->setProcessName($this->psName .'-master');
        //记录进程id,脚本实现自动重启
        $pid = "{$this->httpServer->master_pid}-{$this->httpServer->manager_pid}";
        file_put_contents($this->pidFile, $pid);
    }
    /**
     * swoole-server manager start 
     * 管理进程启动回调
     * @param $server
     */
    public function onManagerStart($server)
    {
        echo 'Date:' . date('Y-m-d H:i:s') . "\t swoole_http_server manager worker start \n";
        $this->setProcessName($this->psName .'-manager');
    }
    /**
     * worker start 加载业务脚本常驻内存
     */
    public function onWorkStart($server, $workerId)
    {
        //设置进程的名称
        if($workerId >= $this->config['worker_num']){
            $this->setProcessName($this->psName . '-task-'.($workerId-$this->config['worker_num']));
        }else{
            $this->setProcessName($this->psName . '-worker-'.$workerId);
        }
        require APP_PATH . '/app/configs/services.php';
        //server注入容器
        $httpServer = $this->httpServer;
        $di->setShared('httpServer', function () use ($httpServer) {
            return $httpServer;
        });
        
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

        if ($request->server['request_method'] == 'GET' && isset($request->get)) {
            foreach ($request->get as $key => $value) {
                $_GET[$key] = $value;
                $_REQUEST[$key] = $value;
            }
        }
        if ($request->server['request_method'] == 'POST' && isset($request->post) ) {
            foreach ($request->post as $key => $value) {
                $_POST[$key] = $value;
                $_REQUEST[$key] = $value;
            }
        }
        if(isset($request->header)){
            foreach ((array)$request->header as $key => $value) {
                $_SERVER[$key] = $value;
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
     * 处理task任务
     */
    public function onTask($server, $task_id, $from_id, $data)
    {
        echo "[".date('Y-m-d H:i:s')."] This Task {$task_id} from Worker {$from_id}\n";
        echo "This data {$data} from Worker {$from_id}\n";
        $data = json_decode($data,true);
        //模拟耗时任务，使用业务邮件发送、信息广播等
        if(isset($data['cmd']) && $data['cmd'] == 'sleep'){
            $sleepTime = isset($data['sleepTime']) ? (int)$data['sleepTime'] : 1;
            sleep($sleepTime);
        }
        return true;//必须有return 否则不会调用onFinish
    }
    /**
     * task 完成回调
     */
    public function onFinish($server,$taskId, $data)
    {
        echo "Task {$taskId} finish\n";
        echo "Result: {$data}\n";
    }
    /**
     * Server结束时回调
     */
    public function onShutdown($server)
    {
        unlink($this->pidFile);
        echo 'Date:' . date('Y-m-d H:i:s') . "\t swoole_http_server shutdown \n";
    }
    /**
     * 获取实例对象
     */
    public function getInstance($port)
    {
        if (!self::$instance) {

            self::$instance = $this->startServer($port);
        }

        return self::$instance;
    }
    /**
     *
     * 设置进程的名称
     * @param string $name 进程名称
     */
    private function setProcessName($name){
        // Mac OS 不支持经常重命名
        if(PHP_OS == 'Darwin'){
            return false;
        }
        if(function_exists('cli_set_process_title')){
            cli_set_process_title($name);
        }else{
            if(function_exists('swoole_set_process_name')){
                swoole_set_process_name($name);
            }else{
                throw new \Exception(__METHOD__ .' Failed ! Require cli_set_process_title|swoole_set_process_name');
            }
        }
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
    /**
     * 重启swoole-task服务
     * 重启worker进程
     */
    public function reloadServer()
    {
        echo "Reloading...";
        $pid = exec("pidof " . $this->psName .'-manager');
        exec("kill -USR1 {$pid}");
        echo "Reloaded";
    }
    /**
     * 
     * 停止swoole-task服务,
     */
    public function stopServer()
    {
        echo "Stopping...";
        $pid = exec("pidof " . $this->psName .'-master');
        exec("kill -TERM {$pid}");
        echo "Stopped";
    }
}