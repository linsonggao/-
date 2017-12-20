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
            "msg_id"        =>$msg_id,
            "system_uid"           =>$data["system_uid"],
            "msg_type"       =>$data["msg_type"],
            "create_date"  =>time() ))->query();
        return array("msg_id"=>$msg_id,"msg_system_id"=>$msg_system_id);
    }
}
