<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace tasks\SphinxTime;
use core\lib\Task;
use core\lib\Utils;
use core\lib\Db;
/**
 *  定时刷新 sphinx 索引
 */
class SphinxTimeTask extends Task{
    public $_timer='/2 * * * * * *';
    /**
     * 入口
     */
	public function run(){
	    //刷新 索引
	    shell_exec ( 'usr/local/coreseek/bin/indexer -c  /usr/local/coreseek/etc/csft_mysql.conf  --all --rotate' );
	}
}
