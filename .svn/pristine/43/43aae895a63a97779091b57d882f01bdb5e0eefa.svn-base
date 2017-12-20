<?php 
// +----------------------------------------------------------------------
// | 数据封包解包
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace Extend;
class Pack{
    //分隔符
    private  $_symbol='\r\n';
    //配置文件
    public  $_config=array();
    //需要操作的数据
    private  $_data;
    public function __construct($config,$data){
        $this->_config  =$config;
        $this->_data    =$data;
    }
    /**
     * 解包操作
     *   */
    public function dePack(){
        $json_data=array();
        //先解包 在解密
        foreach (explode($this->_symbol,$this->_data,-1) AS $v){
            if ($this->_config["DEBUG"]==false){//加密
                $json_data[]=json_decode($this->_config["EXTEND"]["MCrypt"]->decrypt(str_replace($this->_symbol,"",$v)),true);
                continue;
            } 
            $json_data[]=json_decode($v,true);
        }
        return $json_data;
    }
    /**
     * 封包
     *   */
    public function enPack($data){
        if ($this->_config["DEBUG"]==false){
            $data=$this->_config["EXTEND"]["MCrypt"]->encrypt($data);
        }
        return $data.$this->_symbol;
    }
}

?>