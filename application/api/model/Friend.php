<?php
// +----------------------------------------------------------------------
// | 好友模型
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace app\api\model;
class Friend extends CommonModel{
    /**
     * 搜索 
     * @param unknown $uid
     * @param unknown $phone  */
    public function searchPhone($uid,$phone){
        $where  =array(
            "is_phone_search"  =>1,
            "phone"            =>$phone,
        );
        $res    =db("user")->field("uid AS to_uid,head_img,head_img,nickname,phone,sex")->where($where)->find();
        if (!empty($res)){
            $res["relation"]=-1;
            $relation       =db("user_relation")->field("relation")->where(array("uid"=>$uid,"to_uid"=>$res["to_uid"]))->value("relation");
            if (!empty($relation)){
                $res["relation"]=$relation;
            }else{
                $where  =array(
                    "inform_type"   =>1,
                    "send_id"       =>$uid,
                    "receive_id"    =>$res["to_uid"]
                );
                $inform =db("common_inform")->field("common_inform_id")->where($where)->find();
                if (!empty($inform)){
                    $relation   =3;
                }
            }
        }
        return $res;
    }
}
