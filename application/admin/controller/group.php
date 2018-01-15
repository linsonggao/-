<?php
namespace app\admin\controller;
use app\admin\model\Group as GroupModel;
use app\admin\controller\AdminAuth;
use think\Validate;
use think\Image;
use think\Request;
use think\Controller;

class Group extends AdminAuth
{
	//模块基本信息
	private $data = array(
		'module_name' => '群管理',
		'module_url'  => '/admin/group/',
		'module_slug' => 'group',
		);
    /**
     * [index 获取用户数据列表]
     * @return [type] [description]
     */
    public function index()
    {
    	$list =  GroupModel::where('group_id','gt',0)->paginate();
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
        $item = GroupModel::get($id);
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
        $model = new GroupModel;
        $data = input('post.');
        $updateDate['status'] = $data['status'];
        //$updateDate['uid'] = $id;
        $model->where(['group_id'=>$id])->update($updateDate);
        return $this->success('管理员信息更新成功','/admin/group');
    }
}