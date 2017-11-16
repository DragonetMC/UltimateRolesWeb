<?php


namespace app\common\model;


use think\Model;

class BalanceHistory extends Model {
    protected $table = "balance_history";

    protected $autoWriteTimestamp = "create";
    protected $updateTime = false;
}