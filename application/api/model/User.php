<?php
// +----------------------------------------------------------------------
// | 会员模型
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace app\api\model;
class User extends CommonModel{
    /**
     * 用openid 查询用户信息
     * @param unknown $open_id  */
    public function getWxOne($open_id){
        $result =db('user_third')->alias("a")
                ->field("b.login_number,b.max_head_img,b.uid,b.username,b.phone,b.nickname,b.status,b.sex,b.head_img,b.email,c.identity,c.realname,b.is_inform")
                ->join("chat_user b","a.uid=b.uid","left")
                ->join("chat_user_authentication c","c.uid=b.uid","left")
                ->where(["a.open_id"=>$open_id,"a.type"=>1])
                ->find();
        $this->where(array("uid"=>$result["uid"]))->update(array("login_number"=>$result["login_number"]+1));
        return $result;
    }
    /**
     * 验证 短信号码是否存在
     * @param unknown $phone
     * @param unknown $code  */
    public function regSmsCode($phone,$code){
        $where=array(
            "phone" =>$phone,
            "code"  =>$code,
            "status"=>1
        );
        $res=db("common_sms_code")->field("common_sms_code_id")->where($where)->find();
        if (empty($res)){
            return false;
        }
        return db("common_sms_code")->where('common_sms_code_id',$res["common_sms_code_id"])->update(array("status"=>-1));
    }
    /**
     * 短信登录
     * @param unknown $phone  */
    public function smsLogin($phone){
        $result=db("user")
            ->field("a.max_head_img,a.uid,a.username,a.phone,a.nickname,a.status,a.sex,a.head_img,a.email,a.is_inform,b.identity,b.realname,a.login_number")
            ->alias("a")
            ->join("chat_user_authentication b","a.uid=b.uid","left")
            ->where(array("a.phone"=>$phone,"a.status"=>array("in","-1,1")))
            ->find();
        if (empty($result)){
            return false;
        }//登录次数加1
        $this->where(array("phone"=>$phone))->update(array("status"=>1,"login_number"=>$result["login_number"]+1));
        return $result;
    }
    /**
     * 验证手机是否绑定过第三方
     *   */
    public function regPhoneThird($phone){
        $count=db("user")->alias("a")
            ->join("chat_user_third b","a.uid=b.uid","left")
            ->where(["a.phone"=>$phone])
            ->count();
        return $count;
    }
    /**
     * 实名认证
     * @param unknown $info  */
    public function authentication($info){
        $is_count=db("user_authentication")->where(array("uid"=>$info["uid"]))->count();
        if (empty($is_count)){
            db("user_authentication")->insert(array(
                "uid"           =>$info["uid"],
                "identity"      =>$info["identity"],
                "realname"      =>$info["realname"],
                "create_date"   =>time()
            ));
        }
    }
    /**
     * 验证手机号是否存在
     * @param unknown $phone  */
    public function regPhoneExist($phone){
        $result=db("user")->field("uid")->where(array("phone"=>$phone))->find();
        if (empty($result)){
            return false;
        }
        return true;
    }
    
}
