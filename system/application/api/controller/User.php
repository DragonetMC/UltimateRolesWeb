<?php


namespace app\api\controller;


use app\common\model\PerkInstance;

class User extends BaseAPIController {
    public function balance(){
        return $this->json(["value" => $this->user()->balance]);
    }

    public function perks(){
        $m = $this->user();
        $perks = PerkInstance::where("userId", $m->id)->where("endTime", "<", time());
        $n = [];
        foreach($perks as $p) {
            $n[] = $p->name;
        }
        $s = join(", ", $n);
        if(trim($s) === "") $s = "(none yet)";
        return $this->json(["value" => $s]);
    }
}