<?php


namespace app\common\model;


use think\Model;

class ShopItem extends Model {
    protected $table = "shop_items";

    public function perk(){
        return $this->hasOne("Perk", "id", "perkId");
    }

}