<?php


namespace app\common\model;


use think\Model;

class PerkInstance extends Model {
    protected $table = "perk_instances";

    public function perk() {
        return $this->belongsTo("Perk", "perkId");
    }

    public function getExpiredAttr() {
        if(intval($this->endTime) === -1) {
            return false; // permanant
        }
        return time() > intval($this->endTime);
    }
}