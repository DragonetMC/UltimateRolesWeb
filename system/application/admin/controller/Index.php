<?php


namespace app\admin\controller;


class Index extends BaseAdminController {
    public function index() {
        return $this->fetch();
    }
}