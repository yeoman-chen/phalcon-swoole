<?php
/**
 * 基类控制器，所有模块控制器必须继承此类
 * @copyright  Yeoman-chen
 * @author Yeoman
 * @since 2016.09.13
 */

namespace MyApp\Core;

use Phalcon\Mvc\Controller;


class BaseController extends Controller 
{

    /**
	 * 初始化
	 * @author Yeoman
	 * @since 2016.09.09
	 */
	protected function initialize()
	{

	}

	/**
     * 接口返回结果规范
     * @author Yeoman
     * @since 2016.09.09
     * @param string $code 编码
     * @param string $message 消息
     * @param string $content 内容
     * @param string $callback 跨域
     * @return string JSON
     */
    protected function apiResultStandard($code, $message = null, $content = null, $callback = null)
    {
        //自动识别是否存在callback参数
        if (!$callback){
            $callback = $this->request->get('callback', 'string', '');
        }
        $params = array(
            'code'      => $code,
            'message'   => $message,
            'timeStamp' => time(),
            'content'   => $content,
        );
        return $this->echoJson($params, $callback);
    }
    /**
     * 返回json格式数据
     * @author Yeoman
     * @since 2016.09.09 
     * @param mixed $data 任意的数字，布尔值，字符串，数组 或者对象会被编码utf8格式
     * @param string $jsonp 是否jsonp格式
     * @return string JSON
     */
    protected function echoJson($data, $jsonp = "")
    {
        header("Content-type: application/json; charset=utf-8");
        if (empty($jsonp)) {
            echo json_encode($data);
        } else {
            echo $jsonp . '(' . json_encode($data) . ')';
        }
    }
}