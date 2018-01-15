<?php
// +----------------------------------------------------------------------
// | 会员操作接口
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller;
class User extends Common{
    //注册
    public function register(){
        $this->regInfoNull($this->info, array("phone","nickname","sex","head_img"));
        if(model("user")->regPhoneExist($this->info["phone"])==false){//新账号 走注册流程
            //注册
            model("user")->insert(array(
                "phone"     =>$this->info["phone"],
                "nickname"  =>$this->info["nickname"],
                "sex"       =>$this->info["sex"],
                "head_img"  =>$this->info["head_img"],
                "region"    =>$this->info["region"],
                "region_code"=>$this->info["region_code"],
                "create_date"=>time(),
                "max_head_img"=>$this->info["max_head_img"]
            ));
        }
        $result=model("user")->smsLogin($this->info["phone"]);
        if (empty($this->info["open_id"])){//手机注册
            return $this->apiJson($result,200,"ok");
        }
        $this->oneLogin($result);
        //微信注册
        \think\Db::name("user_third")->insert(array(
            "uid"           =>$result["uid"],
            "type"          =>1,
            "open_id"       =>$this->info["open_id"],
            "create_date"=>time(),
        ));
        return $this->apiJson($result,200,"ok");
    }
    //处理第一次登录
    public function oneLogin($info){
        if ($info["login_number"]==0){
            $msg_id=\think\Db::name("msg")->insert(array(
                "type"          =>"text",
                "dialogue_type" =>3,
                "uid"           =>1,
                "content"       =>"欢迎某某同学",
                "create_date"   =>time(),
            ));
            \think\Db::name("msg_system")->insert(array(
                "type"          =>2,
                "system_uid"    =>1,
                "msg_id"        =>$msg_id,
                "msg_type"      =>1,
                "uid"           =>$info["uid"],
                "send_uid"      =>1,
                "receive_uid"   =>$info["uid"],
                "create_date"   =>time(),
            ));
        }
    }
    //退出
    public function checkOut(){
        return $this->apiJson('',200,"ok");
    }
    //短信验证码登录
    public function smsLogin(){
        $this->regInfoNull($this->info, array("phone","code"));
        $is_exist=model("User")->regSmsCode($this->info["phone"],$this->info["code"]);
        if ($is_exist){//
            $result=model("User")->smsLogin($this->info["phone"]);
            $this->oneLogin($result);
            if (empty($result)){
                return $this->apiJson($result,304,"用户不存在或者已经被管理员封禁");
            }
            return $this->apiJson($result,200,"ok");
        }
        return $this->apiJson('',417,"短信验证码错误");
    }
    //微信快捷登录
    public function wxLogin(){
        $this->regInfoNull($this->info, array("open_id"));
        $result=model("User")->getWxOne($this->info["open_id"]); 
        if ($result["status"]==-1){//账号已经被冻结
            return $this->apiJson('',302,"账号已经被冻结");
        }
        if (empty($result)){
            return $this->apiJson('',303,"绑定手机号");
        }
        $this->oneLogin($result);
        return $this->apiJson($result,200,"ok");
    }
    //获取好友列表
    public function getFriendList(){
        $this->regInfoNull($this->info, array("uid"));
        return $this->apiJson(model("UserRelation")->getUserList($this->info["uid"]),200,"ok",false,true);
    }
    //修改昵称
    public function updateNickname(){
        $this->regInfoNull($this->info, array("uid","nickname"));
        $config=config("SYSTEM_CONFIG")["disobey_words"];
        if (in_array($this->info["nickname"],explode(",",$config,-1))){
            return $this->apiJson('',416,"请求的带违禁字");
        }
        model("user")->where(array("uid"=>$this->info["uid"]))->update(array("nickname"=>$this->info["nickname"]));
        return $this->apiJson('',200,"ok");
    }
    //修改性别
    public function updateSex(){
        $this->regInfoNull($this->info, array("uid","sex"));
        model("user")->where(array("uid"=>$this->info["uid"]))->update(array("sex"=>$this->info["sex"]));
        return $this->apiJson('',200,"ok");
    }
    //修改邮箱
    public function updateEmail(){
        $this->regInfoNull($this->info, array("uid","email"));
        model("user")->where(array("uid"=>$this->info["uid"]))->update(array("email"=>$this->info["email"]));
        return $this->apiJson('',200,"ok");
    }
    //修改头像
    public function updateHeadImg(){
        $this->regInfoNull($this->info, array("uid","head_img","max_head_img"));
        model("user")->where(array("uid"=>$this->info["uid"]))->update(array("head_img"=>$this->info["head_img"],"max_head_img"=>$this->info["max_head_img"]));
        return $this->apiJson('',200,"ok");
    }
    
   //修改搜索状态
    public function updatePhoneSearch(){
        $this->regInfoNull($this->info, array("uid","phone_search"));
        model("user")->where(array("uid"=>$this->info["uid"]))->update(array("is_phone_search"=>$this->info["phone_search"]));
        return $this->apiJson('',200,"ok");
    }
    //用户实名认证
    public function authentication(){
        $this->regInfoNull($this->info, array("uid","identity","realname"));
        model("user")->authentication($this->info);
        return $this->apiJson('',200,"ok");
    }
    //修改消息通知
    public function updateInform(){
        $this->regInfoNull($this->info, array("uid","is_inform"));
        model("user")->where(array("uid"=>$this->info["uid"]))->update(array("is_inform"=>$this->info["is_inform"]));
        return $this->apiJson('',200,"ok");
    }
    //冻结和解冻
    public function safetyUser(){
        $this->regInfoNull($this->info, array("phone","status"));
        \think\Db::name("user")->where(array("phone"=>$this->info["phone"]))->update(array("status"=>$this->info["status"]));
        return $this->apiJson('',200,"ok");
    }
}
