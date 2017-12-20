<?php
// +----------------------------------------------------------------------
// | api公共操作
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller;
use app\extend\Strings;
use app\extend\System;
use think\Controller;
use think\Request;
class Common extends Controller{
    protected $info;//取到的get和post的值
    use System,Strings;  
    protected $config=array(//此处 写到后台配置
        "disobey_words"=>"昵称,123456,"
    );
    //初始化
    public function __construct(){
        $this->info =input();
        $this->init();
        $this->regSign();//验证签名
        config("SYSTEM_CONFIG",$this->config);
    }
    /**
     * json 数据返回
     * @param unknown $data
     * @param number $code
     * @param string $msg  */
    protected function apiJson($data,$code=200,$msg="ok",$is_encrypt=false,$is_cache=false){
        $data_json=array(
            "data"  =>$data,
            "code"  =>$code,
            "msg"   =>$msg
        );
        //是否做缓存
        if ($is_cache==true){
            $data_json["etage"]=md5(serialize($data));
            if ($data_json["etage"]==@$this->info["etage"]){
                $data_json["data"]='';
                $data_json["code"]=201;
            }
        }
        $data_json=$this->unsetnullArr($data_json);
        if ($GLOBALS["config"]["DEBUG"]==true || $is_encrypt==true){//开启调试模式
            return json($data_json,200);
        }
        //加密返回
        return $GLOBALS["config"]["EXTEND"]["MCrypt"]->encrypt(json_encode($data_json));
    }
    /**
     * 替换数组中的null
     * @param unknown $arr  */
    public function unsetnullArr($arr){
        $narr = array();
        while(list($key, $val) = each($arr)){
            if (is_array($val)){
                $val = $this->unsetnullArr($val);
                count($val)==0 || $narr[$key] = $val;
            }else{
                $val === null||$val === NULL?$narr[$key] = '':$narr[$key] = $val;
            }
        }
        return $narr;
    }
    /**
     * 验证签名
     *   */
    private function regSign(){
        static $path=array(//需要验证的 接口
            "User/getFriendList",
            "User/updateNickname",
            "User/updateSex",
            "User/updateupdateEmail",
            "User/updateHeadImg",
            //
            "User/updateEmail",
            "User/updatePhoneSearch",
            "User/authentication",
            "User/updateInform",
            "User/checkOut",
        );
        $cm=$this->_request->dispatch()["module"][1]."/".$this->_request->dispatch()["module"][2];
        if (in_array($cm,$path)){
            $info = Request::instance()->header();
            if (empty($info["sign"]) || empty($this->info["uid"]) || $info["sign"]!=md5(date("Ymd").$this->info["uid"])){
                $data=$this->apiJson('',413,"签名错误");//
                is_string($data) && die($data);
                $data->send();die;
            }
        }
    }
    /**
     * 验证 传的值是否
     * @param unknown $data
     * @param unknown $key  */
    public function regInfoNull($data,$key_arr){
        foreach ($key_arr as $v){
            if (!in_array($v,array_keys($data))){
                $result=$this->apiJson('',412,"确少请求参数");
                is_string($result) && die($result);
                $result->send();die;
            }
        }
    } 
}
