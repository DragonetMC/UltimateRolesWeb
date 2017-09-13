<?php


namespace app\common\model;


use think\Model;

class ServerDefinition extends Model {
    protected $table = "server_definition";

    public function getDisplayAttr() {
        return $this["name"] . ($this->group == 1 ? "(group)" : "");
    }
}