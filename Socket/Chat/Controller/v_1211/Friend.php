<?php
// +----------------------------------------------------------------------
// | 好友相关操作
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace Controller\v_1211;
use Lib\Common;
use Extend\Arrays;
use Extend\System;
class Friend extends Common{
    //添加好友
    public function sendAddFriends(){
        $data   =$this->data["data"];
        $reg    =Arrays::regInfoNull($data,array("send_uid","receive_uid","type"));
        if ($reg["code"]!=200){//验证
            $this->sendToClient($this->apiJson('', $reg["code"],$reg["msg"],false));return;
        }
        $Friend=System::model("Friend",false);
        if (!empty($Friend->regInform($data))){
            //已经发送了邀请 不能重复发送
             $this->sendToClient($this->apiJson('',306,"已经发送了邀请,不能重复发送",false));return;
        }
        if (!empty($Friend->regInform(array("send_uid"=>$data["receive_uid"],"receive_uid"=>$data["send_uid"])))){
            //已经接受到邀请 不能重复操作
            $this->sendToClient($this->apiJson('',308,"已经接受到邀请,不能发送。 ",false));return;
        }
        //验证该会员是否在好友里面
        if (!empty($Friend->regUserExist($data["send_uid"],$data["receive_uid"]))){
            //该会员已存在 自己的名单
            $this->sendToClient($this->apiJson('',307,"该会员已存在 自己的名单",false));return;
        }
        $Friend->addInform($data);
        //发送 给接受人 信息
        $this->userMsgIncr($data);
        $this->sendReceive($data["receive_uid"]);
        $this->sendToClient($this->apiJson('',200,"ok",false));
    }
    //确认添加好友
    public function notarizeAddFriends(){
        //这里需要回推一条消息    common_inform_id    status  send_uid  uid
        $data   =$this->data["data"];
        $Friend=System::model("Friend",false);
        if ($data["status"]==1){//通过 加好友
            $Friend->notarizeAddFriends($data["common_inform_id"]);
            //推送投诉 信息
            $this->sendToUid($data["send_uid"],$this->apiJson(array(
                "bodies"=>$Friend->addSystemMsg($data)//推送给发送者 并且保存记录 
            ), 200, "ok",true,"SystemPush","sendToUserAll",true));
        }
        //推送给 发起者
        $Friend->updateCommonInform($data["common_inform_id"],$data["status"]);
        $this->sendToClient($this->apiJson('',200,"ok",false));
    }
    //删除好友
    public function delFriends(){
        $info   =$this->data["data"];//uid  to_uid
        System::model("Friend",false)->delFriends($info);
        $this->sendToClient($this->apiJson('',200,"ok",false));
    }
    //加入黑名单
    public function addBlack(){
        $info   =$this->data["data"];//uid to_uid  relation
        System::model("Friend",false)->addBlack($info);
        $this->sendToClient($this->apiJson('',200,"ok",false));
    }
    //修改备注
    public function updateRemark(){
        $info   =$this->data["data"];//uid to_uid  remark
        System::model("Friend",false)->updateRemark($info);
        $this->sendToClient($this->apiJson('',200,"ok",false));
    }
    /**
     * 信息入缓存
     * @param unknown $info  */
    private function userMsgIncr($info){
        $data=array(
            "uid"       =>$info["receive_uid"],
            "msg_type"  =>3,
            "id"        =>1,
            "number"    =>1,
            "content"   =>'',
            "add_time"  =>time()
        );
        $GLOBALS["config"]["EXTEND"]["RedisMsg"]->informIncr($data);
    }
    /**
     * 发送消息给接受者
     * @param   receive_uid
     *   */
    private function sendReceive($receive_uid){
        $redis_msg      =$GLOBALS["config"]["EXTEND"]["RedisMsg"];
        $list           =$redis_msg->getInformList($receive_uid);
        $total_number   =0;
        $friend_number  =0;
        $group_number   =0;
        foreach ($list["data"]["inform"] as $v){
            $total_number   +=$v["number"];
            if ($v["type"]==1){//加好友 消息
                $friend_number  +=$v["number"];
            }else if($v["type"]==2){
                $group_number   +=$v["number"];
            }
        }
        $data   =array(
            "total_number"  =>$total_number,
            "friend_number" =>$friend_number,
            "group_number"  =>$group_number
        );
        $this->sendToUid($receive_uid, $this->apiJson($data,200,"ok",true,'','',true));
    }
}
