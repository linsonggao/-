<?php
// +----------------------------------------------------------------------
// | 推送
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 
// | Data  2018年1月11日 上午9:29:30 
// | Version  1.0.0
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com> 
// +----------------------------------------------------------------------
namespace app\extend\controller;
use think\Controller;
class Push extends Controller{
    /**
     * controller("extend/Push")->announcement("标题","内容");
     * 系统公告推送       
     * @param unknown $title
     * @param unknown $content  */
    public function announcement($system_uid,$title,$content){
        $info   =array(
            "system_uid"    =>$system_uid,
            "title"         =>$title,
            "content"       =>$content,
            "create_date"   =>time() 
        );
        //加入公告表
        \think\Db::name("system_announcement")->insert($info);
        $temp_info  =$GLOBALS["config"]["EXTEND"]["MsgTemp"]->getTemp(1,$info);
        //加入 内容表
        \think\Db::name("msg")->insert(array(
            "type"          =>"temp",
            "template_id"   =>1,
            "dialogue_type" =>4,
            "uid"           =>$system_uid,
            "content"       =>$temp_info["serialize_data"],
            "create_date"   =>time()
        ));
        $msg_id =\think\Db::name("msg")->getLastInsID();
        \think\Db::name("msg_system")->insert(array(
            "system_uid"    =>$system_uid,
            "msg_id"        =>$msg_id,
            "send_uid"      =>$system_uid,     
            "receive_uid"   =>0,
            "create_date"   =>time()
        ));
        $temp_info["data"]["msg_id"]=$msg_id;
        $GLOBALS["config"]["EXTEND"]["SocketClinet"]->pushServe($temp_info["data"],1);
        return ;
    }
    /**
     * 处理投诉
     * @param unknown $system_uid   处理人id
     * @param unknown $common_complain_id   主键id
     * @param unknown $feedback_content     反馈内容
     * @param unknown $status           状态  	1 提交投诉 2投诉成功 3投诉失败
     *   */
    public function disposeComplain($system_uid,$common_complain_id,$feedback_content,$status){
        $res    =\think\Db::name("common_complain")->where(array("common_complain_id"=>$common_complain_id))->find();
        //修改状态
        \think\Db::name("common_complain")->where(array("common_complain_id"=>$common_complain_id))->update(array("status"=>$status));
        if ($res["complain_type"]==1){
            $res["object"]  =\think\Db::name("user")->where(array("uid"=>$res["to_id"]))->value("nickname");
        }else if($res["complain_type"]==2){
            $res["object"]  =\think\Db::name("group")->where(array("group_id"=>$res["to_id"]))->value("name");
        }
        //取模板
        $res["title"]=$status==2?"投诉成功":"投诉不通过";
        $temp_info  =$GLOBALS["config"]["EXTEND"]["MsgTemp"]->getTemp(3,$res);
        //加入内容
        \think\Db::name("msg")->insert(array(
            "type"          =>"temp",
            "template_id"   =>3,
            "dialogue_type" =>4,
            "uid"           =>$system_uid,
            "content"       =>$temp_info["serialize_data"],
            "create_date"   =>time()
        ));
        $msg_id =\think\Db::name("msg")->getLastInsID();
        \think\Db::name("msg_system")->insert(array(
            "system_uid"    =>$system_uid,
            "msg_id"        =>$msg_id,
            "send_uid"      =>$system_uid,
            "receive_uid"   =>0,
            "type"          =>2,
            "create_date"   =>time()
        ));
        $temp_info["data"]["msg_id"]=$msg_id;
        $GLOBALS["config"]["EXTEND"]["SocketClinet"]->pushServe($temp_info["data"],2);
        return ;
    }
}