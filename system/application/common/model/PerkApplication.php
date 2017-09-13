<?php


namespace app\common\model;


use think\Model;

class PerkApplication extends Model {
    protected $table = "perk_applications";

    public function definition(){
        return $this->belongsTo("ServerDefinition", "definitionId");
    }
}