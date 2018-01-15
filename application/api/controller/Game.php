<?php
// +----------------------------------------------------------------------
// | 游戏相关
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
// | Author: 村长 <8044023@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller;
class Game extends Common{
    /**
     * 获取游戏列表
     *   */
    public function getList(){
        $list=\think\Db::name("common_game")
            ->field("common_game_id,name,picture,url")
            ->where(array("status"=>1))
            ->order("sort DESC")
            ->paginate($this->info["limit"],true);
        return $this->apiJson(array("list"=>$list->items(),"currentPage"=>$list->currentPage()),200,"ok");
    }
}
