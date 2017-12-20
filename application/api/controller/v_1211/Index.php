<?php
// +----------------------------------------------------------------------
// | 版本中的控制器
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\v_1211;
use app\api\controller\Common;
//http://www.chat.com/api/v_1211.Index/index.html
class Index extends Common{
    public function index(){
        /* controller("index")->demo();
        $this->demob();
        $this->str1(); */
//      http://www.chat.com/index/v_1211.Index/index.html
     //   return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
        //$this->_model("Demo")->m1();
       // dump($this->_request);
        //测试redis
        /* $redis=$GLOBALS["config"]["EXTEND"]["Rediss"];
        $redis->strSet("a","4444");
        echo $redis->strGet("a"); */
     //   dump($redis);die;
        return $this->apiJson(array("data"=>$this->info),200,"ok");
    }
    
    public function demo(){
        
        /* $array1  = array( 0 ,  1 ,  2 );
        $array2  = array( "00" ,  "01" ,  "2" );
        $result  =  array_diff_assoc ( $array1 ,  $array2 );
        print_r ( $result ); */
        $arr=explode(",",ltrim(rtrim(",0,1,2,3,4,5,",","),","));
        $arr1=explode(",",rtrim("2,5,",","));   
        
        
        echo ",".join(",",array_diff($arr,$arr1)).",";
        
        
        $brr=array(
            array("uid"=>1),
            array("uid"=>2),
            array("uid"=>3),
        );
        dump(array_column ($brr,"uid"));
      //  dump($result);
        
    }
}
