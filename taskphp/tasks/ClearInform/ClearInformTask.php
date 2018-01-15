<?php
// +----------------------------------------------------------------------
// | 定时清除 消息
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace tasks\ClearInform;
use core\lib\Task;
use core\lib\Utils;
use core\lib\Db;
/**
 *  清除过期的通知消息
 */
class ClearInformTask extends Task{
    public $_timer='/2 * * * * * *';
    /**
     * 入口
     */
	public function run(){
	    $inform_time   =$GLOBALS["config"]["INFORM_TIME"];
	    $db            =Db::connect(Utils::config('db'));
	    $db::name("common_inform")->where("create_date<".(time()-$inform_time))->update(array("status"=>2));
	    Db::clear();
	}
}
