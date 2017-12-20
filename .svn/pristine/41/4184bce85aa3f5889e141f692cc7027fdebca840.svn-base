<?php
// +----------------------------------------------------------------------
// | 
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace Controller;
use Lib\Common;
use Extend\System;
class Index extends Common{
    public function index(){
        
        ////{"controller":"Index","method":"index"}\r\n
        /* $a=System::table("demo");
        var_dump($a->find()); */
       // System::model("Demo")->index();
        for($i=0;$i<10;$i++){
            $this->sendToClient(
                $this->apiJson(array("controller"=>"Index","method"=>"index"), 200, "ok")
            );
        }
        
        
        return;
    }
}
