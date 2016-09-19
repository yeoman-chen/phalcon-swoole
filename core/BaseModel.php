<?php

/**
 * 基础模型类，所有模型都必须继承此类
 *
 * @package 基础模块处理业务定义公共函数
 * @author chenym
 * @since 2016.09.14
 */

namespace MyApp\Core;

use \Phalcon\DI;
use \Phalcon\DI\Injectable;

class BaseModel extends Injectable
{

    public function __construct()
    {
        $di = DI::getDefault();
        $this->setDI($di);
    }

    /**
     * 返回表名对应的模型映射
     * @return string
     */
    public function getSource()
    {
        return '';
    }
    /**
     * 插入数据
     * @param array $data 插入数据的数组
     * @return string 最后插入数据的id
     */
    public function insert($data = [])
    {
        return $this->db->insert($this->getSource(), $data);
    }
    /**
     * 更新数据
     * @param array $data 更新数据的数组
     * @param array $where 条件
     * @return boolean true|false
     */
    public function update($data = [], $where = [])
    {
        if (empty($where)) {
            return 0;
        }
        return $this->db->update($this->getSource(), $data, $where);
    }
    /**
     * 查询多条数据
     * @param string $columns 查询字段
     * @param array  $where   条件
     * @return mixed array|null 列表
     */
    public function select($columns, $where)
    {
        return $this->db->select($this->getSource(), $columns, $where);
    }
    /**
     * 查询数量
     * @param array  $where   条件
     * @return number 返回数量
     */
    public function count($where)
    {
        return $this->db->count($this->getSource(), $where);
    }
    /**
     * 删除数据
     * @param array  $where   条件
     * @return boolean true|false 
     */
    public function delete($where)
    {
        if (empty($where)) {
            return 0;
        }
        return $this->db->delete($this->getSource(), $where);
    }
    /**
     * 查询单条数据
     * @param array  $where   条件
     * @return mixed array|null 单条数据
     */
    public function get($columns, $where)
    {
        return $this->db->get($this->getSource(), $columns, $where);
    }

    public function has($where)
    {
        return $this->db->has($this->getSource(),$where);
    }
    /**
     * 取指定列最大值
     * @param string $column 
     * @param array $where
     * @return number 最大值
     */
    public function max($column = '', $where = [])
    {
        return $this->db->max($this->getSource(), $column, $where);
    }
    /**
     * 取指定列最小值
     * @param string $column
     * @param array $where
     * @return number 最小值
     */
    public function min($column = '', $where = [])
    {
        return $this->db->min($this->getSource(), $column, $where);
    }
}
