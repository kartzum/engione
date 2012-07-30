<?php 
/**
 * Index.
 */

session_start();
$_SESSION["start_configuration"] = array(
	"start_root_path" => "./",
	"start_path" => "./",
	"default_plugin" => "start",
	"default_theme" => "default");
require_once $_SESSION["start_configuration"]["start_root_path"]."go.php";
go();