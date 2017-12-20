<?php 
// +----------------------------------------------------------------------
// | 日志操作
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace Extend;
class Log{
    public static $_dir;
    /**
     * 日志写入
     * @param unknown $msg
     * @param unknown $type log error
     * 
     *   */
    public static function write($msg,$description="",$type="log"){
        self::regFile();
        $log="描述:".$description.PHP_EOL;
        $log.="时间:".date("Y-m-d H:i:s").PHP_EOL;
        $log.="参数:".$msg.PHP_EOL;
        $log.="-------------------------------------------------------------------------".PHP_EOL;
        if ($GLOBALS["config"]["IS_SHOW_LOG"]==true){
            echo $log;
        }
        error_log($log ,  3 ,  self::$_dir."/".date("Y-m-d")."-".$type.".log");
    }
    /**
     * 验证 文件夹是否存在
     *   */
    private static function regFile(){
        self::$_dir    =$GLOBALS["config"]["SOCKET_LOG_PATH"];
        if (!file_exists(self::$_dir)){//存在
            mkdir (self::$_dir,0777);
        }
    }
}

?>