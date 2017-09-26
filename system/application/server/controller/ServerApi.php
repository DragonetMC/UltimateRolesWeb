<?php


namespace app\server\controller;


use think\controller\Rest;

class ServerApi extends Rest {
    public function __construct(){
        if(!isset($_GET["key"]) or $_GET["key"] !== config("security.secret_plugin")) {
            json(["status" => "error", "message" => "wrong_key"], 200)->send();
            exit();
        }
        parent::__construct();
    }

    protected function json($data = [], $status = "success", $message = "") {
        return json(array_merge(["status" => $status, "message" => $message], $data), 200);
    }
}