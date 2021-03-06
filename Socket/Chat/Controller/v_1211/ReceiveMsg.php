<?php
// +----------------------------------------------------------------------
// | 接收到的聊天信息
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace Controller\v_1211;
use Lib\Common;
use \GatewayWorker\Lib\Gateway;
use Extend\System;
class ReceiveMsg extends Common{
    /**
     * 接收到的1v1聊天id
     *   */
    public function receive1v1(){
        $info   =$this->data["data"];
        $data=array(
            "uid"=>$info["to_uid"],
            "msg_type"=>0,
            "id"=>$info["uid"],
            "number"=>substr_count($info["msg_id"],","),
        );
        $GLOBALS["config"]["EXTEND"]["RedisMsg"]->informDec($data);
        //修改数据库状态
     //   System::model("ReceiveMsg",false)->updateMsgStatus($info["msg_id"]);
        //待写 修改数据状态
        $this->sendToClient($this->apiJson('', 200, "ok",false));
    }
    /**
     * 接受到群组消息
     *   */
    public function receiveGroup(){
        $info   =$this->data["data"];
        $data=array(
            "uid"=>$info["uid"],
            "msg_type"=>1,
            "id"=>$info["group_id"],
            "number"=>substr_count($info["msg_id"],","),
        );
        $GLOBALS["config"]["EXTEND"]["RedisMsg"]->informDec($data);
        $this->sendToClient($this->apiJson('', 200, "ok",false));
    }
    /**
     * 接受到 系统消息后返回
     *   */
    public function receviceSystem(){
        $info   =$this->data["data"];
        $data=array(
            "uid"=>$info["uid"],
            "msg_type"=>2,
            "id"=>$info["system_uid"],
            "number"=>substr_count($info["msg_system_uid"],","),
        );
        $GLOBALS["config"]["EXTEND"]["RedisMsg"]->informDec($data);
        $this->sendToClient($this->apiJson('', 200, "ok",false));
    }
    /**
     * 接受到 消息后返回
     *   */
    public function receviceInform(){
        $info   =$this->data["data"];
        $data=array(
            "uid"=>$info["uid"],
            "msg_type"=>3,
            "id"=>$info["type"],
            "number"=>1,
        );
        $GLOBALS["config"]["EXTEND"]["RedisMsg"]->informDec($data);
        $this->sendToClient($this->apiJson('', 200, "ok",false));
    }
}
