<?php


namespace app\admin\controller;


use app\common\model\User;

class Players extends BaseAdminController {
    public function index() {
        return $this->fetch();
    }

    public function detail($userId) {
        $userId = intval($userId);

        $user = User::get($userId);
        if(!$user) {
            $this->assign("message", "can not find that player! ");
            return $this->fetch("common/error");
        }

        $this->assign("user", $user);
        return $this->fetch();
    }

    public function dialogAddRole($userId) {
        $userId = intval($userId);
        $user = User::get($userId);
        if(!$user) {
            $this->assign("message", "can not find that player! ");
            return $this->fetch("common/error");
        }
        $this->assign("user", $user);
        $this->assign("server_time", date("Y-m-d H:i:s"));
        return $this->fetch("dialogAddRole");
    }
}