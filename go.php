<?php 
function go() {
	require_once $_SESSION["start_configuration"]["start_root_path"]."system/lcore/lcore.php";
	lcore_go();
}