<?php
// +----------------------------------------------------------------------
// | 公共模型
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace app\api\model;
class UserRelation extends CommonModel{
    //protected $readonly = ['user_relation_id','relation',"uid","to_uid"];
    /**
     * 获取好友列表
     * @param unknown $uid  */
    public function getUserList($uid){
         $list=db("user_relation")->alias("a")
            ->field("a.relation,b.phone,a.user_relation_id,a.to_uid,b.nickname,b.head_img,b.sex")
            ->join("chat_user b","a.to_uid=b.uid","left")
            ->where(["a.uid"=>$uid,"a.status"=>1])
            ->select();
         $system_list=db("system_user")->field("nickname,system_uid,head_img")->where(array("is_show"=>1))->order("sort")->select();
         return array("list"=>$list,"system_list"=>$system_list);
    }
    /**
     * 验证好友是否存在 自己的列表中
     * @param unknown $uid
     * @param unknown $to_uid  */
    public function regUserExist($uid,$to_uid){
        return (bool)db("user_relation")->where(array(
            "uid"       =>$uid,
            "to_uid"    =>$to_uid
        ))->count();
    }
    
}
