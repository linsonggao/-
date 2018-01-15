<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    '__pattern__'                                => [
        'name' => '\w+',
    ],
    '[hello]'                                    => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post'], ['id' => '\d+']],
    ],
    // 路由规则定义
    //url->folder/controller/function
    'admin/login/'                               => 'admin/index/login',
    'admin/login_action/'                        => 'admin/index/login_action',
    'admin/lost_password/'                       => 'admin/index/lost_password',
    'admin/logout/'                              => 'admin/index/logout',

    'admin/administrator/create'                 => 'admin/administrator/create',
    'admin/administrator/add'                    => 'admin/administrator/add',
    'admin/administrator/update/:id'             => 'admin/administrator/update',
    'admin/administrator/delete/:id'             => 'admin/administrator/delete',
    'admin/administrator/delete_image/:id'       => 'admin/administrator/delete_image',
    'admin/administrator/update_expire_time/:id' => 'admin/administrator/update_expire_time',
    'admin/administrator/:id'                    => 'admin/administrator/read',

    'admin/set/add_role'                         => 'admin/set/add_role',
    'admin/set/create_role'                      => 'admin/set/create_role',
    'admin/set/update_role_privs/:id'            => 'admin/set/update_role_privs',
    'admin/set/role_delete/:id'                  => 'admin/set/role_delete',
    'admin/set/:id'                              => 'admin/set/read',
    'admin/welcome'                              => 'admin/index/welcome',

    'admin/player/update/:id'                    => 'admin/player/update',
    'admin/player/log/:id'                       => 'admin/player/log',
    'admin/player/amount_log/:id'                => 'admin/player/amount_log',
    'admin/player/:id'                           => 'admin/player/read',

    'admin/group/update/:id'                      => 'admin/group/update',
    'admin/group/:id'                            => 'admin/group/read',

    'admin/announcement/create'                 =>  'admin/announcement/create',

    'admin/complain/update_complain'            =>  'admin/commoncomplain/update_complain',

    'admin/test'                                 => 'admin/test/test',

];
