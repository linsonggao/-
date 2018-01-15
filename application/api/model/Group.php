<?php
// +----------------------------------------------------------------------
// | 群组模型
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace app\api\model;
class Group extends CommonModel{
    /**
     * 创建群聊
     * @param unknown $uid
     * @param unknown $uid_join  */
    public function createGroup($uid,$uid_join){
        //生成 群聊名称
        $my_group_count =db("group_user")->where(array("uid"=>$uid,"type"=>1))->count()+1;
        $name           ="群聊({$my_group_count})";
        //创建群
        $group_id       =db("group")->insertGetId(array(
            "name"          =>$name,
            "type"          =>1,
            "uid_str"       =>$uid.",".$uid_join,
            "create_date"   =>time()
        ));
        //生成群成员
        foreach (explode(",",$uid.",".$uid_join) AS $v){
            $type=0;
            ($v==$uid) && $type=1;
            $nickname   =db("user")->field("nickname")->where(array("uid"=>$v))->value("nickname");
            db("group_user")->insert(array(
                "uid"          =>$v,
                "type"         =>$type,
                "remark"       =>$nickname, 
                "group_id"     =>$group_id,
                "create_date"  =>time()
            ));
        }
        return array("group_id"=>$group_id,"name"=>$name);
    }
    /**
     * 获取 保存后的列表
     * @param unknown $uid  */
    public function getList($uid){
        return db("group_user")->alias("a")
                ->field("b.group_id,b.name,b.logo")
                ->join("chat_group b","a.group_id=b.group_id","left")
                ->where(array("a.uid"=>$uid,"a.is_group_chat"=>1))
                ->select()
        ;
    }
    /**
     * 获取 群详情和成员列表
     * @param unknown $group_id
     * @param unknown $uid  */
    public function details($group_id,$uid){
        $group  =db("group_user")->alias("a")
                ->field("a.group_id,a.type,a.status,a.is_stick,a.is_voice,a.is_show_nickname,a.is_group_chat,b.affiche_update_date,b.name,b.logo,b.affiche,b.uid_str,b.is_auth")//
                ->join("chat_group b","a.group_id=b.group_id","left")
                ->where(array("a.uid"=>$uid,"a.group_id"=>$group_id))
                ->find();
        $group_user_list    =db("group_user")->alias("a")
                ->field("a.uid,a.remark,b.head_img")
                ->join("chat_user b","a.uid=b.uid","left")
                ->where(array("a.group_id"=>$group_id))
                ->order("type DESC")
                ->select()
        ;
        $user_list=array();
        foreach ($group_user_list as $v){
            $where  =array(
                "group_id"      =>$group_id,
                "uid"           =>$uid,
                "to_uid"        =>$v["uid"]
            );
            $one    =db("group_user_remark")->field("remark")->where($where)->find();
            if (!empty($one)){//
                $v["remark"]    =$one["remark"];
            }
            $user_list[]=$v;
        }
        return array("group"=>$group,"group_user_list"=>$user_list);
    }
}
