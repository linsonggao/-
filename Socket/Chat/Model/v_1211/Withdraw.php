<?php
// +----------------------------------------------------------------------
// | 公共操作模型
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace Model\v_1211;
class Withdraw{
    //获取撤回的列表
    public function getWithdrawList($info){
        if ($info["type"]==1){//单人
            $in     =$info["uid"].",".$info["to_id"];
            $where  ="(chat_msg_1v1.uid in($in) AND chat_msg_1v1.to_uid in($in)) AND (chat_msg_1v1.msg_id between {$info["start_msg_id"]} AND {$info["end_msg_id"]}) AND chat_msg.msg_status=-1";
            $list   =$GLOBALS["db"]->select("chat_msg_1v1.msg_id")
                ->from("chat_msg_1v1")
                ->leftJoin("chat_msg","chat_msg_1v1.msg_id=chat_msg.msg_id")
                ->where($where)
                ->query();
        }else if($info["type"]==2){//群
            $where  ="(uid={$info["uid"]} AND group_id={$info["to_id"]}) AND (chat_msg_group.msg_id between {$info["start_msg_id"]} AND {$info["end_msg_id"]}) AND chat_msg.msg_status=-1";
            $list   =$GLOBALS["db"]->select("chat_msg_group.msg_id")
                ->from("chat_msg_group")
                ->leftJoin("chat_msg","chat_msg_group.msg_id=chat_msg.msg_id")
                ->wehre($where)
                ->query();
        }
        return $list;
    }
    //消息撤回
    public function recall($uid,$msg_id){
        $where  ="uid={$uid} AND msg_id={$msg_id}";
        $res    =$GLOBALS["db"]->select("dialogue_type")->from("chat_msg")->row();
        if($res["dialogue_type"]==1){//单人
            $info   =$GLOBALS["db"]->select("to_uid AS to_id")->from("chat_msg_1v1")->where("msg_id={$msg_id} AND uid={$uid}")->row();
            $type   =1;
        }else if($res["dialogue_type"]==2){//群组
            $type   =2;
            $info   =$GLOBALS["db"]->select("group_id AS to_id")->from("chat_msg_group")->where("msg_id={$msg_id} AND uid={$uid}")->row();
        }else if($res["dialogue_type"]==3){//客服
            $type   =3;
            $info["to_id"]=0;
        }
        $GLOBALS["db"]->update('chat_msg')->cols(array("msg_status"=>-1))->where($where)->query();
        return array("type"=>$type,"to_id"=>$info["to_id"],"msg_id"=>$msg_id);
    }
}
