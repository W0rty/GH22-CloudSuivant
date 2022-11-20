<?php

$pages = [
    "login" => "core/users/login.php",
    "register" => "core/users/register.php",
    "logout" => "core/users/logout.php",
    
    "/" => "core/app/index.php",
    "upload" => "core/app/upload.php",
    "download" => "core/app/download.php",
    "delete" => "core/app/delete.php",
    "myfile" => "core/app/myfile.php",
    "temp_upload" => "core/app/temp_upload.php",
    "temp_download" => "core/app/temp_download.php",

    "404" => "core/errors/404.php",
    "403" => "core/errors/403.php"];

$allowed_pages = ["login","register","logout","upload","myfile","/","404","403","temp_upload","temp_download","download","delete"];

$upload_folder = "/var/data/";

$is_debug = 0;
