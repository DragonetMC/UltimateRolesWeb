<?php


namespace app\admin\controller;


use think\Controller;
use think\Validate;

class Auth extends Controller {
    public function login(){
        if(!Validate::make(["password" => "require"])->check($_POST)) {
            return $this->fetch();
        }

        $p = sha1($_POST["password"]);
        if(config("admin.password") === $p) {
            session("admin", true);
            $this->redirect("admin/index/index");
            return "login success";
        } else {
            return "wrong password! ";
        }
    }

    public function logout(){
        session("admin", null);
        $this->redirect("admin/auth/login");
    }
}