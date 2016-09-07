<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\View;

// Register an autoloader
$loader = new Loader();
// $loader->registerDirs(array(
//     __DIR__ . '/../controllers/',
//     __DIR__ . '/../models/',
// ))->register();

$loader->registerNamespaces(
    array(
        'MyApp\Controllers'       => __DIR__ . '/../controllers/',
        'MyApp\Controllers\Front' => __DIR__ . '/../controllers/front',
    )
)->register();

//引入配置文件
$config = include_once APP_PATH . "/app/configs/config.php";

// Create a DI
$di = new FactoryDefault();

$di->setShared('config', function () use ($config) {
    return $config;
});

// Setup the router component
$di->set('router', function () {

    return require __DIR__ . '/routers.php';
}, true);
// Setup the view component
$di->set('view', function () {
    $view = new View();
    $view->setViewsDir('../app/views/');
    return $view;
});

// Setup a base URI so that all generated URIs include the "tutorial" folder
$di->set('url', function () {
    $url = new UrlProvider();
    $url->setBaseUri('/');
    return $url;
});
$di->setShared('logger', function () use ($config) {

    return new FileAdapter($config->logger->application);
});
$di->set('dispatcher', function () {
    $dispatcher = new Dispatcher();
    $dispatcher->setDefaultNamespace('MyApp\Controllers');
    return $dispatcher;
});
