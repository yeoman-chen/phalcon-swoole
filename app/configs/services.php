<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Loader;
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
                'MyApp\Controllers' => __DIR__ . '/../controllers/',
                'MyApp\Controllers\Front' => __DIR__ . '/../controllers/front'
        )
)->register();


// Create a DI
$di = new FactoryDefault();



// Setup the router component
$di->set('router', function () {

	return require __DIR__ . '/routers.php';
},true);
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
$di->set('dispatcher', function(){
	$dispatcher = new Dispatcher();
	$dispatcher->setDefaultNamespace('MyApp\Controllers');
	return $dispatcher;
});