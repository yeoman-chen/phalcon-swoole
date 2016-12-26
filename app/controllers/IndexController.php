<?php
namespace MyApp\Controllers;
use Phalcon\Mvc\Controller;
use MyApp\Core\BaseController;

class IndexController extends BaseController
{

    public function indexAction()
    {
        echo "<h1>Hello!</h1>";
    }

    public function testAction()
    {
    	echo "test";
    	print_r($_HERDER);
    }
    public function taskAction()
    {
    	$data = ['cmd' => 'sleep','sleepTime' => 5,'reqTime' => time(),'from' => 'http','data' => 'Task Test'];
    	$this->httpServer->task(json_encode($data));
    	//echo json_encode($data);
    	$this->apiResultStandard(1000,'请求成功!',$data);
    }

}