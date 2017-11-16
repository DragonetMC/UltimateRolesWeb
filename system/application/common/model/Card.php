<?php


namespace app\common\model;


use think\Model;

class Card extends Model {

    protected $table = "cards";


    public function getUsedAttr($value) {
        return intval($value) !== 0;
    }

}