<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 15/7/15
 * Time: 下午7:04
 */

namespace Core;

use Valitron\Validator;

class M
{
    protected $_table = NULL;
    protected $_primaryKey = 'id';
    protected $_db = NULL;
    protected $_cacheConn = null;
    protected $_cacheExitType = '';
    protected $_cache = false;
    protected $_prefix = "cc_";
    protected $_life_time = -1;

    protected $_rules = [
        'insert' => [],
        'update' => []
    ];

    public function __construct()
    {
        $this->_table = $this->_prefix . $this->_table;
        $this->_db = \Db\Medoo\Medoo::getInstance();
        if ($this->_cacheExitType == "SSDB" && $this->_cache) {
            $this->_cacheConn = new \Cache\SSDB();
        } elseif ($this->_cacheExitType == "Redis" && $this->_cache) {
            $this->_cacheConn = new \Cache\Redis();
        }
    }

    public function getById($id, $params = "*")
    {
        $key = $id . json_encode($params);
        if ($this->_cache) {
            $tmp = $this->_cacheConn->get($key, $this->_table);
            if ($tmp) {
                return $tmp;
            } else {
                $where = array($this->_primaryKey => $id);
                $result = $this->_db->select($this->_table, $params, $where);
                $this->_cacheConn->set($key, $result == null ? false : $result, $this->_table, $this->_life_time);
                return $result == null ? false : $result;
            }
        }
        $where = array($this->_primaryKey => $id);
        $result = $this->_db->select($this->_table, $params, $where);
        return $result == null ? false : $result[0];
    }

    public function gets($where, $params = "*")
    {
        $key = json_encode($where) . json_encode($params);
        if ($this->_cache) {
            $tmp = \Cache\File::get($key, $this->_table);
            if ($tmp) {
                return $tmp;
            } else {
                $result = $this->_db->select($this->_table, $params, $where);
                \Cache\File::set($key, $result, $this->_table, $this->_life_time);
                return $result;
            }
        }

        return $this->_db->select($this->_table, $params, $where);
    }

    public function insert($insert)
    {
        /*if(!$this->_rules['insert'])
        {
            return 0;
        }*/
        if ($this->_cache) {
            \Cache\File::delete('*', $this->_table);
        }

        /*$v = new Validator($insert);
        foreach ($this->_rules['insert'] as $key => $rule) {
            $v->rule($key,$rule);
        }*/

        return $this->_db->insert($this->_table, $insert);
    }

    public function lists($page, $limit, $where, $params = "*")
    {
        $result = array();
        //总条数
        $result["count"] = $this->_db->count($this->_table, $where);
        //列表
        $where['LIMIT'] = array($limit * ($page - 1), $limit);
        $result["rows"] = $this->_db->select($this->_table, $params, $where);

        return $result;
    }

    /**
     * @param $where
     * @return int 行数
     */
    public function count($where)
    {
        $key = json_encode($where);
        if ($this->_cache) {
            $tmp = \Cache\File::get($key, $this->_table);
            if ($tmp) {
                return $tmp;
            } else {
                $result = $this->_db->count($this->_table, $where);
                \Cache\File::set($this->_table, $key, $result, $this->_life_time);
                return $result;
            }
        }
        return $this->_db->count($this->_table, $where);
    }

    /**
     * @param $where
     * @return int 被删除的行数
     */
    public function del($where)
    {
        if ($this->_cache) {
            \Cache\File::delete('*', $this->_table);
        }
        return $this->_db->delete($this->_table, $where);
    }

    public function softDelete($where)
    {
        return $this->_db->update($this->_table,['is_delete'=>1],$where);
    }

    /**
     * @param $data
     * @param $where
     * @return int 受影响的行数
     */
    public function update($data, $where)
    {
        if ($this->_cache) {
            \Cache\File::delete('*', $this->_table);
        }
        return $this->_db->update($this->_table, $data, $where);
    }

    public function error()
    {
        return $this->_db->error();
    }

    public function query($query)
    {
        return $this->_db->query($query)->fetchAll();
    }

    public function lastQuery()
    {
        return $this->_db->last_query();
    }

    public function sum($column, $where)
    {
        $key = json_encode($column) . json_encode($where);
        if ($this->_cache) {
            $tmp = \Cache\File::get($key, $this->_table);
            if ($tmp) {
                return $tmp;
            } else {
                $result = $this->_db->sum($this->_table, $column, $where);
                \Cache\File::set($this->_table, $key, $result, $this->_life_time);
                return $result;
            }
        }
        return $this->_db->sum($this->_table, $column, $where);
    }

    public function replace($column, $search, $replace, $where)
    {
        if ($this->_cache) {
            \Cache\File::delete('*', $this->_table);
        }
        return $this->_db->replace($this->_table, $column, $search, $replace, $where);
    }

    public function insertSql($sql)
    {
        return $this->_db->insertSql($sql);
    }

}
