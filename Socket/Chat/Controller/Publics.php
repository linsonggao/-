<?php
// +----------------------------------------------------------------------
// | tcp公共处理
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace Controller;
use Lib\Common;
use Extend\Arrays;
use Extend\System;
class Publics extends Common{
    //投诉
    //uid   to_id   complain_type 投诉类型  1用户 2群   content 内容   type类型串,  picture图片串,create_date
    public function complain(){
        $info=$this->data["data"];
        $data   =array(
            "uid"               =>$info["uid"],//发送人
            "to_id"             =>$info["to_id"],//接受对象
            "complain_type"     =>$info["complain_type"],//投诉类型  1用户 2群
            "content"           =>$info["content"],//内容
            "type"              =>$info["type"],//类型串
            "picture"           =>$info["picture"],//图片串
            "create_date"       =>time()
        );
        $reg    =Arrays::regInfoNull($data, array("uid","to_id","complain_type","content","type","picture","create_date"));
        if ($reg["code"]!=200){
            $this->sendToClient($this->apiJson('', $reg["code"],$reg["msg"],false));return;
        }
        $model  =System::model("Publics");
        //入信息
        $result =$model->addSystemMsg($info);
        //数据入库
        $result["order_id"]=$model->complain($data);
        //推送投诉 信息
        $this->sendToClient($this->apiJson(array(
            "bodies"=>$result
        ), 200, "ok",true,"SystemPush","sendToUserAll",true));
        //end
        $this->sendToClient($this->apiJson('',200,"ok",false));
    }
    
}
