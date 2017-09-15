<?php


namespace app\common\model;


use think\Model;

class DefaultPerk extends Model {
    protected $table = "default_perks";

    public function perk() {
        return $this->hasOne("Perk", "id", "perkId");
    }
}