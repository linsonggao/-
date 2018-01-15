<?php
// +----------------------------------------------------------------------
// | 群聊操作
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace Model\v_1211;
class Group{
    /**
     * 创建群聊
     * @param unknown $uid
     * @param unknown $uid_join  */
    public function createGroup($uid,$uid_join){
        //生成 群聊名称
        $my_group_count=$GLOBALS["db"]->select("count(*) AS my_group_count")->from("chat_group_user")->where("uid=".$uid." AND type=1")->row()["my_group_count"]+1;
        $name           ="群聊({$my_group_count})";
        //创建群
        $GLOBALS["db"]->insert('chat_group')->cols(array(
            "name"          =>$name,
            "type"          =>1,
            "uid_str"       =>$uid.",".$uid_join,
            "create_date"   =>time(),
            "affiche_update_date"=>time()
        ))->query();
        $group_id   =$GLOBALS["db"]->lastInsertId();
        //生成群成员
        foreach (explode(",",$uid.",".$uid_join,-1) AS $v){
            $type=0;
            ($v==$uid) && $type=1;
            $nickname   =$GLOBALS["db"]->select('nickname')->from('chat_user')->where("uid=".$v)->row()["nickname"];
            $GLOBALS["db"]->insert('chat_group_user')->cols(array(
                "uid"          =>$v,
                "type"         =>$type,
                "remark"       =>$nickname,
                "group_id"     =>$group_id,
                "create_date"  =>time()
            ))->query();
        }
        return array("group_id"=>$group_id,"name"=>$name,"logo"=>"");
    }
    /**
     * 解散群
     * @param unknown $uid
     * @param unknown $group_id
     *   */
    public function unGroup($uid,$group_id){
        $reg    =$this->regGroupManage($uid, $group_id);
        if ($reg["code"]!=200){
            return $reg;
        }
        //删除群数据
        $GLOBALS["db"]->delete('chat_group')->where('group_id='.$group_id)->query();
        //删除成员
        $GLOBALS["db"]->delete('chat_group_user')->where('group_id='.$group_id)->query();
        return array("code"=>200,"msg"=>"ok");
    }
    /**
     * 验证该会员是否是群主
     * @param unknown $uid
     * @param unknown $group_id
     *   */
    public function regGroupManage($uid,$group_id){
        $res    =$GLOBALS["db"]->select('type')->from('chat_group_user')->where("uid=".$uid." AND type=1 AND group_id=".$group_id)->row();
        if (empty($res)){
            return array("code"=>502,"msg"=>"该用户没有权限!");
        }
        return array("code"=>200,"msg"=>"ok");
    }
    /**
     * 获取一条 群信息
     * @param unknown $group_id  */
    public function getGroupOne($group_id){
        
    }
    /**
     * 获取群认证
     * @param unknown $gruop_id  */
    public function getGruopAuth($group_id) {
        return $GLOBALS["db"]->select('is_auth')->from('chat_group')->where("group_id=".$group_id)->row()["is_auth"]; 
    }
    /**
     * 获取群主uid
     * @param unknown $gruop_id  */
    public function getGroupUid($group_id){
        return $GLOBALS["db"]->select('uid')->from('chat_group_user')->where("group_id=".$group_id." AND type=1")->row()["uid"];
    }
    /**
     * 加群操作
     * @param unknown $to_uid
     * @param unknown $gruop_id
     *   */
    public function addUserGroup($to_uid,$group_id){
        $uid_str="";
        foreach (explode(",",$to_uid,-1) AS $v){
            $where  ="uid=".$v." AND status=1 AND group_id=".$group_id;
            $res    =$GLOBALS["db"]->select('uid')->from('chat_group_user')->where($where)->row();
            if (empty($res)){//不存在
                $uid_str.=$v.",";
                $nickname   =$GLOBALS["db"]->select('nickname')->from('chat_user')->where("uid=".$v)->row()["nickname"];
                $GLOBALS["db"]->insert('chat_group_user')->cols(array(
                    "uid"          =>$v,
                    "type"         =>0,
                    "remark"       =>$nickname,
                    "group_id"     =>$group_id,
                    "create_date"  =>time()
                ))->query();
            }
        }
        return array("uid_str"=>$uid_str);
    }
    /**
     * 添加 群通知
     * @param unknown $info  */
    public function addInform($info){
        //chat_common_inform
        $push_arr=array();
        //验证  消息是否有这条 数据
        foreach (explode(",",$info["to_uid"],-1) AS $v){
            $where  ="send_id=".$v." AND receive_id=".$info["group_id"]." AND inform_type=2 AND status=0";
            $res    =$GLOBALS["db"]->select('common_inform_id')->from('chat_common_inform')->where($where)->row();
            if (empty($res)){//不能存在 
                //在验证 该会员是否在该群
                $where  ="uid=".$v." AND group_id=".$info["group_id"];
                $res1   =$GLOBALS["db"]->select('group_user_id')->from('chat_group_user')->where($where)->row();
                if (empty($res1)){//发起验证 操作
                    $arr    =array(
                        "send_id"       =>$v,
                        "receive_id"    =>$info["group_id"],//群id
                        "inform_type"   =>2,
                        "create_date"   =>time()
                    );   
                    $push_arr[]=array(
                        "nickname"          =>$GLOBALS["db"]->select("nickname")->from("chat_user")->where("uid={$info["uid"]}")->row()["nickname"],
                        "send_uid"          =>$v,
                        "common_inform_id"  =>$GLOBALS["db"]->insert('chat_common_inform')->cols($arr)->query()
                    );
                }
            }
        }
        return $push_arr;
    }
    /**
     * 取一条消息通知
     * @param unknown $common_inform_id  */
    public function getInformOne($common_inform_id,$select="*"){
        return $GLOBALS["db"]->select($select)->from('chat_common_inform')->where("common_inform_id=".$common_inform_id)->row();
    }
    /**
     * 删除群成员
     * @param unknown $group_id
     * @param unknown $uid_str  */
    public function delGroupUser($group_id,$uid_str){
        $where  ="group_id=".$group_id." AND uid in(".$uid_str.")";
        $GLOBALS["db"]->delete('chat_group_user')->where($where)->query();
        return;
    }
    /**
     * 获取 群成员列表
     * @param unknown $group_id  */
    public function getGroupUserAll($group_id,$select="*"){
        return $GLOBALS["db"]->select($select)->from('chat_group_user')->where("group_id=".$group_id)->orderByDESC(array("type"))->query(); 
    }
    /**
     * 修改群信息
     * @param unknown $group_id
     * @param unknown $set_arr  */
    public function updateInfo($group_id,$set_arr){
        //array_keys(array,value) 
        $GLOBALS["db"]->update('chat_group')->cols($set_arr)->where('group_id='.$group_id)->query();
    }
    /**
     * 转让群
     * @param unknown $data  */
    public function transferGroup($data){
        //1.修改 成员
        $GLOBALS["db"]->update('chat_group_user')->cols(array("type"=>0))->where('group_id='.$data["group_id"])->query();
        //2.修改接受者 群权限
        $GLOBALS["db"]->update('chat_group_user')->cols(array("type"=>1))->where('group_id='.$data["group_id"]." AND uid=".$data["receive_uid"])->query();
        //3.修改 群会员串
        $uid_str=join(",",array_column ($this->getGroupUserAll($data["group_id"],"uid"),"uid"));;
        $GLOBALS["db"]->update('chat_group')->cols(array("uid_str"=>$uid_str))->where('group_id='.$data["group_id"])->query();
        //4.修改缓存 串
        return $uid_str;
    }
    /**
     * 保存群聊
     * @param unknown $data  */
    public function saveChat($data){
        $GLOBALS["db"]->update('chat_group_user')
            ->cols(array("is_group_chat"=>$data["is_group_chat"]))
            ->where('group_id='.$data["group_id"]." AND uid=".$data["uid"])
            ->query();
        return;
    }
    /**
     * 退出群
     * @param   $group_id
     * @param   $uid    
     *   */
    public function checkOutGroup($group_id,$uid){
        //1退出操作 
        $GLOBALS["db"]->delete('chat_group_user')->where('group_id='.$group_id." AND uid=".$uid)->query();
        //2返回群成员串
        return $this->getGroupUserAll($group_id,"uid");
    }
    /**
     * 消息置顶
     * @param unknown $data  */
    public function updateGroupStick($data){
        $GLOBALS["db"]->update('chat_group_user')
            ->cols(array("is_stick"=>$data["is_stick"]))
            ->where('group_id='.$data["group_id"]." AND uid=".$data["uid"])
            ->query();
        return;
    }
    /**
     * 开启禁音
     * @param unknown $data  */
    public function updateGroupVoice($data){
        $GLOBALS["db"]->update('chat_group_user')
            ->cols(array("is_voice"=>$data["is_voice"]))
            ->where('group_id='.$data["group_id"]." AND uid=".$data["uid"])
            ->query();
        return;
    }
    /**
     * 设置群成员 备注
     * @param unknown $data  */
    public function updateUserRemark($data){
        //1.查询是否有这条数据
        $where  ="uid={$data["uid"]} AND to_uid={$data["to_uid"]} AND group_id={$data["group_id"]} AND status=1";
        $res    =$GLOBALS["db"]->select("group_user_remark_id")->from("chat_group_user_remark")->where($where)->row();
        if (!empty($res)){//存在就修改
            $GLOBALS["db"]->update('chat_group_user_remark')
            ->cols(array("remark"=>$data["remark"]))
            ->where('group_user_remark_id='.$res["group_user_remark_id"])
            ->query();
        }else{//添加
            $GLOBALS["db"]->insert('chat_group_user_remark')->cols(array(
                "uid"          =>$data["uid"],
                "to_uid"       =>$data["to_uid"],
                "group_id"     =>$data["group_id"],
                "remark"       =>$data["remark"], 
                "create_date"  =>time(),
            ))->query();
        }
        return;
    }
    /**
     * 显示成员昵称
     * @param unknown $data  */
    public function updateShowNickname($data){
        $GLOBALS["db"]->update('chat_group_user')
        ->cols(array("is_show_nickname"=>$data["is_show_nickname"]))
        ->where('group_id='.$data["group_id"]." AND uid=".$data["uid"])
        ->query();
        return;
    }
    /**
     * 修改 自己在群里面的昵称
     * @param unknown $data  */
    public function updateMyRemark($data){
        $GLOBALS["db"]->update('chat_group_user')
            ->cols(array("remark"=>$data["remark"]))
            ->where('group_id='.$data["group_id"]." AND uid=".$data["uid"])
            ->query();
        return;
    }
    //批量加入群
    public function batchGroup($group_id){
        $where        ="receive_id=".$group_id." AND inform_type=2 AND status=0";
        $send_list    =$GLOBALS["db"]->select('send_id')->from('chat_common_inform')->where("group_id=".$group_id)->query();
        //修改消息状态
        $GLOBALS["db"]->update('chat_common_inform')->cols(array("status"=>1))->where($where)->query();
        foreach ($send_list as $v){
            //验证该会员在群里没有
            $where      ="uid=".$v["send_id"]." AND group_id=".$group_id;
            $reg        =$GLOBALS["db"]->select("group_user_id")->from("chat_group_user")->where($where)->query();
            if (empty($reg)){
                $nickname   =$GLOBALS["db"]->select('nickname')->from('chat_user')->where("uid=".$v["send_id"])->row()["nickname"];
                $map=array(
                    "uid"           =>$v["send_id"],
                    "group_id"      =>$group_id,
                    "remark"        =>$nickname,
                    "create_date"   =>time()
                );
                $GLOBALS["db"]->insert('chat_group_user')->cols($map)->query();
            }
        }
        return $send_list;
    }
    //修改公告时间
    public function updateAfficheUpdateDate($group_id){
        $GLOBALS["db"]->update('chat_group')->cols(array("group_id"=>$group_id))->where('affiche_update_date='.time())->query();
    }
}
