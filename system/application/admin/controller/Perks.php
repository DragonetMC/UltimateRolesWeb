<?php


namespace app\admin\controller;


use app\common\model\DefaultPerk;
use app\common\model\Perk;
use app\common\model\PerkItem;
use app\common\model\ServerDefinition;
use think\Url;
use think\Validate;

class Perks extends BaseAdminController {
    public function index(){
        return $this->fetch();
    }

    public function create() {
        return $this->fetch();
    }

    public function edit($perkId) {
        $perkId = intval($perkId);
        $p = Perk::get($perkId);
        if(!$p) {
            $this->assign("message", "Can not find the perk! ");
            return $this->fetch("common/error");
        }
        $this->assign("perk", $p);
        $this->assign("item_count", count($p->items));
        return $this->fetch();
    }

    public function dialogEditItem($itemId) {
        $itemId = intval($itemId);
        $item = PerkItem::get($itemId);
        if(!$item) {
            die("Failed to find the perk item with ID [" . $itemId . "]. ");
        }
        $this->assign("item", $item);
        return $this->fetch("dialogEditItem");
    }

    public function updatePerk($perkId = -1) {
        if(!Validate::make([
            "name" => "require",
            "description" => "require"
        ])->check($_POST)) {
            $this->assign("message", "Invalid data");
            return $this->fetch("common/error");
        }
        $perkId = intval($perkId);
        if($perkId === -1) {
            $perk = new Perk();
        } else {
            $perk = Perk::get($perkId);
        }
        $perk->name = $_POST["name"];
        $perk->description = $_POST["description"];
        $perk->save();
        $this->redirect(Url::build("Perks/edit", ["perkId" => $perk->id], false, true));
    }

    public function editApplications($perkId) {
        $perkId = intval($perkId);
        $perk = Perk::get($perkId);
        if(!$perk) {
            $this->assign("message", "Failed to find perk! ");
            return $this->fetch("common/error");
        }
        $this->assign("perk", $perk);
        return $this->fetch("editApplications");
    }

    public function dialogAddApplication() {
        $this->assign("definitions", isset($_GET["group"]) ? ServerDefinition::all(["group" => 1]) : ServerDefinition::all());
        return $this->fetch("dialogAddApplication");
    }

    public function defaults() {
        return $this->fetch();
    }

    public function dialogAddDefaultPerk() {
        return $this->fetch("dialogAddDefaultPerk");
    }
}
