<?php
// +----------------------------------------------------------------------
// | 后台操作的推送
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace Controller;
use Lib\Common;
use Extend\System;
use \GatewayWorker\Lib\Gateway;
class SystemPush extends Common{
    //推送给全部人  系统通知
    public function sendToUserAll(){
        //权限验证
        $this->sendToUserGroup($this->apiJson($this->data['data'], 200, "ok"));
        //返回包
        $this->sendToClient($this->apiJson('', 200, "ok",false));
    }
    //推送给群成员
    //public function sendToUserGroup(){ }
    public function SystemsendToUser(){ 
        $uid    =System::model("SystemPush")->getuid($this->data["data"]["msg_id"]);
        $this->sendToUid($uid, $this->apiJson($this->data["data"], 200, "ok",true,"SystemPush","SystemsendToUser",true));
    }
    /**
     * 获取 提送消息列表
     * @param   system_uid  客服uid
     * @param   uid         自己的uid
     * @param   start_msg_id    开始消息id
     * @param   end_msg_id      结束消息id
     * @param   limit       要显示的调试
     *   */
    public function getList(){
        $list   =array();
        foreach (System::model("SystemPush")->getList($this->data['data']) as $v){
            if ($v["msg_type"]==1){//系统通知模板
                $v["content"]=@unserialize($v["content"]);
            }else if($v["msg_type"]==2){//聊天通知
                //此处要分离 文字 图片 名片等
            }
            $list[]=$v;
        }
        $this->sendToClient($this->apiJson(array("list"=>$list), 200, "ok",false));
    }
    
}
