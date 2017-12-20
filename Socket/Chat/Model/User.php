<?php
// +----------------------------------------------------------------------
// | 会员操作模型
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace Model;
class User{
    /**
     * 获取该用户 群列表
     * @param unknown $id  */
    public function getGroupAll($uid){
        return $GLOBALS["db"]->select('group_id')->from('chat_group_user')->where("uid=".$uid)->query();
    }
    /**
     * 设置系统通知未读条数
     * @param unknown $id  */
    public function setSystemMsg($uid){
        $admin_list=$GLOBALS["db"]->select("system_uid")->from("chat_system_user")->where("status=1 AND is_show=1")->query();
        foreach ($admin_list as $v){//取全部管理员
            $where="system_uid in({$v["system_uid"]}) AND uid in({$uid},0)";
            //取当前会员 已读消息
            $msg_count=$GLOBALS["db"]->select("count(*) AS msg_count")->from("chat_msg_system")->where($where)->row()["msg_count"];
            $user_read_count=$GLOBALS["db"]->select("count(*) AS user_read_count")->from("chat_user_read_system")->where("uid=".$uid)->row()["user_read_count"];
            $total_count=$msg_count-$user_read_count;
            if ($total_count>=0){//加入
                //取最后一条信息
                $chat_msg=$GLOBALS["db"]->select("chat_msg.content,chat_msg.create_date")
                    ->from("chat_msg_system")
                    ->leftJoin("chat_msg","chat_msg_system.msg_id=chat_msg.msg_id")
                    ->where("chat_msg_system.system_uid in({$v["system_uid"]}) AND chat_msg_system.uid in({$uid},0)")
                    ->orderByDESC(array("chat_msg_system.msg_id"))
                    ->row()["content"];
                $data=array(
                    "uid"           =>$uid,
                    "msg_type"      =>2,
                    "id"            =>$v["system_uid"],
                    "number"        =>$total_count,
                    "content"       =>$chat_msg["content"],
                    "add_time"      =>$chat_msg["create_date"]
                );
                $GLOBALS["config"]["EXTEND"]["RedisMsg"]->informIncr($data);
            } 
        }   
    }
}
