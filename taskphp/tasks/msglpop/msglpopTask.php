<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace tasks\MsgLpop;
use core\lib\Task;
use core\lib\Utils;
use core\lib\Db;
/**
 *  群组消息 出列操作
 *  这里 暂时使用 单进程  后期在优化消息队列多进程
 */
class MsgLpopTask extends Task{
    public $_timer='/2 * * * * * *';
    /**
     * 入口
     */
	public function run(){
	    $redis=$GLOBALS["config"]["EXTEND"]["RedisMsg"]->_redisRun;
	    $msg_group_arr=array();
	    //队列弹出数据
	    while (true){
	        $val=$redis->lpop("group_list");
	        if($val==false){
	            $redis->delete('group_list');break;
	        }else{
	            if (!empty($id)){
	                $id=explode(":",$val);
	                $msg_group_arr[$id[0]][]=$id[1];
	            }
	        }
	    }
	    $db=Db::connect(Utils::config('db'));
	    //数据入库
	    foreach ($msg_group_arr as $msg_group_id=>$v){
	        //查询数据库一条信息
	        $not_read_uid=$db->field("not_read_uid")->table("tourism_game_common_game")->where(array("msg_group_id"=>$msg_group_id))->find()["not_read_uid"];
	        //计算出差集
	        $not_read_uid_arr=array_diff(explode(",",ltrim(rtrim($not_read_uid),",")),$v);
	        if (!empty($not_read_uid_arr)){
	            $not_read_uid_join=",".join(",",$not_read_uid_arr).",";
	            //执行修改
	            $db::query("update chat_msg_group set read_uid='{$not_read_uid_join}' where msg_group_id=".$msg_group_id);
	        }  
	    }
	    Db::clear();
	}
}
