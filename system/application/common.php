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

// 应用公共文件
if(!isset($_GET["lang"])) {
    if(!isset($_COOKIE["ur_admin_lang"])) {
        $_GET["lang"] = "en-us";
        $_COOKIE["ur_admin_lang"] = "en-us";
    } else {
        $_GET["lang"] = $_COOKIE["ur_admin_lang"];
    }
} else {
    $_COOKIE["ur_admin_lang"] = $_GET["lang"];
}