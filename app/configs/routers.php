<?php
/**
 * 路由定义
 */
$router = new Phalcon\Mvc\Router(false);
/**
 * 设置默认路由
 */
$router->setDefaultNamespace('MyApp\Controllers');
$router->setDefaultController('index');
$router->setDefaultAction('index');

$router->add('/front/:controller/:action', [
    'namespace'  => 'MyApp\Controllers\Front',
    'controller' => 1,
    'action'     => 2,
    //'params'     => 3,
]);


$router->add('/:controller/:action', [
    'namespace'  => 'MyApp\Controllers',
    'controller' => 1,
    'action'     => 2,
    //'params'     => 3,
]);

return $router;