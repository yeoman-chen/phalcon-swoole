<?php

error_reporting(E_ALL);
date_default_timezone_set('Asia/Shanghai');

define('APP_PATH', realpath('..'));

use Phalcon\Mvc\Application;

try{

	 /**
     * Include services
     */
    require APP_PATH . '/app/configs/services.php';

    /**
     * Handle the request
     */
    $application = new Application();

    /**
     * Assign the DI
     */
    $application->setDI($di);

    echo $application->handle()->getContent();
}catch(Phalcon\Exception $e){

	echo $e->getMessage();
}catch(PDOException $e){

	echo $e->getMessage();
}