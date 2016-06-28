<?php
return new \Phalcon\Config(array(
    'database' => array(
        'adapter' => 'Mysql',
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname' => 'phalconsmvc',

    ),
    'application'=> array(
        'controllersDir' => APP_PATH.'/../controllers/',
        'modelsDir' => APP_PATH. '/../models/',
        'viewsDir' => APP_PATH . '/../views/',
        'libraryDir' => APP_PATH . '/../library/', 
        'pluginsDir' => APP_PATH . '/../plugin/', 
        'baseUri' => '/'
    ),
));