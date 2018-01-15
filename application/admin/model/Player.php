<?php
namespace app\admin\model;

use think\Model;

class Player extends Model
{

    // 设置完整的数据表（包含前缀）
    protected $table = 'chat_user';

    // 关闭自动写入时间戳
    //protected $autoWriteTimestamp = false;

    //默认时间格式
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $autoWriteTimestamp = 'datetime';
    protected $type       = [
        // 设置时间戳类型（整型）
        'create_at'     => 'datetime',
        'update_at'     => 'datetime',
        'last_login_at' => 'datetime',

    ];
    protected $updateTime = 'update_date';
}