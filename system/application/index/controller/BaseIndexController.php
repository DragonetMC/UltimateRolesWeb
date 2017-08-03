<?php


namespace app\index\controller;


use app\common\model\ShopCategory;
use think\Controller;

class BaseIndexController extends Controller {
    public function _initialize() {
        $this->assign("item_categories", ShopCategory::all());
    }
}