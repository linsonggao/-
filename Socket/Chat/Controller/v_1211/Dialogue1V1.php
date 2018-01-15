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
use Extend\Msg;
use \GatewayWorker\Lib\Gateway;
use Extend;
class Dialogue1V1 extends Common{
    /**
     * 发送给好友
     * TODO  待做好友验证     黑名单验证
     *   */
    public function sendToFriend(){
        $data   =$this->data["data"]["bodies"];
        $remsg =Msg::serializeContent($data);
        //分离系统客服和用户
        if ($data["scene_type"]==1){//普通
            //验证 是否是好友 和 黑名单规则
            $Dialogue1V1=System::model("Dialogue1V1",false);
            $reg        =$Dialogue1V1->regUserRelation($data["uid"],$data["to_id"]);
            if ($reg["code"]!=200){//验证是否是好友
                $this->sendToClient($this->apiJson('', $reg["code"], $reg["msg"],false));return;
            }
            $result=$Dialogue1V1->addDialogueLog(array_merge($data,array("content"=>$remsg["content"])));
            //TODO 记录用户的未读标记    数据放到redis
            $this->userMsgIncr($remsg["short_content"]);
        }else if($data["scene_type"]==3){//TODO 待开发 系统客服
            
        }
        if (Gateway::isUidOnline($data["to_id"])){//好友在线 发送消息
            Gateway::sendToUid(
                $this->data["data"]["bodies"]["to_id"],
                $this->apiJson(array_merge($data,array("msg_id"=>$result["msg_id"])), 200, "ok",true,'','',true)
            );
        }
        //返回给当前用户
        $this->sendToClient($this->apiJson(array("msg_id"=>$result["msg_id"]), 200, "ok",false));
    }
    /**
     * 获取聊天列表
     *   */
    public function getList(){ 
        $list=Msg::unserializeContent(System::model("Dialogue1V1",false)->getDialogueList($this->data["data"]),1);
        $this->sendToClient($this->apiJson(array("list"=>$list), 200, "ok",false));
    }
    /**
     * 1v1 信息入缓存
     *   */
    private function userMsgIncr($content){
        $info   =$this->data["data"]["bodies"];
        $data=array(
            "uid"       =>$info["to_id"],
            "msg_type"  =>0,
            "id"        =>$info["uid"],
            "number"    =>1,
            "content"   =>$content,
            "add_time"  =>time()
        );
        $GLOBALS["config"]["EXTEND"]["RedisMsg"]->informIncr($data);
    }
}
