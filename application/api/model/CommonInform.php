<?php
// +----------------------------------------------------------------------
// | 消息通知
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace app\api\model;
class CommonInform extends CommonModel{
    /**
     * 验证通知是否存在
     * @param unknown $info
     * @param number $inform_type
     * @return boolean  */
    public function regInform($info,$inform_type=1){
        $count=db("common_inform")
            ->where(array(
                "send_id"       =>$info["send_uid"],
                "receive_id"    =>$info["receive_uid"],
                "inform_type"   =>$inform_type,
                "status"        =>0
            ))
            ->count();
        return (bool)$count;
    }
    /**
     * 添加 信息
     * @param unknown $info  */
    public function addInform($info,$inform_type=1){
        return db("common_inform")->insert(array(
            "send_id"       =>$info["send_uid"],
            "receive_id"    =>$info["receive_uid"],
            "type"          =>$info["type"],
            "inform_type"   =>$inform_type,
            "create_date"   =>time()
        ));
    }
    /**
     * 获取 消息列表
     * @param unknown $uid  */
    public function getList($uid,$inform_type=1){
        if($inform_type==1){//好友消息
            $list=db("common_inform")
                ->alias("a")
                ->join("chat_user b","a.send_id=b.uid","left")
                ->field("a.common_inform_id,a.status,a.create_date,b.nickname,b.head_img,b.uid AS send_uid")
                ->where(array("a.receive_id"=>$uid))
                ->limit(100)
                ->select();
        }
        return $list;
    }
}
