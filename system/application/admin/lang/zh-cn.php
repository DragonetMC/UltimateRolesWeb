<?php

return [
    "ur_admin_home" => "管理页面",
    "ur_admin_login" => "管理员登录",
    "ur_cp" => "控制面板",

    "logout" => "管理员登出",

    // role creation
    "basic_info" => "基础信息",


    // main page
    "ur_total_players" => "总玩家",
    "ur_perks" => "权限组",
    "ur_shop_items" => "商店物品",
    "ur_role_instances" => "应用于玩家的角色",

    // this is for JS so \n must be \\n in PHP (double conversion)
    "role_deletion_warning" =>
        ("你将会删除ID为 [{role_id}] 的角色 \\n" .
        "所有相关的商店，实例等也会被删除！ \\n\\n" .
        "请确认删除 "),

    "role_items_desc" => "<p>角色是一个权限，权限组，称号的集合<br />" .
            "类型是一个双子符的域, <code>PM</code> 为权限, <code>GR</code> 为组, <code>PF</code> 为前缀, <code>SF</code> 为后缀 <br />" .
            "<strong>前缀后缀都需要遵守以下格式: <code>优先级:name</code> 比如 <code>150:[管理员]</code></strong>",

    "role_item_types" => [
        "权限节点",
        "权限组",
        "玩家前缀",
        "玩家后缀"
    ],
];
