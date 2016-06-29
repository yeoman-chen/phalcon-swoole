<?php
//use Phalcon\Mvc\Router;
//$router = new Phalcon\Mvc\Router();

// Create the router
$router = new Phalcon\Mvc\Router(false);

$router->add('/front/:controller/:action', [
    'namespace'  => 'SmvcApp\Controllers\Front',
    'controller' => 1,
    'action'     => 2,
    //'params'     => 3,
]);


$router->add('/:controller/:action', [
    'namespace'  => 'SmvcApp\Controllers',
    'controller' => 1,
    'action'     => 2,
    //'params'     => 3,
]);

return $router;

