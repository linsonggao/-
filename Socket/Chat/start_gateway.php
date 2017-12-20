<?php 
use \Workerman\Worker;
use \GatewayWorker\Gateway;
use \Workerman\Autoloader;
use Workerman\Connection\TcpConnection;
// 自动加载类 win 会用到 
require_once __DIR__ . '/../../config.php';
// 设置每个连接接收的数据包最大为1024000字节
TcpConnection::$maxPackageSize = 2048000;
// gateway 进程
$gateway = new Gateway("tcp://0.0.0.0:7373");
// 设置名称，方便status时查看
$gateway->name = 'ChatGateway';
// 设置进程数，gateway进程数建议与cpu核数相同
$gateway->count = 4;
// 分布式部署时请设置成内网ip（非127.0.0.1）
$gateway->lanIp = '127.0.0.1';
// 内部通讯起始端口，假如$gateway->count=4，起始端口为4000
// 则一般会使用4000 4001 4002 4003 4个端口作为内部通讯端口 
$gateway->startPort = 3300;
// 心跳间隔
$gateway->pingInterval = 5;
// 心跳数据
$type='{"type":"ping"}';
if ($GLOBALS["config"]["DEBUG"]==false){
    $type=$GLOBALS["config"]["EXTEND"]["MCrypt"]->encrypt('{"type":"ping"}');
}
$gateway->pingData = $type.'\r\n';
// 服务注册地址
$gateway->registerAddress = '127.0.0.1:1237';
/* 
// 当客户端连接上来时，设置连接的onWebSocketConnect，即在websocket握手时的回调
$gateway->onConnect = function($connection)
{
    $connection->onWebSocketConnect = function($connection , $http_header)
    {
        // 可以在这里判断连接来源是否合法，不合法就关掉连接
        // $_SERVER['HTTP_ORIGIN']标识来自哪个站点的页面发起的websocket链接
        if($_SERVER['HTTP_ORIGIN'] != 'http://chat.workerman.net')
        {
            $connection->close();
        }
        // onWebSocketConnect 里面$_GET $_SERVER是可用的
        // var_dump($_GET, $_SERVER);
    };
}; 
*/
// 如果不是在根目录启动，则运行runAll方法
if(!defined('GLOBAL_START'))
{
    Worker::runAll();
}

