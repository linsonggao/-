<?php
// +----------------------------------------------------------------------
// | 入口
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);
use \GatewayWorker\Lib\Gateway;
use Extend\Pack;
use Extend\Log;
use Extend\System;
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../help.php';
class Events{
   /**
    * 有消息时
    * @param int $client_id
    * @param mixed $message
    */
   public static function onMessage($client_id, $message){
       $Pack=new Pack($GLOBALS["config"], $message);
       C(array_merge($GLOBALS["config"]["DB"],array('DB_PARAMS'=>array('persist'=>true))));
       //解包
       $json_data=$Pack->dePack();
       if (empty($json_data)){
           Log::write($message,"解包失败","error");return;//解包失败
       }
       //进入 控制器 方法
       foreach ($json_data AS $v){
           if (@$v["type"]!="pong" && $v!=null){//不等于心跳包
               $controller  =$v["controller"];
               $method      =$v["method"];
               //验证token
               if (@$v["token"]!=date("Ymd")){
                   Log::write($message,"token错误","error");
                   Gateway::closeClient($client_id);//踢出
                   break;
               }
               //end
               System::$_v  ="v_".str_replace(".","",@$v["versions"]);
               if (empty($v["versions"])){//进入 公共连接
                   $c="Controller\\".$controller;
               }else{//进入版本 长链接
                   $c="Controller\\".System::$_v."\\".$controller;//versions
               }
               if (class_exists($c)){
                   Log::write($message,"传入的数据");
                   (new $c($Pack,$client_id,$v))->$method();
               }else{//访问的 控制器方法不存在
                   Log::write($message,"参数错误","error");
                   Gateway::closeClient($client_id);//踢出
               }
           } 
       }
   }
   /**
    * 启动时触发
    * @param integer $businessWorker 进程实例
    */
   public static function onWorkerStart($businessWorker){ 
       //TODO 如果数据库出错 可以用这个方案    mysql gone away
       Config\Db::start("db1",array(
           "host"   =>$GLOBALS["config"]["DB"]["DB_HOST"],
           'port'    => $GLOBALS["config"]["DB"]["DB_PORT"],
           'user'    => $GLOBALS["config"]["DB"]["DB_USER"],
           'password' => $GLOBALS["config"]["DB"]["DB_PWD"],
           'dbname'  => $GLOBALS["config"]["DB"]["DB_NAME"],
           'charset'    => $GLOBALS["config"]["DB"]["DB_CHARSET"]
       ));
       $GLOBALS["db"]=\GatewayWorker\Lib\Db::instance("db1"); 
   }   
   /**
    * 当客户端断开连接时
    * @param integer $client_id 客户端id
    */
   public static function onClose($client_id){  }
   /**
    * 三次握手成功后
    * @param integer $client_id 客户端id
    */
   public static function onConnect($client_id){}
   /**
    * 进程退出
    * @param integer $businessWorker 进程实例
    */
   public static function onWorkerStop($businessWorker){}
}
