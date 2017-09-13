<?php


namespace app\my\controller;


use app\common\model\User;
use think\Controller;
use think\Validate;

class Auth extends Controller {
    public function login() {
        return $this->fetch();
    }

    public function check() {
        if(!Validate::make([
            "username" => "require|alphaDash",
            "password" => "require"
        ])->check($_POST)) {
            $this->assign("message", "Please give the complete login information! ");
            return $this->fetch("common@common/error");
        }
        $u = $_POST["username"];
        $p = sha1($_POST["password"]);
        $m = User::get(["username" => $u, "password" => $p]);
        if(!$m) {
            $this->assign("message", "Invalid login information! <br />If you are first time using this system, please create an account by typing <code>/shop</code> in game! ");
            return $this->fetch("common@common/error");
        }

        session("user_id", $m->id);
        session("user_name", $m->username);

        $this->redirect("my/index/index");
        return;
    }

    public function logout() {
        session("user_id", null);
        session("user_name", null);
        $this->redirect("index/index/index");
    }
}