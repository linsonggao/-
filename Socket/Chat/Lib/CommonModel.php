<?php 
// +----------------------------------------------------------------------
// | 公共继承模型
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace Lib;
use Extend\System;
class CommonModel{
    public function __construct(){
        return System::table();
    }
}

?>