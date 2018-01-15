<?php
// +----------------------------------------------------------------------
// | 群组消息处理
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace Controller\v_1211;
use Extend\Msg;
use Lib\Common;
use Extend\System;
use \GatewayWorker\Lib\Gateway;
class DialogueGroup extends Common{
    /**
     * 接受到的群组消息
     *   */
    public function sendToGroup(){
        //1.加入数据    发送到群组
        $data=$this->data["data"]["bodies"];
        $remsg =Msg::serializeContent($data);
        //验证是否是群员
        $reg   =System::model("DialogueGroup",false)->regUserGroup($data["group_id"],$data["uid"]);
        if ($reg==false){
            $this->sendToClient($this->apiJson('', 310, "你已经离开了该群,不能发送消息",false));return;
        }
        $msg_id=System::model("DialogueGroup",false)->addDialogueLog(array_merge($data,array("content"=>$remsg["content"])))["msg_id"];
        //发送到群组
        $data_arr=array_merge($data,array("msg_id"=>$msg_id));
        $this->sendToFlockGroupClient(
            $data["to_id"],
            $this->apiJson($data_arr, 200, "ok",true,'','',true)
        );
        //数据添加
        $this->userMsgIncr($data_arr);
        //end
        $this->sendToClient($this->apiJson(array("msg_id"=>$msg_id), 200, "ok",false));
    }
    /**
     * 群组 信息入缓存
     *   */
    private function userMsgIncr($data_arr){
        $redis          =$GLOBALS["config"]["EXTEND"]["RedisMsg"];
        $uid_str_table  =$GLOBALS["config"]["REDIS_TABLE"]["group:uid_str:"]["name"];
        $uid_str_list   =explode(",",$redis->_redisRun->get($uid_str_table.$data_arr["to_id"]),-1);   
        $res_content    =Msg::serializeContent($data_arr);
        foreach ($uid_str_list as $v){//数据添加
            if ($v!=$data_arr["uid"]){
                $GLOBALS["config"]["EXTEND"]["RedisMsg"]->informIncr(array(
                    "uid"       =>$v,
                    "id"        =>$data_arr["to_id"],
                    "number"    =>1,
                    "msg_type"  =>1,
                    "content"   =>$res_content["short_content"],
                    "add_time"  =>time()
                ));
            }
        }  
        //设置群聊天 最新的一条信息
        $redis->_redisRun->set($GLOBALS["config"]["REDIS_TABLE"]["group:newcontent:"]["name"].$data_arr["to_id"],$res_content["short_content"]);
    }
    /**
     * 获取 群聊信息列表
     *   */
    public function getList(){
        $list=Msg::unserializeContent(System::model("DialogueGroup",false)->getDialogueList($this->data["data"]),2);
        $this->sendToClient($this->apiJson(array("list"=>$list), 200, "ok",false));
    }
}
