<?php
namespace app\admin\controller;
use app\admin\model\Role;
use app\admin\model\Privs;
use app\admin\model\RolePrivs;
use app\admin\controller\AdminAuth;
use think\Validate;
use think\Image;
use think\Request;
class Set extends AdminAuth
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
    public function index()
    {
        $list =  Role::where('is_delete','=','0')->where('id','gt',1)->paginate();
        $this->assign('data',$this->data);
        $this->assign('list',$list);
        return $this->fetch();
    }
    /**
     * [read 读取用户数据]
     * @param  string $id [用户ID]
     * @return [type]     [description]
     */
    public function read($id='')
    {
        

        //默认值设置
        $item = RolePrivs::all(['role_id'=>$id]);
        //dump($item);exit;
        $privs_item = [];
        foreach ($item as $key => $value) {
        	$itemModel = RolePrivs::get($value['id']);
        	$privs_item['p'.$value['privs_id']] = $itemModel->privs->name;
        }
        $this->assign('item',$privs_item);//1,2,3
        $this->assign('id',$id);
        $default = [];
        $privs = Privs::where('pid',0)->order("sort DESC")->select();
        $edit_fields = [];
        foreach ($privs as $key => $value) {
            //$value['id']
            $children = [];
            $default = [];
        	$default[$value['id']] = $value['name'];
            $children = Privs::all(['parent'=>$value['id']]);
            foreach ($children as $k => $v) {
                $default['p'.$v['id']] = $v['name'];
                $edit_fields['p'.$v['id']] = $v['name'];
            }
            $edit_fields['p'.$value['id']] = array('type' => 'checkbox', 'label' => $value['name'], 'default' => $default);
        }
        // dump($privs_item);
        // dump($edit_fields);exit;
        $this->data['edit_fields'] = $edit_fields;
        $this->assign('data',$this->data);
        return view();
    }
    /**
     * [add 新增用户数据ACTION，create()页面表单数据提交到这里]
     * @return [type] [description]
     */
    public function add_role()
    {
        $user = new Role;
        $data = input('post.');
        //ump($data);exit;
        $rule = [
            'name|昵称'   => 'require|min:2',
        ];
        // 数据验证
        $validate = new Validate($rule);
        $result   = $validate->check($data);
        if(!$result){
            return  $validate->getError();
        }

        if ($id = $user->validate(true)->insertGetId($data)) {
            return $this->success('角色添加成功',$this->data['module_url'],1);
        } else {
            return $this->error($user->getError());
        }
    }
    public function create_role()
    {
    	$this->data['edit_fields'] = array(
			'name' => array('type' => 'text', 'label'     => '角色名'),
        );

        //默认值设置
        $item['status'] = '正常';
        //$item['salt'] = rand(100,999);

        $this->assign('item',$item);
        $this->assign('data',$this->data);
        return view();
    }
    public function update_role_privs($id)
    {
        //dump($id);exit;
        //dump(input('post.'));
        $data = input('post.');
        $rp = RolePrivs::destroy(['role_id'=>$id]);
        if($rp || !RolePrivs::where('role_id',$id)->find()){
            $updateData = [];
            $rpModel = new RolePrivs;
            foreach ($data as $key => $parent_privs) {
                foreach ($parent_privs as $k => $privs_id) {
                    //str_replace('p','',$privs_id);
                    array_push($updateData,['role_id'=>$id,'privs_id'=>str_replace('p','',$privs_id)]);
                    //$rp->insertGetId(['role_id'=>$id,'privs_id'=>$v]);
                }
            }
            //dump($updateData);exit;
            $rpModel->saveAll($updateData);
            return $this->success('修改角色权限成功',$this->data['module_url'].$id);
        }
    }
    /**
     * [delete 删除用户数据(伪删除)]
     * @param  [type] $id [表ID]
     * @return [type]     [description]
     */
    public function role_delete($id)
    {
        $user = new Role;
        $data['id'] = $id;
        $data['is_delete'] = 1;
        if ($user->update($data)) {
        	$data['error'] = 0;
        	$data['msg'] = '删除成功';
        } else {
        	$data['error'] = 1;
        	$data['msg'] = '删除失败';
        }
        return $data;

        // 真.删除，不想用伪删除，请用这段
        // $user = Administrator::get($id);
        // if ($user) {
        //     $user->delete();
        //     $data['error'] = 0;
        // 	$data['msg'] = '删除成功';
        // } else {
        // 	$data['error'] = 1;
        // 	$data['msg'] = '删除失败';
        // }
        // return $data;
    }
}