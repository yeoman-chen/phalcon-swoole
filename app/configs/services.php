<?php
/**
 * 注册服务类
 * @author Yeoman
 * @since 2016.09.19
 */

use Phalcon\DI\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\View;

/**
 * 注册自动加载器
 */
$loader = new Loader();

/**
 * 注册命名空间，对应目录下的文件会自动加载
 */
$loader->registerNamespaces(
    array(
        'MyApp\Controllers'       => __DIR__ . '/../controllers',
        'MyApp\Controllers\Front' => __DIR__ . '/../controllers/front',
        'MyApp\Library'           => __DIR__ . '/../library',
    )
)->register();

/**
 * 引入配置文件
 */
$config = include_once APP_PATH . "/app/configs/config.php";

/**
 * 创建依赖对象服务DI
 */
$di = new FactoryDefault();
/**
 * 把config注册为共享服务
 */
$di->setShared('config', function () use ($config) {
    return $config;
});

/**
 * 设置路由组件
 */
$di->set('router', function () {

    return require __DIR__ . '/routers.php';
}, true);
/**
 * 设置视图组件
 */
$di->set('view', function () {
    $view = new View();
    $view->setViewsDir('../app/views/');
    return $view;
});

/**
 * 把url组件设置为共享服务，该URL组件用来产生各种URL的应用程序
 */
$di->set('url', function () {
    $url = new UrlProvider();
    $url->setBaseUri('/');
    return $url;
});
/**
 * 设置日志组件为共享服务
 */
$di->setShared('logger', function () use ($config) {

    return new FileAdapter($config->logger->application);
});
/**
 * 注册路由分发器
 */
$di->set('dispatcher', function () {
    $dispatcher = new Dispatcher();
    $dispatcher->setDefaultNamespace('MyApp\Controllers');
    return $dispatcher;
});
/**
 * 考虑性能和安全问题，使用轻量级Medoo Orm框架
 */
$di->setShared('db', function () use ($config) {
    $dbConfig = $config->database->toArray();

    $dns = [
        'database_type' => $dbConfig['adapter'],
        'database_name' => $dbConfig['dbname'],
        'server'        => $dbConfig['host'],
        'username'      => $dbConfig['username'],
        'password'      => $dbConfig['password'],
        'charset'       => $dbConfig['charset'],
        'port'          => $dbConfig['port'],
    ];
    $db = new Medoo($dns);

    unset($dbConfig);
    return $db;
});

/**
 * 设置缓存服务
 */
$di->setShared('cache', function () {
    //创建前端数据，并设置2天的缓存时间
     $frontCache = new \Phalcon\Cache\Frontend\Data(array(
        "lifetime" => 172800
     ));

     //创建缓存设置redis的连接选项
     $cache = new Phalcon\Cache\Backend\Redis($frontCache, array(
        'host' => 'localhost',
        'port' => 6379,
        //'auth' => 'foobared',
        //'persistent' => false
     ));
    return $cache;
});
