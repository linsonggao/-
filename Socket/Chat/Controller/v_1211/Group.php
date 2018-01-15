<?php
// +----------------------------------------------------------------------
// | 群组操作
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
class Group extends Common{
    /**
     * 创建群聊
     * @param   uid 创建这uid
     * @param   uid_join    成员串
     * @return  ok
     *   */
    public function createGroup(){
        $data   =$this->data["data"];
        $info   =System::model("Group",false)->createGroup($data["uid"],$data["uid_join"]);
        //存入 缓存
        $this->setUidJoin($info["group_id"],$data["uid"].",".$data["uid_join"]);
        $this->sendToClient($this->apiJson($info, 200, "ok",false));
    }
    /**
     * 解散群组
     * @param   uid         创建这uid
     * @param   group_id    群主id
     * @return  ok
     *   */
    public function unGroup(){
        $data   =$this->data["data"];
        $info   =System::model("Group",false)->unGroup($data["uid"],$data["group_id"]);
        $this->sendToClient($this->apiJson('', $info["code"], $info["msg"],false));
        //删除 缓存串
        $GLOBALS["config"]["EXTEND"]["RedisMsg"]->delUidJoin($data["group_id"]);
        $this->sendToClient($this->apiJson('', 200, "ok",false));
    }
    /**
     * 拉人入群
     * @param   uid         创建这uid
     * @param   group_id    群主id
     * @param   to_uid      成员串  如:1,2,  
     * @return  ok
     *   */
    public function addUserGroup(){
        //uid   to_uid  group_id  
        $data       =$this->data["data"];
        $model      =System::model("Group",false);
        $group_uid  =$model->getGroupUid($data["group_id"]);//获取群主id
        if ($group_uid==$data["uid"]){//群主
            $this->_addUserGroup($data,$model);
        }else{//群员
            if ($model->getGruopAuth($data["group_id"])==1){//开启验证
                $push_uid   =$model->addInform($data);
                //推送给管理员
                $this->userMsgIncr($push_uid, $group_uid,$data["group_id"]);
            }else{//关闭验证  直接加入好友
                $this->_addUserGroup($data,$model);
            }
        }
        $this->sendToClient($this->apiJson('', 200, "ok",false));
    }
    /**
     * 信息入缓存 并且推送
     * @param unknown $push_uid 
     * @param unknown $group_uid 
     *  */
    private function userMsgIncr($push_uid,$group_uid,$group_id){
        foreach ($push_uid as $v){
            $data=array(
                "uid"       =>$group_uid,
                "msg_type"  =>3,
                "id"        =>2,
                "number"    =>1,
                "content"   =>'',
                "add_time"  =>time()
            );
            $v["group_id"]=$group_id;
            $GLOBALS["config"]["EXTEND"]["RedisMsg"]->informIncr($data);
            //推送到群内部
            $result=$GLOBALS["config"]["EXTEND"]["MsgTemp"]->getTemp(8,$v)["data"];
            $this->sendToFlockGroupClient($group_id,$this->apiJson(array("bodies"=>$result), 200, "ok",true,"SystemPush","sendToUserGroup",true));
        }
        //推送
        /* $this->sendToUid($group_uid, $this->apiJson(array(
            "receive_uid"   =>$group_uid,
            "status"        =>0
        ),200,"ok",true,'','',true)); */
    }
    /**
     * 添加操作 直接入群
     * @param unknown $data 数据
     * @param unknown $model    模型
     *  */
    private function _addUserGroup($data,$model){
        //加入提示

        $uid_str=$GLOBALS["config"]["EXTEND"]["RedisMsg"]->getUidJoin($data["group_id"]).$model->addUserGroup($data["to_uid"],$data["group_id"])["uid_str"];
        $this->setUidJoin($data["group_id"],$uid_str);
        return mb_substr($uid_str,0,strlen($uid_str)-1);
    }
    //  status  common_inform_id    send_uid//对方uid    uid
    /**
     * 管理员操作 进群
     * @param unknown status 状态
     * @param unknown send_uid    申请者uid串  如:1,2,
     * @param unknown common_inform_id    通知主id
     * @param unknown uid   管理员id
     *  */
    public function notarizeaddUserGroup(){
        //确认进群操作    
        $data       =$this->data["data"];
        $group_id   =System::model("Group",false)->getInformOne($data["common_inform_id"],"receive_id")["receive_id"];
        //入群操作
        $this->_addUserGroup(array("group_id"=>$group_id,"to_uid"=>$data["send_uid"].","),System::model("Group",false));
        //修改消息状态
        System::model("Friend",false)->updateCommonInform($data["common_inform_id"],$data["status"]);
        //推送 给发送者
        $this->sendToUid($data["send_uid"], $this->apiJson(array(
            "group_id"   =>$group_id,
            "status"        =>$data["status"]
        ),200,"ok",true,'','',true));
        $this->sendToClient($this->apiJson('',200,"ok",false));
    }
    /**
     * 删除成员
     * @param   $uid    管理员id
     * @param   $group_id   群id
     * @param   $uid_str    群成员串   如：1,2,
     *   */
    public function delGroupUser(){
        $data       =$this->data["data"];
        //验证 是否是管理员
        $model      =System::model("Group",false);
        $group_uid  =$model->getGroupUid($data["group_id"]);//获取群主id
        if ($group_uid!=$data["uid"]){
            //不是管理员 无权限
            $this->sendToClient($this->apiJson('', 502, "不是管理员 无权限",false));return;
        }
        //删除操作
        $model->delGroupUser($data["group_id"],mb_substr($data["uid_str"],0,strlen($data["uid_str"])-1));
        //修改缓存串
        $uid_str=join(",",array_column ($model->getGroupUserAll($data["group_id"],"uid"),"uid"));
        $this->setUidJoin($data["group_id"],$uid_str);
        //TODO 推送?
        $this->sendToClient($this->apiJson('',200,"ok",false));
    }
    /**
     * 修改群相关信息       【 群认证    群公告 显示群昵称开关  群名称】
     * @param   $uid        管理员id
     * @param   $group_id   群id
     * @param   $field      名称  如:name 名称
     *   */
    public function updateGroupInfo(){
        $data       =$this->data["data"];
        //验证 是否是管理员
        $model      =System::model("Group",false);
        $group_uid  =$model->getGroupUid($data["group_id"]);//获取群主id
        if ($group_uid!=$data["uid"]){
            //不是管理员 无权限
            $this->sendToClient($this->apiJson('', 502, "不是管理员 无权限",false));return;
        }
        $model->updateInfo($data["group_id"],$data["field"]);
        if (array_keys($data["field"])[0]=="is_auth"){
            if ($data["field"]["is_auth"]==0){//关闭验证    直接进群
                //操作批量入群
                /* foreach ($model->batchGroup($data["group_id"]) as $v){
                    //推送 给发送者
                    $this->sendToUid($v["send_id"], $this->apiJson(array(
                        "group_id"   =>$data["group_id"],
                        "status"        =>1
                    ),200,"ok",true,'','',true));
                }  */ 
            }
            $result=System::model("Publics")->pushInfrom($data,7);
            $this->sendToFlockGroupClient(
                $data["group_id"],
                $this->apiJson(array("bodies"=>$result), 200, "ok",true,'SystemPush','sendToUserGroup',true),false
            );
            //uid to_id content type
            //推送 群验证 状态
        }else if(array_keys($data["field"])[0]=="affiche"){//修改公告时间
            $model->updateAfficheUpdateDate($data["group_id"]);
        }
        $this->sendToClient($this->apiJson('',200,"ok",false));
    }
    /**
     * 转让群主
     * @param   $uid            管理员id
     * @param   $group_id       群id
     * @param   $receive_uid    接受人uid
     *   */
    public function transferGroup(){
        //1 验证
        $data       =$this->data["data"];
        //验证 是否是管理员
        $model      =System::model("Group",false);
        $group_uid  =$model->getGroupUid($data["group_id"]);//获取群主id
        if ($group_uid!=$data["uid"]){//不是管理员 无权限
            $this->sendToClient($this->apiJson('', 502, "不是管理员 无权限",false));return;
        }
        //2 转让操作   修改缓存
        $this->setUidJoin($data["group_id"], $model->transferGroup($data));
        $this->sendToClient($this->apiJson('',200,"ok",false));
    }
    /**
     * 保存群聊
     * @param   $uid            uid
     * @param   $group_id       群id
     * @param   $is_group_chat  是否保存群聊 1是 0否
     *   */
    public function saveChat(){
        System::model("Group",false)->saveChat($this->data["data"]);
        $this->sendToClient($this->apiJson('',200,"ok",false));
    }
    /**
     * 退出群
     * @param   $uid            uid
     * @param   $group_id       群id
     *   */
    public function checkOutGroup(){
        $data       =$this->data["data"];
        //验证 是否是guanliyuan
        //退群
        $uid_str=join(",",array_column (System::model("Group",false)->checkOutGroup($data["group_id"],$data["uid"]),"uid"));
        //修改缓存
        $this->setUidJoin($data["group_id"], $uid_str);
        $this->sendToClient($this->apiJson('',200,"ok",false));
    }
    /**
     * 设置缓存 群成员串
     * @param unknown $group_id
     * @param unknown $uid_str  */
    private function setUidJoin($group_id,$uid_str){
        $GLOBALS["config"]["EXTEND"]["RedisMsg"]->setUidJoin($group_id,$uid_str);
    }
    /**
     * 消息置顶
     * @param   $uid            uid
     * @param   $group_id       群id
     * @param   $is_stick  是否保存群聊 1是 0否
     *   */
    public function updateGroupStick(){
        System::model("Group",false)->updateGroupStick($this->data["data"]);
        $this->sendToClient($this->apiJson('',200,"ok",false));
    }
    
