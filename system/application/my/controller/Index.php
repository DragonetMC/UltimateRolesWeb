<?php


namespace app\my\controller;


class Index extends BaseMyController {
    public function index() {
        return $this->fetch();
    }
}