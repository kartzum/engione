<?php
session_start();
define("START_PATH", "start_path");
$_SESSION[START_PATH] = "./";
require_once $_SESSION[START_PATH]."entry_point.php";
entry_point();
?>