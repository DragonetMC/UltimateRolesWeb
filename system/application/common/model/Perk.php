<?php


namespace app\common\model;


use think\Model;

class Perk extends Model {
    protected $table = "perks";

    public function applications() {
        return $this->hasMany("PerkApplication", "perkId");
    }

    public function items() {
        return $this->hasMany("PerkItem", "perkId");
    }
}