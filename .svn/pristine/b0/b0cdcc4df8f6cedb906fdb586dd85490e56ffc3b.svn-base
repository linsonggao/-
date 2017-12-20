<?php
// +----------------------------------------------------------------------
// | 后台操作的推送
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace Controller;
use Lib\Common;
use Extend\System;
use \GatewayWorker\Lib\Gateway;
class SystemPush extends Common{
    //推送给全部人  系统通知
    public function sendToUserAll(){
        $data=$this->data['data']["bodies"];
        //入库
        $result=System::model("SystemPush")->addSystemMsg($this->data["data"]["bodies"]);
        //权限验证
        $this->sendToUserGroup($this->apiJson(array_merge($data,array("msg_system_id"=>$result["msg_system_id"])), 200, "ok"));
        //返回包
        $this->sendToClient($this->apiJson('', 200, "ok",false));
    }
    
}
