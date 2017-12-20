<?php
namespace app\admin\model;

use think\Model;

class Role extends Model
{

    // 设置完整的数据表（包含前缀）
    protected $table = 'chat_system_role';

    // 关闭自动写入时间戳
    //protected $autoWriteTimestamp = false;

    //默认时间格式
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $autoWriteTimestamp = 'datetime';
    protected $type       = [
        // 设置时间戳类型（整型）
        'create_at'     => 'datetime',
        'update_at'     => 'datetime',
    ];

    //自动完成
    protected $insert = [
        'create_at',
        'update_at',
    ];

    protected $createTime = 'create_at';// 默认的字段为create_time 和 update_time
    protected $updateTime = 'update_at';

    // status属性读取器
    protected function getIsDeleteAttr($value)
    {
        $status = [0 => '正常', 1 => '删除'];
        return $status[$value];
    }




    // // create_time读取器设置时间格式
    // protected function getCreateTimeAttr($datetime)
    // {
    //     return date('Y-m-d H:i:s', $datetime);
    // }
    // // update_time读取器设置时间格式
    // protected function getUpdateTimeAttr($datetime)
    // {
    //     return date('Y-m-d H:i:s', $datetime);
    // }
}