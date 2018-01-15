<?php
namespace app\admin\controller;
use app\admin\model\Player as User;
use app\admin\model\UserLog;
use app\admin\model\UserAmountLog;
use app\admin\controller\AdminAuth;
use think\Validate;
use think\Image;
use think\Request;
use think\Controller;

class Player extends AdminAuth
{
	//模块基本信息
	private $data = array(
		'module_name' => '用户管理',
		'module_url'  => '/admin/player/',
		'module_slug' => 'player',
		);
    /**
     * [index 获取用户数据列表]
     * @return [type] [description]
     */
    public function index()
    {
    	$list =  User::where('uid','gt',0)->paginate();
    	$this->assign('data',$this->data);
    	$this->assign('list',$list);
        return $this->fetch();
    }
    public function read($id='')
    {
        $this->data['edit_fields'] = array(
			'username' => array('type' => 'text', 'disabled'=>1, 'label'     => '用户名'),
			'nickname' => array('type' => 'text', 'disabled'=>1, 'label'     => '用户昵称'),
			'phone'   => array('type' => 'text', 'disabled'=>1, 'label'     => '手机号'),
			//'head_img'   => array('type' => 'file','label'     => '头像'),
			'status'   => array('type' => 'radio', 'label' => '状态','default'=> array(1 => '正常', -1 => '冻结')),
        );

        //默认值设置
        $item = User::get($id);
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
        $user = new User;
        $data = input('post.');
        $updateDate['status'] = $data['status'];
        //$updateDate['uid'] = $id;
        $user->where(['uid'=>$id])->update($updateDate);
        return $this->success('用户信息更新成功',$this->data['module_url'].'/list');
    }
    public function log($id)
    {
        $data = input('post.');
        $searchArray = ['uid'=>$id];
        if($data){
            $searchArray = array_merge($searchArray,['type'=>$data['type']]);
        }
        $list =  UserLog::where($searchArray)->order('update_date desc')->paginate();
        $this->assign('data',$this->data);
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function amount_log($id)
    {
        $list =  UserAmountLog::where('uid','=',$id)->order('update_date desc')->paginate();
        $this->assign('data',$this->data);
        $this->assign('list',$list);
        return $this->fetch();
    }
}