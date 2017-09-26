<?php


namespace app\server\controller;


use app\common\model\User;
use think\Validate;

/**
 * Class Account, used for offline-mode authentication
 * @package app\server\controller
 */
class Account extends ServerApi {
    public function checkRegistered() {
        if (!Validate::make([
                "username" => "require|alphaDash|max:16",
            ])->check($_GET) or strpos($_GET["username"], "-") !== false
        ) {
            return $this->json([], "error", lang("server_invalid_username"));
        }
        $u = User::get(["username" => $_GET["username"]]);
        if(!$u) return $this->json(["registered" => false]);
        if($u->passwordSet == false) return $this->json(["registered" => false]);
        return $this->json(["registered" => true]);
    }

    public function register() {
        if (!Validate::make([
                "username" => "require|alphaDash|max:16",
                "password" => "require",
                "uuid" => "require|alphaDash"
            ])->check($_GET) or strpos($_GET["username"], "-") !== false
        ) {
            return $this->json([], "error", lang("server_reg_error_fields"));
        }
        $username = $_GET["username"];
        $password = sha1($_GET["password"]);
        $uuid = $_GET["uuid"];
        $u = User::get(["username" => $username]);
        if ($u) {
            if($u->passwordSet) {
                return $this->json([], "error", lang("server_reg_error_user_exists"));
            }
            $u->username = $username;
            $u->password = $password;
            $u->passwordSet = true;
            $u->uuid = $uuid;
            $u->save();
        } else {
            $u = User::create([
                "username" => $username,
                "password" => $password,
                "passwordSet" => true,
                "uuid" => $uuid
            ]);
        }
        if(!$u) {
            return $this->json([], "error", lang("server_reg_error_server"));
        }
        return $this->json();
    }

    public function checkLogin() {
        if (!Validate::make([
                "username" => "require|alphaDash|max:16",
                "password" => "require",
            ])->check($_GET) or strpos($_GET["username"], "-") !== false
        ) {
            return $this->json([], "error");
        }
        $username = $_GET["username"];
        $password = sha1($_GET["password"]);
        $u = User::get(["username" => $username]);
        if(!$u or !$u->passwordSet) {
            return $this->json([], "error", lang("server_login_error_user_not_found"));
        }
        if($u->password !== $password) {
            return $this->json([], "error", lang("server_login_error_password"));
        }
        return $this->json();
    }
}