<?php
namespace app\index\controller;

use app\common\model\PerkInstance;
use app\common\model\ShopItem;
use app\common\model\User;

class Index extends BaseIndexController
{
    public function index()
    {
        $this->assign("users", User::count());
        $this->assign("purchases", PerkInstance::count());
        $this->assign("featured_items", ShopItem::all(["featured" => true]));
        return $this->fetch();
    }
}
