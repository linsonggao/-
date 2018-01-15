<?php
namespace app\admin\controller;
use app\admin\model\CommonComplain as CommonComplainModel;
use app\admin\controller\AdminAuth;
use think\Validate;
use think\Image;
use think\Request;
use think\Controller;

class CommonComplain extends AdminAuth
{
	//模块基本信息
	private $data = array(
		'module_name' => '投诉管理',
		'module_url'  => '/admin/complain/',
		'module_slug' => 'common_complain',
		);
    /**
     * [index 获取用户数据列表]
     * @return [type] [description]
     */
    public function index()
    {
    	$list =  CommonComplainModel::where('common_complain_id','gt',0)->paginate();
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
        $item = CommonComplainModel::get($id);
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
        $user = new CommonComplainModel;
        $data = input('post.');
        $updateDate['status'] = $data['status'];
        //$updateDate['uid'] = $id;
        $user->where(['uid'=>$id])->update($updateDate);
        return $this->success('管理员信息更新成功',$this->data['module_url'].'/list');
    }
    public function update_complain()
    {
        $data = input('post.');
        $complain = CommonComplainModel::get($data['id']);
        $complain->common_complain_id = $data['id'];
        $complain_msg = ['2'=>'通过','3'=>'拒绝'];
        //dump($data);exit;
        //$user->expire_at = date('Y-m-d h:i:s'); //
        $complain->status = $data['status'];
        if (false !== $complain->save()) {
            $data['complain'] = $complain_msg[$data['status']];
            $data['error'] = 0;
            $data['msg'] = '更新成功';
        } else {
            $data['error'] = 1;
            $data['msg'] = '更新失败';
        }
        return $data;
    }
}