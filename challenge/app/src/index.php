<?php
require_once("core/controllers/ControllerCreator.php");
require_once("core/controllers/ControllerDownload.php");
session_start();
include("config/conf.php");
if($is_debug)
{
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}
$page = "/";
if(isset($_GET["page"]) && !empty($_GET["page"]))
{
    if(in_array($_GET["page"],$allowed_pages)) $page = $_GET["page"];
    else $page = "404";
}
new ControllerCreator($pages[$page],$_REQUEST);