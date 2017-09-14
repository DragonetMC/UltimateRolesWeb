<?php


namespace app\admin\controller;


use think\Controller;

class BaseAdminController extends Controller {
    public function _initialize() {
        if(!session("?admin")) {
            $this->redirect("admin/auth/login");
            exit();
        }
        $this->assign("admin_api_key", config("security.secret_admin"));
        $this->assign("currency_unit", config("settings.currency_unit"));
    }
}