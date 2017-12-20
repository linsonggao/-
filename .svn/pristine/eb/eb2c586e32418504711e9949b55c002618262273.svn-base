<?php 
// +----------------------------------------------------------------------
// | 公共系统操作
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace Extend;
class System{
    public static $_v;
    /**
     * 实例化 db
     * @param string $name
     * @param string $tablePrefix
     * @param string $connection  */
    public static function table($name='', $tablePrefix='',$connection='') {
        static $_model  = array();
        if(strpos($name,':')) {
            list($class,$name)    =  explode(':',$name);
        }else{
            $class      =   'thinkdb\\Model';
        }
        $guid           =   (is_array($connection)?implode('',$connection):$connection).$tablePrefix . $name . '_' . $class;
        if (!isset($_model[$guid]))
            $_model[$guid] = new $class($name,$tablePrefix,$connection);
        return $_model[$guid];
    }
    /**
     * 实例化model
     * @param unknown $table
     * @param string $is_common  */
    public static function model($table,$is_common=true){
        if ($is_common==true){//公共版本
            $m="Model\\".$table;
        }else{//版本模型
            $m="Model\\".self::$_v."\\".$table;
        }
        return new $m();
    }
}

?>