<?php

/**
 * Entry point.
 */
function entry_point() {
	require_once $_SESSION[START_PATH]."modules/system/core/core.php";
	core_go();
}

?>