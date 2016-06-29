<?php
namespace SmvcApp\Controllers\Front;
use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
	public function indexAction()
	{
		echo 'hello world!';
	}
}