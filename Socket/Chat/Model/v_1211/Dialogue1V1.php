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
class Dialogue1V1{
    /**
     * 保存聊天记录
     * @param unknown $data 
     * @return  $msg_id
     *  */
    public function addDialogueLog($data){
        $msg_id = $GLOBALS["db"]->insert('chat_msg')->cols(array(
            "type"          =>$data["type"],
            "uid"           =>$data["uid"],
            "content"       =>$data["content"],
            "create_date"  =>time()))->query();
        //
        $chat_msg_1v1=$GLOBALS["db"]->insert('chat_msg_1v1')->cols(array(
            "msg_id"        =>$msg_id,
            "uid"           =>$data["uid"],
            "to_uid"       =>$data["to_id"],
            "create_date"  =>time() ))->query();
        return array("msg_id"=>$msg_id,"msg_1v1_id"=>$chat_msg_1v1);
    }
    /**
     * 获取聊天列表
     * @param unknown $data  */
    public function getDialogueList($data){
        $uid=$data["uid"].",".$data["to_uid"];
        $db=$GLOBALS["db"]
                    ->select('a.uid,a.to_uid,b.content,b.type,b.msg_status,b.msg_id')
                    ->from('chat_msg_1v1 AS a')
                    ->leftJoin("chat_msg AS b","a.msg_id=b.msg_id")
        ;
        //没有记录
        if ($data["start_msg_id"]==0 && $data["end_msg_id"]==0) {//没有聊天记录
            $db=$db->where("a.uid in({$uid}) AND a.to_uid in({$uid})");         
        }else if($data["start_msg_id"]!=0 && $data["end_msg_id"]==0){//没有结尾数据
            $db=$db->where("(a.uid in({$uid}) AND a.to_uid in({$uid})) AND a.msg_id<".$data["start_msg_id"]);
        }else if($data["start_msg_id"]==0 && $data["end_msg_id"]!=0){//没有开始数据
            $db=$db->where("(a.uid in({$uid}) AND a.to_uid in({$uid})) AND a.msg_id>".$data["end_msg_id"]);
        }else{//区间数据
            $db=$db->where("(a.uid in({$uid}) AND a.to_uid in({$uid})) AND (a.msg_id between ".$data["start_msg_id"]." AND ".$data["end_msg_id"].")");
        }
        return $db->limit($data["limit"])->orderByDESC(array("a.msg_id"))->query();
    }
    /**
     * 验证 是否在好友名单里面
     * @param unknown $uid
     * @param unknown $to_uid  */
    public function regUserRelation($uid,$to_uid){
        $reg    =$GLOBALS["db"]->select("relation")
                ->from("chat_user_relation")
                ->where("uid=".$uid." AND to_uid=".$to_uid)
                ->row();
        if (!empty($reg)){//存在
            $relation  =$GLOBALS["db"]->select("relation")
                ->from("chat_user_relation")
                ->where("uid=".$to_uid." AND to_uid=".$uid)
                ->row();
            if ($relation["relation"]==2){//黑名单
                return array("code"=>204,"msg"=>"对方已经把你加入到黑名单，不能发送消息。");
            }
            return array("code"=>200,"msg"=>"ok");
        }
        return array("code"=>205,"msg"=>"该用户不在好友名单里面。");
    }
}
