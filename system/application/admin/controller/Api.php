<?php


namespace app\admin\controller;


use app\common\model\DefaultPerk;
use app\common\model\Perk;
use app\common\model\PerkApplication;
use app\common\model\PerkInstance;
use app\common\model\PerkItem;
use app\common\model\ServerDefinition;
use app\common\model\ShopItem;
use app\common\model\User;
use think\controller\Rest;
use think\Validate;

class Api extends Rest {
    public function __construct(){
        if(isset($_GET["key"])) {
            $k = $_GET["key"];
            if($k !== config("security.secret_admin")) {
                $this->json([], "failed", "wrong key")->send();
                exit();
            }
        } else if(!session("?admin")) {
            $this->json([], "failed", "no key given and not logged in")->send();
            exit();
        }
        parent::__construct();
    }

    protected function json($data = [], $status = "success", $message = "") {
        return json(array_merge(["status" => $status, "message" => $message], $data), 200);
    }

    /* ==== statistics == */

    public function countPlayers() {
        return $this->json(["value" => User::count()]);
    }

    public function countPerks() {
        return $this->json(["value" => Perk::count()]);
    }

    public function countShopItems() {
        return $this->json(["value" => ShopItem::count()]);
    }

    public function countPerkPurchases() {
        return $this->json(["value" => PerkInstance::count()]);
    }

    public function userBalance($userId) {
        $userId = intval($userId);
        $user = User::get($userId);
        if(!$user) return $this->json(["value" => config("lang.player_not_found")]);
        return $this->json(["value" => ($user->balance . " " . config("settings.currency_unit"))]);
    }

    /* ==== perks ==== */
    public function perks($page = 1, $pageLimit = 20, $parseApplications = false) {
        $page = intval($page);
        if($page < 1) $page = 1;
        $perkPaginator = Perk::paginate($pageLimit, false, ["page" => $page]);
        $perks = $perkPaginator->items();
        $result = [
            "page" => $page,
            "maxPage" => $perkPaginator->lastPage()
        ];
        $perks_array = [];
        foreach($perks as $p) {
            $perk_info = $p->toArray();
            if($parseApplications !== false) {
                $applications = []; // array of string
                foreach($p->applications as $application) {
                    $applicationName = $application->definition->name;
                    if($application->definition->group) {
                        $applicationName .= "(group)";
                    }
                    $applications[] = $applicationName;
                }
                $perk_info["applications"] = $applications;
            }
            $perks_array[] = $perk_info;
        }
        $result["perks"] = $perks_array;
        return $this->json($result);
    }

    public function deletePerk($perkId) {
        $perkId = intval($perkId);
        $deleted = Perk::destroy($perkId);
        if($deleted > 0) {
            return $this->json(["deletion" => ["success", "none"]]);
        } else {
            return $this->json(["deletion" => ["failed", "can not find"]]);
        }
    }

    public function perkItems($perkId) {
        $perkId = intval($perkId);
        $perk = Perk::get($perkId);
        if(!$perk) {
            return $this->json([], "error", "can not find the perk");
        }
        $r = [];
        foreach($perk->items as $item) {
            $data = $item->toArray();
            $data["displayType"] = $item->displayType;
            $r[] = $data;
        }
        return $this->json(["items" => $r]);
    }

    public function updatePerkItem($perkId = -1, $itemId = -1) {
        $perkId = intval($perkId);
        $itemId = intval($itemId);
        if(!Validate::make([
            "itemType" => "require|number|in:0,1,2",
            "value" => "require",
        ])->check($_POST)) {
            return $this->json([], "error", "invalid data! ");
        }
        if($itemId === -1 and $perkId !== -1) {
            $perk = Perk::get($perkId);
            if(!$perk) {
                return $this->json([], "error", "can not find the perk ID #" . $perkId);
            }
            $perkItem = PerkItem::create(["itemType" => intval($_POST["itemType"]), "value" => $_POST["value"], "perkId" => $perk->id]);
        } else {
            $perkItem = PerkItem::get($itemId);
        }
        if($perkItem) {
            if($itemId !== -1) {
                $perkItem->itemType = intval($_POST["itemType"]);
                $perkItem->value = $_POST["value"];
                $perkItem->save();
            }
            return $this->json();
        } else {
            return $this->json([], "error", "failed to create/edit");
        }
    }

    public function deletePerkItem($itemId) {
        $itemId = intval($itemId);
        $perkItem = PerkItem::get($itemId);
        if(!$perkItem) {
            return $this->json([], "error", "can not find the perk item");
        }
        $perkItem->delete();
        return $this->json();
    }

    public function perkApplications($perkId) {
        $perkId = intval($perkId);
        $appls = PerkApplication::all(["perkId" => $perkId]);
        $data = [];
        foreach($appls as $a) {
            $o = $a->toArray();
            $o["display"] = $a->definition->display;
            $data[] = $o;
        }
        return $this->json(["data" => $data]);
    }

