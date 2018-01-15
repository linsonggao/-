<?php
// +----------------------------------------------------------------------
// | 系统推送
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace Model;
class SystemPush{
    /**
     * 保存 系统推送通知
     * @param unknown $data  */
    public function addSystemMsg($data){
        $msg_id = $GLOBALS["db"]->insert('chat_msg')->cols(array(
            "type"          =>$data["type"],
            "uid"           =>$data["system_uid"],
            "content"       =>$data["content"],
            "create_date"  =>time()))->query();
        //
        $msg_system_id=$GLOBALS["db"]->insert('chat_msg_system')->cols(array(
            "msg_id"       =>$msg_id,
            "system_uid"   =>$data["system_uid"],
            "msg_type"     =>$data["msg_type"],
            "create_date"  =>time() ))->query();
        return array("msg_id"=>$msg_id,"msg_system_id"=>$msg_system_id);
    }
    /**
     * 获取客服消息列表
     *   */
    public function getList($info){ 
        //SELECT * FROM `chat_msg_system` where system_uid=1 AND ((type=2 AND uid=18) OR (type=1 AND uid=0)) ORDER BY `msg_system_id` DESC
        $where   ="a.system_uid=1 AND ((a.type=2 AND a.uid={$info["uid"]}) OR (a.type=1 AND a.uid=0))";
        $db=$GLOBALS["db"]
            ->select('a.send_uid,a.receive_uid,a.msg_type,b.content,b.type,b.msg_status,b.msg_id,b.create_date')
            ->from('chat_msg_system AS a')
            ->leftJoin("chat_msg AS b","a.msg_id=b.msg_id")
        ;
        //没有记录
        if ($info["start_msg_id"]==0 && $info["end_msg_id"]==0) {//没有聊天记录
            $db=$db->where($where);
        }else if($info["start_msg_id"]!=0 && $info["end_msg_id"]==0){//没有结尾数据
            $db=$db->where("{$where} AND a.msg_group_id<".$info["start_msg_id"]);
        }else if($info["start_msg_id"]==0 && $info["end_msg_id"]!=0){//没有开始数据
            $db=$db->where("{$where} AND msg_id>".$info["end_msg_id"]);
        }else{//区间数据
            $db=$db->where("{$where} AND (a.msg_id between ".$info["start_msg_id"]." AND ".$info["end_msg_id"].")");
        }
        return $db->limit($info["limit"])->orderByDESC(array("a.msg_id"))->query();
    }
    public function getUid($msg_id){
        return $GLOBALS["db"]->select("uid")->from("chat_msg")->where("msg_id={$msg_id}")->row()["uid"];
    }
}
