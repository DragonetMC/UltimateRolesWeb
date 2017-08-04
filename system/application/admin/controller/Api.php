<?php


namespace app\admin\controller;


use app\common\model\Perk;
use app\common\model\PerkInstance;
use app\common\model\ShopItem;
use app\common\model\User;
use think\controller\Rest;

class Api extends Rest {
    public function _initialize(){
        if(!isset($_GET["key"])) {
            $k = $_GET["key"];
            if($k !== config("security.secret_admin")) {
                $this->json([], "failed", "wrong key");
                exit();
            }
        }
    }

    protected function json($data = [], $status = "success", $message = "") {
        return json(array_merge(["status" => $status, "message" => $message], $data), 200);
    }

    public function countPlayers() {
        return $this->json(["value" => User::count()]);
    }

    public function countPerks() {
        return $this->json(["value" => Perk::count()]);
    }

    public function countShopItems() {
        return $this->json(["value" => ShopItem::count()]);
    }

    public function countPerkPurchases() {
        return $this->json(["value" => PerkInstance::count()]);
    }
}