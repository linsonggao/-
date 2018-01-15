<?php
// +----------------------------------------------------------------------
// | 
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace Model\v_1211;
class Friend{
    /**
     * 验证是否重复发送
     * @param unknown $info  */
    public function regInform($info,$inform_type=1){
        $where=
<<<SQL
        send_id={$info["send_uid"]} AND
        receive_id={$info["receive_uid"]} AND 
        inform_type={$inform_type} AND 
        status=0
SQL;
        $count_all=$GLOBALS["db"]->select("count(*) AS count_all")->from("chat_common_inform")->where($where)->row()["count_all"];
        return (bool)$count_all;
    }
    /**
     * 验证该会员是否在好友里面
     * @param unknown $send_uid
     * @param unknown $receive_uid  */
    public function regUserExist($uid,$to_uid){
        $where="uid=".$uid." AND to_uid=".$to_uid;
        $count_all=$GLOBALS["db"]->select("count(*) AS count_all")->from("chat_user_relation")->where($where)->row()["count_all"];
        return (bool)$count_all;
    }
    /**
     * 添加信息
     * @param unknown $info  */
    public function addInform($info,$inform_type=1){
        $GLOBALS["db"]->insert('chat_common_inform')->cols(array(
            "send_id"       =>$info["send_uid"],
            "receive_id"    =>$info["receive_uid"],
            "type"          =>$info["type"],
            "inform_type"   =>$inform_type,
            "create_date"   =>time()
        ))->query();
    }
    /**
     * 确认加好友
     * @param unknown $common_inform_id  */
    public function notarizeAddFriends($common_inform_id){
        $res                =$GLOBALS["db"]->select("send_id,receive_id")->from("chat_common_inform")->where("common_inform_id=".$common_inform_id)->row(); 
        //验证是否已经在好友列表
        $where              ="uid={$res["send_id"]} AND to_uid={$res["receive_id"]}";
        $reg                =$GLOBALS["db"]->select("relation")->from("chat_user_relation")->where($where)->row();
        if (!empty($reg)){
            return;
        }
        //发送人的昵称
        $send_nickname      =$GLOBALS["db"]->select("nickname")->from("chat_user")->where("uid=".$res["send_id"])->row()["nickname"];
        //接受人的昵称
        $receive_nickname   =$GLOBALS["db"]->select("nickname")->from("chat_user")->where("uid=".$res["receive_id"])->row()["nickname"];
        //添加好友
        $GLOBALS["db"]->insert('chat_user_relation')->cols(array(
            "relation"      =>1,
            "uid"           =>$res["send_id"],
            "to_uid"        =>$res["receive_id"],
            "remark"        =>$receive_nickname,
            "create_date"   =>time()
        ))->query();
        //对方好友列表
        $GLOBALS["db"]->insert('chat_user_relation')->cols(array(
            "relation"      =>1,
            "uid"           =>$res["receive_id"],
            "to_uid"        =>$res["send_id"],
            "remark"        =>$send_nickname,
            "create_date"   =>time()
        ))->query();
        return;
    }
    /**
     * 修改通知状态
     * @param unknown $common_inform_id
     * @param unknown $status  */
    public function updateCommonInform($common_inform_id,$status){
        $GLOBALS["db"]->update('chat_common_inform')->cols(array('status'=>$status))->where('common_inform_id='.$common_inform_id)->query();
    }
    /**
     * 删除好友 
     * @param unknown $info  */
    public function delFriends($info){
        $uid=$info["uid"].",".$info["to_uid"];
        $GLOBALS["db"]->delete('chat_user_relation')->where('uid in('.$uid.')')->query();
    }
    /**
     * 加入黑名单
     * @param   $user_relation_id   主键id
     * @param   relation    1好友  2黑名单
     *   */
    public function addBlack($info){
        $where="uid=".$info["uid"]." AND to_uid=".$info["to_uid"];
        $GLOBALS["db"]->update('chat_user_relation')->cols(array('relation'=>$info["relation"]))->where($where)->query();
    }
    /**
     * 修改备注
     * @param   $user_relation_id   主键id
     * @param   remark    1好友  2黑名单
     *   */
    public function updateRemark($info){
        $where="uid=".$info["uid"]." AND to_uid=".$info["to_uid"];
        $GLOBALS["db"]->update('chat_user_relation')->cols(array('remark'=>$info["remark"]))->where($where)->query();
    }
    /**
     * 添加推送信息
     * @param unknown $info  */
    public function addSystemMsg($info){
        $nickname   =$GLOBALS["db"]->select("nickname")->from("chat_user")->where("uid={$info["send_uid"]}")->row()["nickname"];
        $content=$GLOBALS["config"]["EXTEND"]["MsgTemp"]->getTemp(6,array_merge($info,array("nickname"=>$nickname)));//to_uid  nickname
        //入 消息
        $msg_id=$GLOBALS["db"]->insert('chat_msg')->cols(array(
            "type"         =>"text",
            "dialogue_type"=>4,
            "uid"          =>1,
            "content"      =>serialize($content),
            "create_date"  =>time()
        ))->query();
        //入 客户消息
        $GLOBALS["db"]->insert('chat_msg_system')->cols(array(
            "system_uid"   =>1,
            "msg_id"       =>$msg_id,
            "type"         =>2,
            "uid"          =>$info["send_uid"],
            "send_uid"     =>1,
            "receive_uid"  =>$info["send_uid"],
            "create_date"  =>time()
        ))->query();
        $content["msg_id"]  =$msg_id;
        return $content;
    }
}
