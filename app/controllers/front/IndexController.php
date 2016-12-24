<?php
namespace MyApp\Controllers\Front;
use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
	public function indexAction()
	{
		echo 'hello world!';
	}
	public function testAction()
	{
		echo '<h1>Hello Test,Welcome to Phalcon!</h1>';
		print_r($_SERVER);
		$this->view->disable();
	}
	//测试task任务
	public function taskAction()
	{
		header("Content-type: text/html;charset=utf-8");
		$data = ['id' => 123,'content' => '测试task数据'];
		$this->server->task(json_encode($data));
		print_r($data);
	}
}