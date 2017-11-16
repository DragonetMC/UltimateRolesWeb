<?php


namespace app\server\controller;


use app\common\model\PerkInstance;
use app\common\model\ShopCategory;
use app\common\model\ShopItem;
use app\common\model\User;
use app\common\PerkTool;
use think\Validate;

class Shop extends ServerApi {

    public function instances() {
        if(!Validate::make([
            "uuid" => "require|alphaDash"
        ])->check($_GET)) {
            return $this->json([], "error", lang("invalid_data"));
        }
        $user = User::get(["uuid" => $_GET["uuid"]]);
        if($user === null) return null;
        $instances = $user->perks;
        $data = [];
        foreach($instances as $key => $i) {
            if ($i->expired) {
                $i->delete();
            } else {
                $data[] = $i->toArray();
            }
        }
        return $this->json(["data" => $data]);
    }

    public function categories() {
        $data = [];
        foreach(ShopCategory::all() as $c) {
            $data[] = [
                "id" => $c->id,
                "name" => $c->name
            ];
        }
        return $this->json(["data" => $data]);
    }

    public function all($categoryId = null) {
        $data = [];
        $items = $categoryId === null ? ShopItem::all() : ShopItem::all(["categoryId" => intval($categoryId)]);
        foreach($items as $item) {
            $item_data = [
                "id" => $item->id,
                "name" => $item->name,
                "description" => $item->description,
                "price" => $item->price
            ];
            $data[] = $item_data;
        }
        return $this->json(["data" => $data]);
    }

    public function renewableShopItems() {
        if(!Validate::make([
            "uuid" => "require|alphaDash",
            "instanceId" => "require|number"
        ])->check($_GET)) {
            return $this->json([], "error", lang("invalid_data"));
        }
        $instance = PerkInstance::get(intval($_GET["instanceId"]));
        if($instance === null) $this->json([], "error", lang("invalid_data"));
        $data = [];
        $items = ShopItem::all(["perkId" => $instance->perkId]);
        foreach($items as $item) {
            $item_data = [
                "id" => $item->id,
                "name" => $item->name,
                "description" => $item->description,
                "price" => $item->price
            ];
            $data[] = $item_data;
        }
        return $this->json(["data" => $data]);
    }

    public function purchase() {
        if (!Validate::make([
            "uuid" => "require|alphaDash",
            "itemId" => "require|number",
        ])->check($_GET)
        ) {
            return $this->json([], "error", lang("invalid_data"));
        }
        $uuid = $_GET["uuid"];
        $itemId = intval($_GET["itemId"]);
        $data = PerkTool::purchasePerk($uuid, $itemId);
        if (!is_array($data) and !is_string($data)) {
            return $this->json([], "error", $data);
        }

        return $this->json(is_array($data) ? ["endTime" => $data[1]->endTime] : [], "success", is_array($data) ? $data[0] : $data);
    }

}