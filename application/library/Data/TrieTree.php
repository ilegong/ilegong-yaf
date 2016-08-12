<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 15/12/6
 * Time: 下午9:17
 */
namespace Data;

use Db\SSDB\SSDB;
use Help\Loader;

class TrieTree
{

    public $tree = array();

    public function __construct($type = '')
    {
        $tree = SSDB::getInstance()->get('censor_' . $type);
        if (is_null($tree)) {
            Loader::model('Admin', 'Censor');
            $censorModel = new \CensorModel();

            $words = $censorModel->gets(['rule' => $type]);

            foreach ($words as $word) {
                $this->insert($word['find']);
            }
            SSDB::getInstance()->set('censor_' . $type, serialize($this->tree));
        } else {
            $this->tree = unserialize($tree);
        }
    }


    public function insert($utf8_str)
    {
        $chars = UTF8Util::get_chars($utf8_str);
        $chars[] = null;    //串结尾字符
        $count = count($chars);
        $T = &$this->tree;
        for ($i = 0; $i < $count; $i++) {
            $c = $chars[$i];
            if (!array_key_exists($c, $T)) {
                $T[$c] = array();    //插入新字符，关联数组
            }
            $T = &$T[$c];
        }
    }

    public function remove($utf8_str)
    {
        $chars = UTF8Util::get_chars($utf8_str);
        $chars[] = null;
        if ($this->_find($chars)) {    //先保证此串在树中
            $chars[] = null;
            $count = count($chars);
            $T = &$this->tree;
            for ($i = 0; $i < $count; $i++) {
                $c = $chars[$i];
                if (count($T[$c]) == 1) {        //表明仅有此串
                    unset($T[$c]);
                    return;
                }
                $T = &$T[$c];
            }
        }
    }

    private function _find(&$chars)
    {
        $count = count($chars);
        $T = &$this->tree;
        for ($i = 0; $i < $count; $i++) {
            $c = $chars[$i];
            if (!array_key_exists($c, $T)) {
                return false;
            }
            $T = &$T[$c];
        }
        return true;
    }

    public function find($utf8_str)
    {
        $chars = UTF8Util::get_chars($utf8_str);
        $chars[] = null;
        return $this->_find($chars);
    }

    public function contain($utf8_str)
    {
        $chars = UTF8Util::get_chars($utf8_str);
        $chars[] = null;
        $len = count($chars);
        $Tree = &$this->tree;

        $res = array();
        for ($i = 0; $i < $len; $i++) {
            $c = $chars[$i];
            if (array_key_exists($c, $Tree)) {//起始字符匹配
                $T = &$Tree[$c];

                for ($j = $i + 1; $j < $len; $j++) {

                    if (array_key_exists(null, $T)) {
                        $res[] = trim($c);
                    } else {
                        $c2 = $chars[$j];
                        $c .= $c2;
                    }
                    if (!array_key_exists($c2, $T)) {
                        break;
                    }
                    $T = &$T[$c2];
                }
            }
        }
        return $res;
    }

    public function contain_all($str_array)
    {
        foreach ($str_array as $str) {
            if ($this->contain($str)) {
                return true;
            }
        }
        return false;
    }

    public function export()
    {
        return serialize($this->tree);
    }

    public function import($str)
    {
        $this->tree = unserialize($str);
    }


}