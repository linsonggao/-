<?php
// +----------------------------------------------------------------------
// | 消息撤回
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace Controller\v_1211;
use Lib\Common;
use Extend\System;
class Withdraw extends Common{
    //获取消息撤回列表
    public function getList(){
        $info   =$this->data["data"];
        $list   =System::model("Publics",false)->getWithdrawList($info);
        if (!empty($list)){
            $list   =join(",",array_column($list,"msg_id"));
        }
        $this->sendToClient($this->apiJson(array("msg_id"=>$list),200,"ok",false));
    }
    //消息撤回
    public function recall(){
        $info   =$this->data["data"];
        $res    =System::model("Withdraw",false)->recall($info["uid"],$info["msg_id"]);
        if ($res["type"]==1){//单人发送
            $this->sendToUid($res["to_id"], $this->apiJson($res,200,"ok",true,'','',true));
        }else if($res["type"]==2){//群组发送
            $this->sendToFlockGroupClient($res["to_id"], $this->apiJson($res,200,"ok",true,'','',true));
        }
        $this->sendToClient($this->apiJson('',200,"ok",false));
    }
}
