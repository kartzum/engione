<?php

/**
 * Default site.
 */
define("START_START", "zero");

/**
 * Custom path provider.
 */
define("START_PATH_CUSTOM", "start_site_path.php");

/**
 * Custom path function provider.
 */
define("START_PATH_CUSTOM_FUN", "start_site_path_execute");

/**
 * Returns code of side.
 * @return string code.
 */
function start_execute() {	
	$p = core_get_sites_path(START_PATH_CUSTOM);
	if(!file_exists($p)) {
		return core_get_site_key(START_START);
	} else {
		require_once $p;
		return core_call_func(START_PATH_CUSTOM_FUN);
	}
}

?>