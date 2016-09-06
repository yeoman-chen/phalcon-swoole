<?php
return new \Phalcon\Config(array(
    'database' => [
        'adapter' => 'Mysql',
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname' => 'phalconsmvc',

    ],
    'application'=> [
        'controllersDir' => __DIR__ . '/../../app/controllers/',
        'modelsDir'      => __DIR__ . '/../../app/models/',
        'viewsDir'       => __DIR__ . '/../../app/views/',
        'pluginsDir'     => __DIR__ . '/../../app/plugins/',
        'libraryDir'     => __DIR__ . '/../../app/library/',
        'cacheDir'       => __DIR__ . '/../../app/cache/',
        'baseUri'        => '/mvc/simple-subcontrollers/',
    ],
    'logger' => [
        'application' => APP_PATH . '/app/runtime/application.log',
        'sql'         => APP_PATH . '/app/runtime/sql.log',
    ],
));