    /**
     * 消息禁音
     * @param   $uid            uid
     * @param   $group_id       群id
     * @param   $is_voice   是否保存群聊 1是 0否
     *   */
    public function updateGroupVoice(){
        System::model("Group",false)->updateGroupVoice($this->data["data"]);
        $this->sendToClient($this->apiJson('',200,"ok",false));
    }
    /**
     * 显示成员昵称
     * @param   $uid            uid
     * @param   $group_id       群id
     * @param   $is_show_nickname   1是 0否
     *   */
    public function updateShowNickname() {
        System::model("Group",false)->updateShowNickname($this->data["data"]);
        $this->sendToClient($this->apiJson('',200,"ok",false));
    }
    /**
     * 设置群成员备注
     * @param   $uid            uid
     * @param   $to_uid         成员uid
     * @param   $group_id       群id
     * @param   $remark         备注
     *   */
    public function updateUserRemark(){
        System::model("Group",false)->updateUserRemark($this->data["data"]);
        $this->sendToClient($this->apiJson('',200,"ok",false));
    }
    /**
     * 消息禁音
     * @param   $uid            uid
     * @param   $group_id       群id
     * @param   $remark   是否保存群聊 1是 0否
     *   */
    public function updateMyRemark(){
        System::model("Group",false)->updateMyRemark($this->data["data"]);
        $this->sendToClient($this->apiJson('',200,"ok",false));
    }
    
}
