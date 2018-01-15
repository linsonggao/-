<?php 
// +----------------------------------------------------------------------
// | 公共继承
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace Lib;
use \GatewayWorker\Lib\Gateway;
use Extend\Log;
class Common{
    protected $_pack;//解包
    protected $_config;//配置
    protected $client_id;//唯一连接id
    protected $data;//数据
    //群组前缀
    protected static $_flock_group_prefix="flock_group:";
    public function __construct($obj,$client_id,$data){
        $this->_pack    =$obj;
        $this->_config  =$obj->_config;
        $this->client_id=$client_id;
        $this->data     =$data;
    }
    /**
     * 返回 数据格式
     * @param unknown $data
     * @param unknown $code
     * @param unknown $msg
     * @param string $controller
     * @param string $method  */
    public function apiJson($data,$code,$msg,$is_cm=true,$controller='',$method='',$un_codeAndMsg=false){
        $data_arr=array(
            "data"      =>$data,
            "code"      =>$code,
            "msg"       =>$msg,
        );
        if ($un_codeAndMsg==true){
            unset($data_arr["code"]);
            unset($data_arr["msg"]);
        }
        if ($is_cm==true){
            if ($controller!=''){//自定义返回
                /* $data_arr["controller"]=$controller;
                $data_arr["method"]=$method; */
                $data_arr["type"]=$controller.$method;
            }else{//系统原样返回
                /* $data_arr["controller"]=$this->data["controller"];
                $data_arr["method"]=$this->data["method"]; */
                $data_arr["type"]=$this->data["controller"].$this->data["method"];
            }
        }
        $pack   =$this->_pack->enPack(json_encode($data_arr,true));
        Log::write($pack,"返回包");
        return $pack;
    }
    /**
     * 给当前用户发送消息
     * @param unknown $result  */
    public function sendToClient($result,$is_out=false){
        Gateway::sendToClient($this->client_id,$result);
        if ($is_out==true){//退出
            Gateway::closeClient($this->client_id);
        }
    }
    /**
     * 给群发送消息
     * @param unknown $result  */
    public function sendToFlockGroupClient($group_id,$result,$is_pron=true){
        if ($is_pron==false){//发送给全组人 包括自己
            Gateway::sendToGroup(self::$_flock_group_prefix.$group_id, $result);
            return;
        }
        //发送给 除自己外的群成员
        foreach (Gateway::getClientSessionsByGroup(self::$_flock_group_prefix.$group_id) AS $k=>$v){
            if ($k!=$this->client_id){
                Gateway::sendToClient($k,$result);
            }  
        }
    }
    /**
     * 加入群组
     * @param unknown $group_id    群id
     *   */
    public function joinFlockGroup($group_id,$uid){
        foreach (Gateway::getClientIdByUid($uid) as $v){
            Gateway::joinGroup($v, self::$_flock_group_prefix.$group_id);
        }
    }
    /**
     * 从某个群组中退出
     * @param unknown $group_id     群组id
     * @param unknown $group_id     用户uid
     *   */
    public function leaveFlockGroup($group_id,$uid){
        foreach (Gateway::getClientIdByUid($uid) as $v){
            Gateway::leaveGroup($v,self::$_flock_group_prefix.$group_id);//踢出分组
        } 
    }
    /**
     * 推送给全部用户
     * @param unknown $result  */
    public function sendToAll($result,$is_pron=true){
        if ($is_pron==false){//发送给全部在线的人
            Gateway::sendToAll($result);return;
        }
        //发送给所有人 除了自己
        foreach (Gateway::getAllClientSessions() AS $k=>$v){
            if ($k!=$this->client_id){
                Gateway::sendToClient($k,$result);
            }
        }
    }
    /**
     * 发送给全部用户
     * @param unknown $result  */
    public function sendToUserGroup($result){
        Gateway::sendToGroup($GLOBALS["config"]["CHAT_GROUP"]["room_user"]["name"], $result);
    }
    /**
     * 给某个会员推送消息
     * @param unknown $uid
     * @param unknown $result  */
    public function sendToUid($uid,$result){
        Gateway::sendToUid($uid,$result);
    }
}

?>