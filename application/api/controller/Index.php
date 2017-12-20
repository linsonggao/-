<?php
// +----------------------------------------------------------------------
// | 公共控制器
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller;
class Index extends Common{
    public function index(){
        $text="";
        foreach (explode(":","123123:abc:333") as $k=>$v){
            if ($k!=0){
                $text.=$v;break;
            }
            $time=$v;
        }
        echo $time;
        dump($text);
        die;
        $uid=1;
        $admin_list=\think\Db::name("system_user")->field("system_uid")->where(array("status"=>1))->select();
        foreach ($admin_list as $v){//取全部管理员
            //msg_system
            $in="uid,".$v["system_uid"];
            $where=array(
                "uid"=>array("in",$in),
                "to_uid"=>array("in",$in),
            );
            //取到 全部信息
            $msg_count=\think\Db::name("msg_system")->where($where)->count();
            //取当前会员 已读消息
            $user_read_count=\think\Db::name("user_read_system")->where(array("uid"=>$uid))->count();
            $total_count=$msg_count-$user_read_count;
            if ($total_count>=0){//加入 
                //取最后一条信息
                $data=array(
                    "uid"=>$uid,
                    "msg_type"=>0,
                    "id"=>$v["system_uid"],
                    "number"=>$total_count,
                    "content"=>"最后一条消息"
                );
                $GLOBALS["config"]["EXTEND"]["RedisMsg"]->informIncr($data);
            }
        }
        die;
        $a=new \SendSms();
        $res=$a->send(18323456810,333,1);
        dump($res);
        die;
        $system_uid=1;
        $data=array(
            "content"=>array(
                "type"=>1,
                "text"=>"内容"
            ),
            "to"    =>$system_uid
        );//如果不传uid 是全局推送
        $GLOBALS["config"]["EXTEND"]["SocketClinet"]->pushSystemAdmin($data);
       // echo "index";
     //   $GLOBALS["config"]["EXTEND"]["MCrypt"]->encrypt();
        //return view("index",array("a"=>"b"));
    }
    public function demo(){
        $SphinxClient=$GLOBALS["config"]["EXTEND"]["SphinxClient"];
        $SphinxClient->SetServer ($GLOBALS["config"]["SPHINX_CONFIG"]["url"], $GLOBALS["config"]["SPHINX_CONFIG"]["port"]);
        
        //可选，为每一个全文检索字段设置权重，主要根据你在sql_query中定义的字段的顺序，Sphinx系统以后会调整，可以按字段名称来设定权重
        //$SphinxClient->SetWeights ( array ( 100, 1 ) );
        
        //设定搜索模式,SPH_MATCH_ALL,SPH_MATCH_ANY,SPH_MATCH_BOOLEAN,SPH_MATCH_EXTENDED,SPH_MATCH_PHRASE
        //$SphinxClient->SetMatchMode(SPH_MATCH_ALL);
        
        /**
         * 过滤不要的值
         * @param       $attribute字段 "id" 
         * @param       $values 值array(3)
         * @param       $exclude    是 
         *   */
        //$SphinxClient->SetFilter($attribute, $values, $exclude);
        
        /**
         * 设置排序
         * @param       $attribute  字段
         * @param       $func
         * @param       $groupsort  排序  @group desc 或者  @group asc
         *   */
        //$SphinxClient->SetGroupBy($attribute, $func, $groupsort);
        
        
        
        $SphinxClient->SetConnectTimeout ( 1 );
        $SphinxClient->SetArrayResult ( true );
        
        //$cl->SetWeights ( array ( 100, 1 ) );
        $SphinxClient->SetMatchMode ( SPH_MATCH_EXTENDED2 );
        
        $SphinxClient->SetSortMode (SPH_SORT_ATTR_DESC,"group_id");
        
        $SphinxClient->SetRankingMode ( SPH_RANK_WORDCOUNT );
        
        
        
        
      //  $SphinxClient->SetLimits($offset,$limit);
        
        $res = $SphinxClient->Query ( ',3,', "mysql" );
        dump($res);
        
        
        
        /*
         $cl = new SphinxClient ();
$cl->SetServer ( '127.0.0.1', 9312);
$cl->SetConnectTimeout ( 1 );
$cl->SetArrayResult ( true );
//$cl->SetWeights ( array ( 100, 1 ) );
$cl->SetMatchMode ( SPH_MATCH_EXTENDED2 );
$cl->SetRankingMode ( SPH_RANK_WORDCOUNT );
//$cl->SetFilter('group_id', array(2),true);
//$cl->SetSortMode ( SPH_SORT_EXTENDED, '@weight DESC' );
//$cl->SetSortMode ( SPH_SORT_EXPR, $sortexpr );

//$cl->SetFieldWeights(array('title'=>10,'content'=>1));
         *   */
        
    }
    public function demo2(){
        echo "demo2";
        
    }
    public function demo1(){
        $redis=$GLOBALS["config"]["EXTEND"]["RedisMsg"];
        $data=array(
            "type"=>"group_text",
            "content"=>"消息",
            "group_id"=>1,
            "uid"=>2,
            "msg_group_id"=>1
        );
        
        $redis->pushGroupFlockMsg($data);
        
        $uid_str_table=$GLOBALS["config"]["REDIS_TABLE"]["group:uid_str:"]["name"];
        $redis->_redisRun->set($uid_str_table."1","1,2,");
        $name= $GLOBALS["config"]["REDIS_TABLE"]["msg_group_id"]["name"];
        //群组    表
        
        while (true){
            $val=$redis->_redisRun->lpop($name);
            if($val==false)
                break;
            //出列操作
            $map    =json_decode($val,true);
            $uid_str_list=explode(",",$redis->_redisRun->get($uid_str_table.$map["group_id"]),-1);
            foreach ($uid_str_list as $v){//数据添加
                if ($v!=$map["uid"]){
                    $redis->informIncr(array(
                        "uid"       =>$map["uid"],
                        "id"        =>$map["group_id"],
                        "number"    =>1,
                        "msg_type"  =>1,
                        "content"   =>$map["content"]
                    ));
                }
            }
        }
        
        
        $list=$redis->getInformList(2);
        dump($list);
        
        /* $data=array(
            "uid"=>1,
            "msg_type"=>0,
            "id"=>1,
            "number"=>2,
            "content"=>"内容".time()
        );
        $redis->informIncr($data);
         */
        
        /* $arr=$redis->getInformList(2);
        dump($arr); */
        
    }
}
