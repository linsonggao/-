<?php
// +----------------------------------------------------------------------
// | 公共数据配置
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
/*
 * 测试环境
 * 
 *   */
//数据库类型
!defined("DB_TYPE") && define("DB_TYPE","mysql");
//地址
!defined("DB_HOST") && define("DB_HOST","10.10.10.104");
//开发数据库
!defined("DB_NAME") && define("DB_NAME","chat");
//账号
!defined("DB_USER") && define("DB_USER","root");
//密码
!defined("DB_PWD") && define("DB_PWD","root");

/*
 * 生产环境
 *   */
//数据库类型
/* !defined("DB_TYPE") && define("DB_TYPE","mysql");
//地址
!defined("DB_HOST") && define("DB_HOST","");
//开发数据库
!defined("DB_NAME") && define("DB_NAME","");
//账号
!defined("DB_USER") && define("DB_USER","");
//密码
!defined("DB_PWD") && define("DB_PWD",""); */

//端口
!defined("DB_PORT") && define("DB_PORT","3306");
//表前缀
!defined("DB_PREFIX") && define("DB_PREFIX","chat_");

/*
 * redis 缓存
 *   */
!defined("REDIS_HOST") && define("REDIS_HOST","127.0.0.1");
//连接的数据库 select
//!defined("REDIS_SELECT") && define("REDIS_SELECT",0);
//端口
!defined("REDIS_PROT") && define("REDIS_PROT","6379");
//foreach ($load_conifg as $v){  include_once $v;}
$GLOBALS["config"]=array(
    "DEBUG"             =>true,//true 开启调试模式   false 关闭调试模式
    "LOG"               =>true,//是否开启日志  true开启 false关闭
    "SOCKET_LOG_PATH"   =>__DIR__.'/runtime/tcplog',
    "IS_SHOW_LOG"       =>true,//日志是否显示
    "MIN_VERSION"       =>"1211",
    "TCP_URL"           =>"tcp://10.10.10.104:7373",
    "RESOURCE_URL"      =>"http://10.10.10.104:7000/",//资源文件url
    "SMS_TIME"          =>300,//单位秒  定时清除过期验证码
    "PUSH_URL"          =>"http://10.10.10.104:2121/?type=publish",
    "SMS_TIME"          =>60,//短信倒计时时间
    //数据库配置
    "DB"=>array(
        "DB_TYPE"   =>DB_TYPE,
        "DB_HOST"   =>DB_HOST,
        "DB_NAME"   =>DB_NAME,
        "DB_USER"   =>DB_USER,
        "DB_PWD"    =>DB_PWD,
        "DB_PORT"   =>DB_PORT,
        "DB_PREFIX" =>DB_PREFIX,
        "DB_PARAMS" =>array('persist'=>true),
        "DB_LITE"   =>"",
        "DB_CHARSET" =>"utf8",
        "DB_DSN"    =>"",
        "TOKEN_ON"  =>""
    ),
    //公共第三方扩展
    
    //redis 表结构  待加工 现只供数据参考
    "REDIS_TABLE"=>array(
        "user_sign"=>array(
            "cache_time"=>0,//缓存时间  0永久
        ),
        //任务 群组 列表
        "group_list"=>array( //保存群组列表
            "type"  =>"list",
            "name"  =>"group_list",
        ),
        "inform_"=>array(//TODO 待测试缓存和动态数据的效率       消息表  计算 推送条数和最新的一条信息  计算消息列表用sphinx
            "cache_time"=>0,
            "name"      =>"inform_",
            "msg_type"  =>array("1v1","group","sysmsg")
        ),
        "msg_group_id"=>array(//群组消息队列
            "name"  =>"msg_group_id",
        ),
        "group:uid_str:"=>array(//群组 uid串   添加成员 删除成员 删除群组 等需要操作该字段 
            "name"  =>"group:uid_str:",
            "type"  =>"string"
        ),
        "msg_system"=>array(//系统消息入列
            "name"  =>"msg_system",
            "type"  =>"hash"
        )
    ),
    //聊天全部分组
    "CHAT_GROUP"=>array(
        "room_user"=>array(
            "name"  =>"roomUser"  
        )
        //1.群组
    ),
    "SPHINX_CONFIG"=>array(//sphinx 配置
        "url"   =>"127.0.0.1",
        "port"  =>9312,//端口 
    )
    //处理 消息已读和未读 采用离线消息队列
);
//加载扩展
$load_conifg=array(
    __DIR__.'/extend/MCrypt.php',
    __DIR__.'/extend/Rediss.php',
    __DIR__.'/extend/RedisMsg.php',
    __DIR__.'/extend/SphinxClient.php',
    __DIR__.'/extend/SocketClinet.php',//
);
$load_class=array();
foreach ($load_conifg as $v){
    include_once $v;
    preg_match ( "|extend\/(.*)\.php|U" ,$v,$out);
    $load_class[$out[1]]=(new $out[1]());
}
$GLOBALS["config"]["EXTEND"]=$load_class;
//只加载不实例化
$load_flie=array(
    __DIR__.'/extend/sms/drives/nusoap.php',//
    __DIR__.'/extend/sms/sms2.php',//
    __DIR__.'/extend/SendSms.php',//
);
foreach ($load_flie as $v){  include_once $v;}
