<?php
namespace app\admin\controller;
use think\Controller;

class Test extends Controller
{
	//模块基本信息
	private $data = array(
		'module_name' => '权限设置',
		'module_url'  => '/admin/set/',
		'module_slug' => 'set',
		'upload_path' => UPLOAD_PATH,
		'upload_url'  => '/public/uploads/',
		);
    /**
     * [index 获取用户数据列表]
     * @return [type] [description]
     */
    public function test()
    {
    	echo 'test';exit;
        return $this->fetch();
    }
}