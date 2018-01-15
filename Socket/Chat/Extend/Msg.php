<?php 
// +----------------------------------------------------------------------
// | 公共系统操作
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace Extend;
class Msg{
    /**
     * 序列化 聊天内容
     * @param unknown $info  */
    public static function serializeContent($info){
        $data   =array();
        switch ($info["type"]){
            case "text"://文字
                $data   =array("content"=>$info["content"],"short_content"=>$info["content"]);
                break; 
            case "img"://图片
                $content=serialize(array(
                    "url"=>$info["url"],
                    "thumb_url"=>@$info["thumb_url"],
                    "size"=>$info["size"]
                ));
                $data   =array("content"=>$content,"short_content"=>"[图片]");
                break;
            case "card":
                $content=serialize(array(
                    "nickname"=>$info["nickname"],
                    "head_img"=>$info["head_img"]
                ));
                $data   =array("content"=>$content,"short_content"=>"[名片]");
                break;
            case "recording":
                $content=serialize(array(
                    "url"=>$info["url"],
                    "time"=>$info["time"]
                ));
                $data   =array("content"=>$content,"short_content"=>"[语音]");
                break;
            case "temp"://模板
                $content=serialize(array("content"=>$info["content"]));
                $data   =array("content"=>$content,"short_content"=>"[模板]");
                break;
        }
        return $data;
    }
    //解析 内容
    public static function unserializeContent($list,$scene_type=1){
        $data   =array();
        foreach ($list  AS $v){
            $map    =array(
                "uid"       =>$v["uid"],
                "msg_id"    =>$v["msg_id"],
                "type"      =>$v["type"],
                "msg_status"=>$v["msg_status"],
                "scene_type"=>$scene_type,
                "content"   =>$v["content"]
            );
            if ($v["type"]!="text"){
                $map["content"]=unserialize($v["content"]);
            }
            $data[]=$map;
        }
        return $data;
    }
}

?>