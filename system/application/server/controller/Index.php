<?php


namespace app\server\controller;


use app\common\model\DefaultPerk;
use app\common\model\PerkApplication;
use app\common\model\PerkInstance;
use app\common\model\ServerDefinition;
use app\common\model\User;
use think\Validate;

class Index extends ServerApi {

    public function index() {
        if(!Validate::make([
            "username" => "require",
            "uuid" => "require",
            "server" => "require",
            "group" => "require"
        ])->check($_GET)) {
            return $this->json([], "error", "invalid_data");
        }
        $username = trim($_GET["username"]);
        $uuid = trim($_GET["uuid"]);
        $server = trim($_GET["server"]);
        $groups = explode(",", trim($_GET["group"]));

        $ret = [
            "player" => [
                "change" => "NONE"
            ]
        ];

        $player = User::get(["uuid" => $uuid]);
        if(!$player) {
            $player = User::create(["username" => $username, "uuid" => $uuid]);
            if(!$player) {
                return $this->json([], "error", "creation_failed");
            }
            $ret["player"]["change"] = "NEW";
        }
        if($player->username !== $username) {
            $ret["player"]["change"] = "USERNAME";
            $player->username = trim($username);
            $player->save();
        }
        $ret["player"]["id"] = $player->id;

        $server_def = ServerDefinition::get(["value" => $server, "group" => 0]);
        if(!$server_def) {
            return $this->json([], "error", "unknown_server_name");
        }
        $group_def = [];
        foreach($groups as $g) {
            $def = ServerDefinition::get(["value" => $g, "group" => 1]);
            if($def) $group_def[] = $def;
        }
        if(!$server_def or !$group_def) {
            return $this->json([], "error", "failed_to_find_server_or_group");
        }

        $i_limited = PerkInstance::where("userId", $player->id)->where("endTime", ">", time())->select();
        $i_permanent = PerkInstance::where("userId", $player->id)->where("endTime", "=", -1)->select();
        $i_default = DefaultPerk::all();

        $perms = [];
        foreach($i_limited as $p) {
            $valid = false;
            if(PerkApplication::get(["perkId" => $p->perk->id, "definitionId" => $server_def->id])) {
                $valid = true;
            }
            foreach($group_def as $g) {
                if(PerkApplication::get(["perkId" => $p->perk->id, "definitionId" => $g->id])) {
                   $valid = true;
                }
            }
            if(!$valid) continue;
            $r = $p->perk->toArray();
            unset($r["description"]);
            $r["type"] = "limited";
            $r["items"] = [];
            $r["endTime"] = $p->endTime;
            foreach($p->perk->items as $i) {
                $r["items"][] = $i->toArray();
            }
            $perms[] = $r;
        }
        foreach($i_permanent as $p) {
            $valid = false;
            if(PerkApplication::get(["perkId" => $p->perk->id, "definitionId" => $server_def->id])) {
                $valid = true;
            }
            foreach($group_def as $g) {
                if(PerkApplication::get(["perkId" => $p->perk->id, "definitionId" => $g->id])) {
                    $valid = true;
                }
            }
            if(!$valid) continue;
            $r = $p->perk->toArray();
            unset($r["description"]);
            $r["type"] = "permanent";
            $r["items"] = [];
            $r["endTime"] = -1;
            foreach($p->perk->items as $i) {
                $r["items"][] = $i->toArray();
            }
            $perms[] = $r;
        }
        foreach($i_default as $p) {
            $valid = false;
            if(PerkApplication::get(["perkId" => $p->perk->id, "definitionId" => $server_def->id])) {
                $valid = true;
            }
            foreach($group_def as $g) {
                if(PerkApplication::get(["perkId" => $p->perk->id, "definitionId" => $g->id])) {
                    $valid = true;
                }
            }
            if(!$valid) continue;
            $r = $p->perk->toArray();
            unset($r["description"]);
            $r["type"] = "default";
            $r["items"] = [];
            $r["endTime"] = -1;
            foreach($p->perk->items as $i) {
                $r["items"][] = $i->toArray();
            }
            $perms[] = $r;
        }
        $ret["data"] = $perms;
        return $this->json($ret);
    }
}