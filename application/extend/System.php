<?php
// +----------------------------------------------------------------------
// | 系统操作
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace app\extend;
use think\Request;
trait System{
    protected $_v;//版本号
    protected $_request;//获取到路由的基本信息
    //入口
    protected function init(){
        $this->getVersions();
    }
    //取基本信息
    private function getVersions(){
        $this->_request  = Request::instance();
        $this->_v        = explode(".",strtolower($this->_request->controller()))[0];
    }
    /**
     * 实例化模型
     * @param unknown $table  */
    protected function _model($table){
        return model("api/{$this->_v}/{$table}");
    }
    
    
}