<?php
// +----------------------------------------------------------------------
// | 公共操作模型
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace Model;
class Publics{
    //加入投诉信息
    public function complain($info){
        return $GLOBALS["db"]->insert('chat_common_complain')->cols($info)->query();
    }
    //消息
    public function addSystemMsg($data){
        if ($data["complain_type"]==1){//用户 
            $data["name"]=$GLOBALS["db"]->select("nickname")->from("chat_user")->where("uid={$data["to_id"]}")->row()["nickname"];
        }else if($data["complain_type"]==2){//群
            $data["name"]=$GLOBALS["db"]->select("name")->from("chat_group")->where("group_id={$data["to_id"]}")->row()["name"];
        }
        return $this->pushInfrom($data, 2); 
    }
    /**
     * 下发消息入库
     * @param unknown $data
     * @param unknown $template_id  */
    public function pushInfrom($data,$template_id){
        //取昵称
        $content_data=$GLOBALS["config"]["EXTEND"]["MsgTemp"]->getTemp($template_id,$data);
        //入 消息
        $msg_id=$GLOBALS["db"]->insert('chat_msg')->cols(array(
            "type"         =>"temp",//模板
            "template_id"  =>$template_id,//模板id 
            "dialogue_type"=>4,
            "uid"          =>1,
            "content"      =>$content_data["serialize_data"],
            "create_date"  =>time()
        ))->query();
        //入 客户消息
        $GLOBALS["db"]->insert('chat_msg_system')->cols(array(//109
            "system_uid"   =>1,
            "msg_id"       =>$msg_id,
            "type"         =>2,
            "uid"          =>$data["uid"],
            "send_uid"     =>1,
            "receive_uid"  =>$data["uid"],
            "create_date"  =>time()
        ))->query();
        $content_data["data"]["msg_id"]  =$msg_id;
        return $content_data["data"];
    }
}
