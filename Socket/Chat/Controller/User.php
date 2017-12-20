<?php
// +----------------------------------------------------------------------
// | 
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
class User extends Common{
    private $_uid;
    //登录
    public function login(){
        $this->_uid=$this->data["data"]["uid"];
        if(Gateway::isUidOnline($this->_uid)){//1.是踢下线
            Gateway::sendToUid($this->_uid,$this->apiJson('', 414, "ok",true,"User","checkOut"));
            foreach (Gateway::getClientIdByUid($this->_uid) AS $v)
                Gateway::closeClient($v);
        }
        //  初始化 用户 
        $this->initUser();
        //获取系统通知
        $this->getSystemMsg($this->_uid);
        //加入用户房间
        Gateway::joinGroup($this->client_id,$GLOBALS["config"]["CHAT_GROUP"]["room_user"]["name"]);//会员房间
        //2.遍历群组 加入分组  
        $this->joinUserGroup();
        //3.获取 推送消息 未读的消息
        $data=$this->pushMessageCount();//需要解析 头像 和标题
        //
        $this->sendToClient($this->apiJson($data, 200, "ok",false));
    }
    //初始化 用户
    private function initUser(){
        Gateway::bindUid($this->client_id,$this->_uid);
    }
    /**
     * 获取系统通知
     *   */
    private function getSystemMsg($uid){
        //取该会员的总条数-当前的已读的总条数
        System::model("User")->setSystemMsg($uid);
    }
    /**
     * 推送消息数
     *   */
    private function pushMessageCount(){  
        $data=$GLOBALS["config"]["EXTEND"]["RedisMsg"]->getInformList($this->_uid);//
        if (!empty($data)){
            return $data;
        }
        return '';
    }
    /**
     * 加入群组
     *   */
    private function joinUserGroup(){
        //1.遍历所有群
        $group_list=System::model("User")->getGroupAll($this->_uid);
        foreach ($group_list as $v){
            $this->joinFlockGroup($v["group_id"],$this->_uid);//加入群组
        }
    }
    /**
     * 退出
     *   */
    public function checkOut(){
        $this->sendToClient($this->apiJson('', 200, "ok",false),true);
    }
}
