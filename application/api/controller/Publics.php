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
class Publics extends Common{
    /**
     * app 启动接口
     *   */
    public function start(){
        return $this->apiJson(array(
            "debug"         =>(int)$GLOBALS["config"]["DEBUG"],//调试模式
            "min_version"   =>$GLOBALS["config"]["MIN_VERSION"],//最低版本号  
            "tcp_url"       =>$GLOBALS["config"]["TCP_URL"],
            "resource_url"  =>$GLOBALS["config"]["RESOURCE_URL"],
            "sms_time"      =>$GLOBALS["config"]["SMS_TIME"]
        ),200,"ok",true);
    }
    /**
     * 文件上传接口
     *   */
    public function uploads(){
        $files = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $dir=ROOT_PATH . 'public' . DS . 'uploads';
        foreach($files as $file){
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->validate([
                'size'=>102400000,
                'ext'=>array("jpg","gif","png"),
            ])->move($dir);
            if($info){
                $path="public".DS."uploads".DS.$info->getSaveName();
                $thumb_path="";
                if (in_array($info->getExtension(),array("jpg","gif","png"))){//如果查询到 是图片类型 就缩约   
                    $image = \think\Image::open($path);
                    $thumb_path="public".DS."uploads".DS.date("Ymd").DS."thumb_".$info->getFilename();
                    $image->thumb(150, 150)->save($thumb_path);
                }
                //入库 数据库
                model("CommonUploads")->insert([
                    'suffix'        =>  $info->getExtension(),
                    'thumb_url'     =>  $thumb_path,
                    "url"           =>  $path,
                    "create_date"   =>  time()
                ]);
                $file_arr[]=array(
                    "path"          =>$path,
                    "thumb_path"    =>$thumb_path
                );
            }else{
                return $this->apiJson('',415,$file->getError());
            }
        }
        return $this->apiJson($file_arr,200,"ok");
    }
    /**
     * 短信发送
     *   */
    public function sendSms(){
        $this->regInfoNull($this->info, array("phone","type"));
        $phone= explode("+",$this->info["phone"])[1];
        if ($this->info["type"]==1){//绑定手机
            if (!empty(model("user")->regPhoneThird($phone))){
                return $this->apiJson('',202,"该手机已经绑定过手机号!");
            }
        }else if($this->info["type"]==2){//登录
            if (empty(model("user")->where(array("phone"=>$phone))->count())){
                return $this->apiJson('',203,"你输入的是一个无效的手机号码。");
            }
        }
        $code   =mt_rand(100000,999999);
        \think\Db::name("common_sms_code")->where(array("phone"=>$phone))->update(array("status"=>-1));
        \think\Db::name("common_sms_code")->insert(array(
            "phone"         =>$phone,
            "type"          =>$this->info["type"],
            "code"          =>$code,
            "create_date"   =>time()
        ));
        $sendSms=new \SendSms();
        $result=$sendSms->send($phone, $code, $this->info["type"]);
        return $this->apiJson(array("code"=>(string)$code),$result["code"],$result["msg"]);
    }
    /**
     * 意见反馈
     *   */
    public function retroaction(){
        $this->regInfoNull($this->info, array("uid","facility","content"));
        \think\Db::name("common_retroaction")->insert(array(
            "uid"           =>$this->info["uid"],
            "content"       =>$this->info["content"],
            "facility"      =>$this->info["facility"],
            "create_date"   =>time()
        ));
        return $this->apiJson('',200,"ok");
    }
}
