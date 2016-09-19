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
		print_r($_POST);
		$this->view->disable();
	}
}