<?php
// +----------------------------------------------------------------------
// | 群组
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller;
class Group extends Common{
    //创建群
    /* public function createGroup(){
        $this->regInfoNull($this->info, array("uid","uid_join"));
        $data=model("Group")->createGroup($this->info["uid"],$this->info["uid_join"]);
        //存入 缓存
        $GLOBALS["config"]["EXTEND"]["RedisMsg"]->setUidJoin($data["group_id"],$this->info["uid"].",".$this->info["uid_join"]);
        return $this->apiJson($data,200,"ok");
    } */
    /**
     * 获取保存群聊列表
     * @param   $uid
     *   */
    public function getList(){
        $this->regInfoNull($this->info, array("uid"));
        $list   =array();
        foreach (model("Group")->getList($this->info["uid"]) as $v){
            $v["content"]   =(string)$GLOBALS["config"]["EXTEND"]["Rediss"]->strGet($GLOBALS["config"]["REDIS_TABLE"]["group:newcontent:"]["name"]);
            $list[]=$v;
        }
        return $this->apiJson($list,200,"ok");
    }
    /**
     * 获取群详情
     *   */
    public function details(){
        $this->regInfoNull($this->info, array("uid","group_id"));
        //获取群信息   包含自定义设置
        $data    =model("Group")->details($this->info["group_id"],$this->info["uid"]);
        return $this->apiJson($data,200,"ok");
    }
    
    
}
