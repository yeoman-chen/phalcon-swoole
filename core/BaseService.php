<?php
/**
 * 基础模型类，所有模型都必须继承此类
 *
 * @package 基础模块处理业务定义接口
 * @author chenym
 * @since 2016.09.14
 */

namespace MyApp\Core;

use \Phalcon\DI;
use \Phalcon\DI\Injectable;

class BaseService extends Injectable
{
    public function __construct()
    {
        $di = DI::getDefault();
        $this->setDI($di);
    }

    /**
     * 打印日志
     * @param string $errmsg 错误信息
     * @param array $fileName 日志文件名称
     */
    public function log($errmsg, $fileName)
    {
        $path     = APP_PATH . '/app/runtime/';
        $filename = $path . $fileName . '.log';
        $fp2      = @fopen($filename, "a");
        fwrite($fp2, date('Y-m-d H:i:s') . '  ' . $errmsg . "\r\n");
        fclose($fp2);
    }
}