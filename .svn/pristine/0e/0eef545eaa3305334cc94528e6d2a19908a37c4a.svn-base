<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace tasks\MsgSystem;
use core\lib\Task;
use core\lib\Utils;
use core\lib\Db;
/**
 *  系统消息 出列操作
 */
class MsgSystemTask extends Task{
    public $_timer='/2 * * * * * *';
    /**
     * 入口
     */
	public function run(){
	    $redis=$GLOBALS["config"]["EXTEND"]["RedisMsg"]->_redisRun;
	    //队列弹出数据
	    $data=array();
	    while (true){
	        $val=$redis->lpop($GLOBALS["config"]["REDIS_TABLE"]["msg_system"]["name"]);
	        if($val==false){
	            $redis->delete($GLOBALS["config"]["REDIS_TABLE"]["msg_system"]["name"]);break;
	        }else{
	            $info=json_decode($val);
	            if (in_array($info["msg_type"],array(1))){
	                foreach (explode(",",$info["msg_system_id"],-1) as $v){
	                    $data[]=array(
	                        "msg_system_id"    =>$v,
	                        "uid"              =>$info["uid"],
	                        "create_date"      =>$info["create_date"]
	                    );
	                }
	            }
	        }
	    }
	    //数据入库
	    $db=Db::connect(Utils::config('db'));
	    $db::name("user_read_system")->insertAll($data);
	    Db::clear();
	}
}
