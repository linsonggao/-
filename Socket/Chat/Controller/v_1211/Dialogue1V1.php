<?php
// +----------------------------------------------------------------------
// | 
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace Controller\v_1211;
use Lib\Common;
use Extend\System;
use \GatewayWorker\Lib\Gateway;
class Dialogue1V1 extends Common{
    /**
     * 发送给好友
     *   */
    public function sendToFriend(){
        $data=$this->data["data"]["bodies"];
        //数据入库
        $result=System::model("Dialogue1V1",false)->addDialogueLog($this->data["data"]["bodies"]);
        if (Gateway::isUidOnline($this->data["data"]["bodies"]["to_uid"])){//好友在线 发送消息
            Gateway::sendToUid(
                $this->data["data"]["bodies"]["to_uid"],
                $this->apiJson(array_merge($data,array("msg_1v1_id"=>$result["msg_1v1_id"])), 200, "ok",true,'','',false)
            );
        }
        //TODO 记录用户的未读标记    数据放到redis
        $this->userMsgIncr();
        //返回给当前用户
        $this->sendToClient($this->apiJson(array("msg_1v1_id"=>$result["msg_1v1_id"]), 200, "ok",false));
    }
    /**
     * 获取聊天列表
     *   */
    public function getList(){
        $list=System::model("Dialogue1V1",false)->getDialogueList($this->data["data"]);
        $this->sendToClient($this->apiJson(array("list"=>$list), 200, "ok",false));
    }
    /**
     * 1v1 信息入缓存
     *   */
    private function userMsgIncr(){
        $info   =$this->data["data"]["bodies"];
        $data=array(
            "uid"       =>$info["to_uid"],
            "msg_type"  =>0,
            "id"        =>$info["uid"],
            "number"    =>1,
            "content"   =>$info["content"],
            "add_time"  =>time()
        );
        $GLOBALS["config"]["EXTEND"]["RedisMsg"]->informIncr($data);
    }
}
