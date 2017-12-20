<?php
namespace app\admin\model;

use think\Model;

class RolePrivs extends Model
{

    // 设置完整的数据表（包含前缀）
    protected $table = 'chat_system_role_privs';

    // 关闭自动写入时间戳
    //protected $autoWriteTimestamp = false;


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
    public function privs()
    {
        return $this->hasOne('Privs','id','privs_id');
    }
}