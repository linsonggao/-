<?php
namespace app\admin\controller;
use app\admin\model\CommonGame as CommonGameModel;
use app\admin\controller\AdminAuth;
use think\Validate;
use think\Image;
use think\Request;
use think\Controller;

class CommonGame extends AdminAuth
{
	//模块基本信息
	private $data = array(
		'module_name' => '游戏管理',
		'module_url'  => '/admin/game/',
		'module_slug' => 'common_game',
		);
    /**
     * [index 获取用户数据列表]
     * @return [type] [description]
     */
    public function index()
    {
    	$list =  CommonGameModel::where('common_game_id','gt',0)->paginate();
    	$this->assign('data',$this->data);
    	$this->assign('list',$list);
        return $this->fetch();
    }
    public function read($id='')
    {
        $this->data['edit_fields'] = array(
			'name' => array('type' => 'text', 'disabled'=>1, 'label'     => '群名'),
			'status'   => array('type' => 'radio', 'label' => '状态','default'=> array(1 => '正常', -1 => '冻结')),
        );

        //默认值设置
        $item = CommonGameModel::get($id);
        $this->assign('item',$item);
        $this->assign('data',$this->data);
        return view();
    }
    /**
     * [update 更新用户数据，read()提交表单数据到这里]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update($id)
    {
        $user = new CommonGameModel;
        $data = input('post.');
        $updateDate['status'] = $data['status'];
        //$updateDate['uid'] = $id;
        $user->where(['uid'=>$id])->update($updateDate);
        return $this->success('管理员信息更新成功',$this->data['module_url'].'/list');
    }
}