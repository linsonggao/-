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
        $msg_group_id=System::model("DialogueGroup",false)->addDialogueLog($data)["msg_group_id"];
        //发送到群组
        $data_arr=array_merge($data,array("msg_group_id"=>$msg_group_id));
        $this->sendToFlockGroupClient(
            $data["group_id"],
            $this->apiJson($data_arr, 200, "ok",true,'','',true)
        );
        //数据添加
        $this->userMsgIncr($data_arr);
        //end
        $this->sendToClient($this->apiJson(array("msg_group_id"=>$msg_group_id), 200, "ok",false));
    }
    /**
     * 群组 信息入缓存
     *   */
    private function userMsgIncr($data_arr){
        $redis=$GLOBALS["config"]["EXTEND"]["RedisMsg"];
        $uid_str_table=$GLOBALS["config"]["REDIS_TABLE"]["group:uid_str:"]["name"];
        $uid_str_list=explode(",",$redis->_redisRun->get($uid_str_table.$data_arr["group_id"]),-1);
        foreach ($uid_str_list as $v){//数据添加
            if ($v!=$data_arr["uid"]){
                $GLOBALS["config"]["EXTEND"]["RedisMsg"]->informIncr(array(
                    "uid"       =>$data_arr["uid"],
                    "id"        =>$data_arr["group_id"],
                    "number"    =>1,
                    "msg_type"  =>1,
                    "content"   =>$data_arr["content"],
                    "add_time"  =>time()
                ));
            }
        }  
    }
    /**
     * 获取 群聊信息列表
     *   */
    public function getList(){
        $list=System::model("DialogueGroup",false)->getDialogueList($this->data["data"]);
        $this->sendToClient($this->apiJson(array("list"=>$list), 200, "ok",false));
    }
}
