<?php
namespace app\index\controller;

use app\common\model\PerkInstance;
use app\common\model\User;
use think\Controller;

class Index extends BaseIndexController
{
    public function index()
    {
        $this->assign("users", User::count());
        $this->assign("purchases", PerkInstance::count());
        return $this->fetch();
    }
}
