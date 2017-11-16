<?php


namespace app\common;


use app\common\model\PerkInstance;
use app\common\model\ShopItem;
use app\common\model\User;

class PerkTool {
    /**
     * @param $uuid
     * @param $itemId
     * @return [string, PerkInstance] "new" for newly bought, "renew" for renewal, "permanent" , or "balance" insufficient balance, null for error
     */
    public static function purchasePerk($uuid, $itemId) {
        $user = User::get(["uuid" => $uuid]);
        if($user === null) return null;
        $item = ShopItem::get($itemId);
        if($item === null) return null;
        if(floatval($user->balance) < floatval($item->price)) {
            return "balance";
        }
        $instances = $user->perks;
        $instance = null;
        $renewal = false;
        foreach($instances as $key => $i) {
            if($i->expired) {
                $i->delete();
                unset($instances[$key]);
            }
            if(intval($i->perkId) === intval($item->perkId)){
                if(intval($i->endTime) === -1) {
                    return "permanent";
                }
                $instance = $i;
                $renewal = true;
            }
        }
        $user->balance = floatval($user->balance) - floatval($item->price);
        if($user->save() === false) return null;
        if(!$renewal) {
            $instance = PerkInstance::create([
                "userId" => $user->id,
                "perkId" => $item->perkId,
                "endTime" => time() + $item->perk_time,
                "purchasedTime" => time()
            ]);
            if($instance === null) return null;
        } else {
            $instance->endTime += intval($item->perk_time);
            if($instance->save() === false) {
                return null;
            }
        }
        if($renewal) {
            return ["renew", $instance];
        } else {
            return ["new", $instance];
        }
    }
}