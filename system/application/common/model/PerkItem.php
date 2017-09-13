<?php


namespace app\common\model;


use think\Model;

class PerkItem extends Model {
    protected $table = "perk_items";

    public function perk() {
        return $this->belongsTo("Perk", "perkId");
    }

    public function getDisplayTypeAttr() {
        switch ($this->itemType) {
            case 0:
                return "PM";
            case 1:
                return "GR";
            case 2:
                return "PF";
            default:
                return "ERROR";
        }
    }
}