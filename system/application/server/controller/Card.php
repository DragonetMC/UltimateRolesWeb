<?php


namespace app\server\controller;


use app\common\model\BalanceHistory;
use app\common\model\User;
use think\Validate;

class Card extends ServerApi {

    public function redeem() {
        if(!Validate::make([
            "uuid" => "require|alphaDash",
            "serial" => "require|alphaNum",
            "key" => "require|alphaNum",
        ])->check($_GET)) {
            return $this->json("error", [], lang("card_invalid_info"));
        }
        $user = User::get(["uuid" => $_GET["uuid"]]);
        if($user === null) {
            return $this->json("error", [], lang("card_invalid_info"));
        }
        $card = \app\common\model\Card::get(["serial" => $_GET["serial"], "key" => $_GET["key"]]);
        if($card->used) {
            return $this->json("error", [], lang("card_used"));
        }
        $card->used = 1;
        $card->save();
        $value = floatval($card->value);
        $user->balance += $value;
        $user->save();
        BalanceHistory::create([
            "userId" => $user->id,
            "diffValue" => $value,
            "reason" => lang("card_history")
        ]);
        return $this->json("success", ["balance" => $user->balance]);
    }

}
