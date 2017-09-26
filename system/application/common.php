<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

session_start();
if(!isset($_GET["lang"])) {
    if(!isset($_SESSION["ur_admin_lang"])) {
        $_GET["lang"] = "en-us";
        $_SESSION["ur_admin_lang"] = "en-us";
    } else {
        $_GET["lang"] = $_SESSION["ur_admin_lang"];
    }
} else {
    $_SESSION["ur_admin_lang"] = $_GET["lang"];
}