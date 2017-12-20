<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace tasks\ClearMSM;
use core\lib\Task;
use core\lib\Utils;
use core\lib\Db;
/**
 *  清除过期的短信验证码
 */
class ClearMSMTask extends Task{
    public $_timer='/2 * * * * * *';
    /**
     * 入口
     */
	public function run(){
	    $sms_time=$GLOBALS["config"]["SMS_TIME"];
	    $db=Db::connect(Utils::config('db'));
	    $db->query("UPDATE chat_common_sms_code SET status=-1 WHERE (create_date <".(time()-$sms_time)." )  AND status=1");
	    Db::clear();
	}
}
