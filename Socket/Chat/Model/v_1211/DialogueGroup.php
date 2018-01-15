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
class DialogueGroup{
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
        $msg_group_id=$GLOBALS["db"]->insert('chat_msg_group')->cols(array(
            "msg_id"        =>$msg_id,
            "uid"           =>$data["uid"],
            "group_id"       =>$data["to_id"],
            "create_date"  =>time() ))->query();
        return array("msg_id"=>$msg_id,"msg_group_id"=>$msg_group_id);
    }
    /**
     * 获取聊天列表
     * @param unknown $data  */
    public function getDialogueList($data){
        $group_id=$data["group_id"];
        $db=$GLOBALS["db"]
                    ->select('a.uid,b.content,b.type,b.msg_status,b.msg_id')
                    ->from('chat_msg_group AS a')
                    ->leftJoin("chat_msg AS b","a.msg_id=b.msg_id")
        ;
        //没有记录
        if ($data["start_msg_id"]==0 && $data["end_msg_id"]==0) {//没有聊天记录
            $db=$db->where("a.group_id={$group_id}");         
        }else if($data["start_msg_id"]!=0 && $data["end_msg_id"]==0){//没有结尾数据
            $db=$db->where("a.group_id={$group_id} AND a.msg_id<".$data["start_msg_id"]);
        }else if($data["start_msg_id"]==0 && $data["end_msg_id"]!=0){//没有开始数据
            $db=$db->where("a.group_id={$group_id} AND a.msg_id>".$data["end_msg_id"]);
        }else{//区间数据
            $db=$db->where("a.group_id={$group_id} AND (a.msg_id between ".$data["start_msg_id"]." AND ".$data["end_msg_id"].")");
        }
        return $db->limit($data["limit"])->orderByDESC(array("a.msg_id"))->query();
    }
    /**
     * 验证
     * @param unknown $group_id
     * @param unknown $uid  */
    public function regUserGroup($group_id,$uid){
        $where  ="uid={$uid} ANd gruop_id={$group_id}";
        $reg    =$GLOBALS["db"]->select("group_user_id")->from("chat_group_user")->where($where)->row();
        //
        if (empty($reg)){
            return true;
        }
        return false;
    }
}
