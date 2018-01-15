<?php
namespace app\admin\model;

use think\Model;

class UserLog extends Model
{

    // 设置完整的数据表（包含前缀）
    protected $table = 'chat_user_log';

    // 关闭自动写入时间戳
    //protected $autoWriteTimestamp = false;

    //默认时间格式
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $autoWriteTimestamp = 'datetime';
    protected $type       = [
        'update_date'     => 'datetime',
    ];
    protected $updateTime = 'update_date';
}