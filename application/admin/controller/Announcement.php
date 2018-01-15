<?php
namespace app\admin\controller;
use app\admin\model\Announcement as AnnouncementModel;
use app\admin\controller\AdminAuth;
use think\Validate;
use think\Image;
use think\Request;
use think\Controller;

class Announcement extends AdminAuth
{
	//模块基本信息
	private $data = array(
		'module_name' => '公告管理',
		'module_url'  => '/admin/announcement/',
		'module_slug' => 'announcement',
		);
    /**
     * [index 获取用户数据列表]
     * @return [type] [description]
     */
    public function index()
    {
    	$list =  AnnouncementModel::where('system_announcement_id','gt',0)->order('system_announcement_id desc')->paginate();
    	$this->assign('data',$this->data);
    	$this->assign('list',$list);
        return $this->fetch();
    }
    public function read($id='')
    {
        $this->data['edit_fields'] = array(
			'title' => array('type' => 'text',  'label'     => '标题'),
            'content' => array('type' => 'text',  'label'     => '标题'),
        );

        //默认值设置
        $item = AnnouncementModel::get($id);
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
        $user = new AnnouncementModel;
        $data = input('post.');
        $updateDate['status'] = $data['status'];
        //$updateDate['uid'] = $id;
        $user->where(['uid'=>$id])->update($updateDate);
        return $this->success('管理员信息更新成功',$this->data['module_url'].'/list');
    }
    public function create()
    {
        $this->data['edit_fields'] = array(
            'title' => array('type' => 'text', 'label'     => '标题'),
            'content' => array('type' => 'textarea', 'label'     => '内容'),
        );
        $this->assign('data',$this->data);
        return view();
    }
    public function add()
    {
        $model = new AnnouncementModel;
        $data = input('post.');

        $rule = [
            //管理员登陆字段验证
            'title|标题'   => 'require|min:2',
            'content|内容' => 'require|min:15',
            //'salt|加密盐'     => 'length:3|number',
        ];
        // 数据验证
        $validate = new Validate($rule);
        $result   = $validate->check($data);
        if(!$result){
            return  $validate->getError();
        }
        //调用接口
        controller("extend/Push")->announcement(session('uid'),$data['title'],$data['content']);
        return $this->success('公告发布成功','/admin/announcement');
    }
}