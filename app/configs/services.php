<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\View;

// Register an autoloader
$loader = new Loader();
$loader->registerDirs(array(
    '../app/controllers/',
    '../app/models/',
))->register();

// Create a DI
$di = new FactoryDefault();

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
