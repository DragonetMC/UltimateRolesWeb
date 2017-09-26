<?php


namespace app\common\model;


use think\Model;

class User extends Model {
    protected $table = "users";

    protected $autoWriteTimestamp = true;

    public function perks() {
        return $this->hasMany("PerkInstance", "userId");
    }
}