<?php
// +----------------------------------------------------------------------
// | 好友操作
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller;
class Friend extends Common{
    //TODO 待写计划任务 自动处理消息
    //获取好友列表
    public function getList(){
        $this->regInfoNull($this->info, array("uid"));
        return $this->apiJson(model("UserRelation")->getUserList($this->info["uid"]),200,"ok",false,true);
    }
    
    //手机通讯录匹配
    public function matchingPhonebook(){
        $this->regInfoNull($this->info, array("phone"));
        $phone  =mb_substr($this->info["phone"],0,strlen($this->info["phone"])-1); 
        //匹配所有号码
        $user_all=\think\Db::name("user")->field("uid,head_img,max_head_img,nickname,phone,sex")->where(array("phone"=>array("in",$phone)))->select();
        //对比 好友列表
        $list=array();
        foreach ($user_all as $v){
            $where=array(
                "uid"   =>$this->info["uid"],
                "to_uid"=>$v["uid"]
            );
            $relation   =-1;
            $res   =\think\Db::name("user_relation")->field("to_uid")->where($where)->find();
            if (!empty($res)){
                $relation   =1;
            }else{//查询 消息表
                $where  =array(
                    "inform_type"   =>1,
                    "send_id"       =>$this->info["uid"],
                    "receive_id"    =>$v["uid"]
                );
                $inform =\think\Db::name("common_inform")->field("common_inform_id")->where($where)->find();
                if (!empty($inform)){
                    $relation   =3;
                }
            }
            $v["relation"]  =$relation;
            $list[]=$v;
        }
        //添加日志
        $this->addPhoneLog($this->info["uid"], $phone);
        return $this->apiJson($list,200,"ok");
    }
    //添加好友
    public function addFriend(){
        $this->regInfoNull($this->info, array("send_uid","receive_uid"));
        //验证 是否发送过好友请求
        if (!empty(model("CommonInform")->regInform($this->info,1))){
            return $this->apiJson('',306,"已经发送了邀请,不能重复发送");
        }
        //验证是否在自己的好友列表
        if (!empty(model("UserRelation")->regUserExist($this->info["send_uid"],$this->info["receive_uid"]))){
            return $this->apiJson('',307,"该会员已存在 自己的名单");
        }
        $res    =\think\Db::name("user")->field("uid AS to_uid,head_img,max_head_img,nickname,phone,sex")->where(array("uid"=>$this->info["receive_uid"]))->find();
        $res["relation"]=-1;
        return $this->apiJson($res,200,"ok");
    }
    //搜索 号码
    public function searchPhone(){
        $this->regInfoNull($this->info, array("uid","phone"));
        return $this->apiJson(model("Friend")->searchPhone($this->info["uid"],$this->info["phone"]),200,"ok");
    }
    //入日志表
    private  function addPhoneLog($uid,$phone){
        foreach (explode(",",$phone) AS $v){
            $reg    =\think\Db::name("user_phone_log")->field("uid")->where(array("uid"=>$uid,"phone"=>$v))->find();
            if (empty($reg)){
                \think\Db::name("user_phone_log")->insert(array(
                    "uid"           =>$uid,
                    "phone"         =>$v,
                    "create_date"   =>time()
                ));
            }
        }
    }
}
