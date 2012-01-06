<?php

/**
 * Key of module.
 */
define("UTILS_MODULE_KEY", "utils");
/**
 * Version of module.
 */
define("UTILS_MODULE_VERSION", "next");
/**
 * Name of module.
 */
define("UTILS_MODULE_NAME", "Utils");
/**
 * Description of module.
 */
define("UTILS_MODULE_DESCRIPTION", "Utils - contains some useful functions.");
/**
 * Url of module.
 */
define("UTILS_MODULE_URL", "");

/**
 * Provides initialization of module. Runs only ones per session.
 * @param array $params params.
 * @return information about module.
 */
function utils_start($params=array()) {
	return array(UTILS_MODULE_KEY => array(
			CORE_MODULE_DESCRIPTOR => array(
					CORE_MODULE_DESCRIPTOR_KEY => UTILS_MODULE_KEY,
					CORE_MODULE_DESCRIPTOR_VERSION => UTILS_MODULE_VERSION,
					CORE_MODULE_DESCRIPTOR_NAME => UTILS_MODULE_NAME,
					CORE_MODULE_DESCRIPTOR_DESCRIPTION => UTILS_MODULE_DESCRIPTION,
					CORE_MODULE_DESCRIPTOR_URL => UTILS_MODULE_URL))
			);
}

/**
 * Calculates data per one request.
 * @param array $params params
 * @return result of calulations.
 */
function utils_execute($params=array()) {
	return array(UTILS_MODULE_KEY => array());
}

?>