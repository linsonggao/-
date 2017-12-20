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
            "to_uid"       =>$data["to_uid"],
            "create_date"  =>time() ))->query();
        return array("msg_id"=>$msg_id,"msg_1v1_id"=>$chat_msg_1v1);
    }
    /**
     * 获取聊天列表
     * @param unknown $data  */
    public function getDialogueList($data){
        $uid=$data["uid"].",".$data["to_uid"];
        $db=$GLOBALS["db"]
                    ->select('a.msg_1v1_id,a.uid,a.to_uid,b.content,b.type')
                    ->from('chat_msg_1v1 AS a')
                    ->leftJoin("chat_msg AS b","a.msg_id=b.msg_id")
        ;
        //没有记录
        if ($data["start_msg_1v1_id"]==0 && $data["end_msg_1v1_id"]==0) {//没有聊天记录
            $db=$db->where("a.uid in({$uid}) AND a.to_uid in({$uid})");         
        }else if($data["start_msg_1v1_id"]!=0 && $data["end_msg_1v1_id"]==0){//没有结尾数据
            $db=$db->where("(a.uid in({$uid}) AND a.to_uid in({$uid})) AND a.msg_1v1_id<".$data["start_msg_1v1_id"]);
        }else if($data["start_msg_1v1_id"]==0 && $data["end_msg_1v1_id"]!=0){//没有开始数据
            $db=$db->where("(a.uid in({$uid}) AND a.to_uid in({$uid})) AND a.msg_1v1_id>".$data["end_msg_1v1_id"]);
        }else{//区间数据
            $db=$db->where("(a.uid in({$uid}) AND a.to_uid in({$uid})) AND (a.msg_1v1_id>".$data["end_msg_1v1_id"]." AND a.msg_1v1_id<".$data["start_msg_1v1_id"].")");
        }
        return $db->limit($data["limit"])->orderByDESC(array("a.msg_1v1_id"))->query();
    }
}