    public function addPerkApplication() {
        if(!Validate::make([
            "perkId" => "require|number",
            "definitionId" => "require|number",
        ])->check($_POST)) {
            return $this->json([], "error", "invalid data! " . print_r($_POST, true));
        }
        $perkId = intval($_POST["perkId"]);
        $defId = intval($_POST["definitionId"]);
        if(PerkApplication::get(["perkId" => $perkId, "definitionId" => $defId])) {
            return $this->json([], "error", "duplicated application!  ");
        }
        $p = Perk::get(intval($_POST["perkId"]));
        if(!$p) {
            return $this->json([], "error", config("lang.perk_not_found"));
        }
        $def = ServerDefinition::get($defId);
        if(!$def) {
            return $this->json([], "error", config("lang.perk_not_found"));
        }
        $appl = PerkApplication::create([
            "perkId" => $p->id,
            "definitionId" =>$def->id
        ]);
        if(!$appl) {
            return $this->json([], "error", "failed to save created application! ");
        }
        return $this->json();
    }

    public function removePerkApplication($applId) {
        $applId = intval($applId);
        $appl = PerkApplication::get($applId);;
        if(!$appl) {
            return $this->json([], "error", "can not find the application");
        }
        $appl->delete();
        return $this->json();
    }

    /* ==== players ==== */
    public function players($conditionUsername = null, $page = 1, $pageLimit = 20) {
        $page = intval($page);
        if ($page < 1) $page = 1;
        if($conditionUsername === null) {
            $userPaginator = User::paginate($pageLimit, false, ["page" => $page]);
        } else {
            $userPaginator = User::whereLike("username", "%$conditionUsername%")->paginate($pageLimit, false, ["page" => $page]);
        }
        $users = $userPaginator->items();
        $result = [
            "page" => $page,
            "maxPage" => $userPaginator->lastPage()
        ];
        $data = [];
        foreach($users as $u) {
            $d = $u->toArray();
            unset($d["password"]); // never give away this!!
            $data[] = $d;
        }
        $result["users"] = $data;
        return $this->json($result);
    }

    public function userPerks($userId) {
        $userId = intval($userId);
        $user = User::get($userId);
        if(!$user) return $this->json([], "error", config("lang.player_not_found"));
        $instances = $user->perks;
        $ret = [];
        foreach($instances as $i) {
            $data = $i->toArray();
            $data["expired"] = $i->expired;
            $data["perk"] = $i->perk->toArray();
            $ret[] = $data;
        }
        return $this->json(["instances" => $ret]);
    }

    public function changeRoleEndTime($instanceId, $endTime) {
        $instanceId = intval($instanceId);
        $endTime = intval($endTime) === -1 ? -1 : strtotime(trim($endTime));
        if($endTime === false){
            return $this->json([], "error", "Incorrect date time format! ");
        }
        $i = PerkInstance::get($instanceId);
        if(!$i) {
            return $this->json([], "error", "Failed to find the instance! ");
        }
        $i->endTime = $endTime;
        $i->save();
        return $this->json();
    }

    public function addRoleInstance() {
        if(!Validate::make([
            "userId" => "require|number",
            "perkId" => "require|number",
            "endTime" => "require"
        ])->check($_POST)) {
            return $this->json([], "error", config("lang.invalid_data") . print_r($_POST, true));
        }
        $endTime = intval($_POST["endTime"]) === -1 ? -1 : strtotime(trim($_POST["endTime"]));
        if($endTime === false) {
            return $this->json([], "error", config("lang.invalid_data") . print_r($_POST, true));
        }
        $userId = intval($_POST["userId"]);
        $user = User::get($userId);
        if(!$user) {
            return $this->json([], "error", config("lang.player_not_found") . print_r($_POST, true));
        }
        $perkId = intval($_POST["perkId"]);
        $perk = Perk::get($perkId);
        if(!$perk) {
            return $this->json([], "error", config("lang.perk_not_found") . print_r($_POST, true));
        }
        $instance = PerkInstance::create([
            "userId" => $user->id,
            "perkId" => $perk->id,
            "purchasedTime" => time(),
            "endTime" => $endTime
        ]);
        if($instance) {
            return $this->json();
        } else {
            return $this->json([], "error", "Failed to create! ");
        }
    }

    public function deleteRoleInstance($instanceId) {
        $instanceId = intval($instanceId);
        $i = PerkInstance::get($instanceId);
        if(!$i) {
            return $this->json([], "error", "Failed to find the instance! ");
        }
        $i->delete();
        return $this->json();
    }

    public function defaultPerks() {
        $ret = [];
        foreach(DefaultPerk::all() as $perk) {
            $data = $perk->toArray();
            $data["perk"] = $perk->perk->toArray();
            $ret[] = $data;
        }
        return $this->json(["perks" => $ret]);
    }

    public function deleteDefaultPerk($defaultPerkId) {
        $defaultPerkId = intval($defaultPerkId);
        $r = DefaultPerk::get($defaultPerkId);
        if(!$r) {
            return $this->json([], "error", "Failed to find! ");
        }
        $r->delete();
        return $this->json();
    }

    public function addDefaultPerk($perkId) {
        $perkId = intval($perkId);
        if(DefaultPerk::get(["perkId" => $perkId])) {
            return $this->json([], "error", "Duplicate role! ");
        }
        $r = Perk::get($perkId);
        if(!$r) {
            return $this->json([], "error", "Failed to find! ");
        }
        $defaultRole = DefaultPerk::create(["perkId" => $r->id]);
        if($defaultRole) {
            return $this->json();
        } else {
            return $this->json([], "error", "Failed to create! ");
        }
    }
}