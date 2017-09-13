<?php


namespace app\my\controller;


use app\common\model\ShopCategory;
use think\Controller;
use think\Url;

class BaseMyController extends Controller {
    public function _initialize() {
        $this->assign("item_categories", ShopCategory::all());

        if(!session("?user_id")) {
            $this->redirect(Url::build("auth/login", [], false, true));
            die();
        }
    }
}