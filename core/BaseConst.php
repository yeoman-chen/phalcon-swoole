<?php

/**
 * 常量定义类
 * @copyright  Yeoman-chen
 * @author Yeoman
 * @since 2016.09.21
 */

namespace MyApp\Core;

class BaseConst {

	// 请求成功
	const SUCCESS_CODE = 1000;
	// 请求失败
	const ERROR_CODE = 1001;
	// 非法访问
	const ERROR_AUTH_CODE = 1002;
	// 参数错误
	const ERROR_PARAM_CODE = 1003;
	// 请求成功
	const SUCCESS_MESSAGE = "请求成功";
	// 请求失败
	const ERROR_MESSAGE = "请求失败";
	// 非法访问
	const ERROR_AUTH_MESSAGE = "非法访问";
	// 参数错误
	const ERROR_PARAM_MESSAGE = "参数错误";
}