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
class ReceiveMsg{
    /**
     * 修改聊天状态
     * @return  $msg_id
     *  */
    public function updateMsgStatus($msg_id){
       // $GLOBALS["db"]->query("UPDATE `chat_msg_1v1` SET `msg_status` = 2 WHERE msg_id IN(".rtrim($msg_id,",").")");
    }
}
