<?php

return [
    "ur_admin_home" => "Admin Home",
    "ur_admin_login" => "Administrator Login",
    "ur_cp" => "Control Panel",

    "logout" => "Logout Admin",

    // role creation
    "basic_info" => "Basic information. ",


    // main page
    "ur_total_players" => "Total players",
    "ur_perks" => "Perks",
    "ur_shop_items" => "Shop items",
    "ur_role_instances" => "Roles applied for players",

    // this is for JS so \n must be \\n in PHP (double conversion)
    "role_deletion_warning" =>
        ("You will DELETE role with ID [{role_id}] and \\n" .
        "ALL associated shop items and instances will be DELETED as well!! \\n\\n" .
        "CONFIRM DELETING? "),

    "role_items_desc" => "<p>Role items is a set of permissions, groups and prefixes. <br />" .
            "Type is a double char field, <code>PM</code> for permission, <code>GR</code> for group, <code>PF</code> for prefix and <code>SF</code> for suffix. <br />" .
            "<strong>Prefixes or Suffixes MUST use format: <code>priority:name</code> eg. <code>150:[Admin]</code></strong>",

    "role_item_types" => [
        "Permission Node",
        "Permission Group",
        "Player Prefix",
        "Player Suffix"
    ],
];
