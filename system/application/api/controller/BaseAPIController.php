<?php


namespace app\api\controller;

use app\common\model\User;
use think\controller\Rest;

class BaseAPIController extends Rest {

    public function __construct(){
        if(!session("?user_id")) {
            json(["status" => "error", "message" => "not_logged_in"], 200)->send();
            exit();
        }
        parent::__construct();
    }

    protected function user() {
        return User::get(session("user_id"));
    }

    protected function json($data = [], $status = "success", $message = "") {
        return json(array_merge(["status" => $status, "message" => $message], $data), 200);
    }

}