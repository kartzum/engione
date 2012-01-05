<?php

/**
 * Key of module.
 */
define("CORE_MODULE_KEY", "core");
/**
 * Version of module.
 */
define("CORE_MODULE_VERSION", "next");
/**
 * Name of module.
 */
define("CORE_MODULE_NAME", "Core");
/**
 * Description of module.
 */
define("CORE_MODULE_DESCRIPTION", "Core - contains modules and sites execution functions.");
/**
 * Url of module.
 */
define("CORE_MODULE_URL", "");

/*
 * Start - Constants.
 */

/**
 * Key of array that contains information about all modules.
 */
define("CORE_MODULES_KEYS", "modules_keys");
/**
 * Key of system modules path.
 */
define("CORE_MODULES_SYSTEM_PATH", "modules/system/");
/**
 * Key of custom modules path.
 */
define("CORE_MODULES_CUSTOM_PATH", "modules/custom/");
/**
 * Just "/".
 */
define("CORE_SLASH", "/");
/**
 * Just ".php".
 */
define("CORE_PHP_EXT", ".php");
/**
 * Just ".xml".
 */
define("CORE_XML_EXT", ".xml");
/**
 * Just "key". This one uses for access to resources values.
 */
define("CORE_KEY", "key");
/**
 * Key of array that contains initial information for start all modules.
 */
define("CORE_MODULES_START_PARAMS", "modules_start_params");
/**
 * Key of array that contains start information from all modules.
 */
define("CORE_MODULES_RESULT_START_PARAMS", "modules_result_start_params");
/**
 * Just "result".
 */
define("CORE_RESULT", "result");
/**
 * Just "params".
 */
define("CORE_PARAMS", "params");
/**
 * Key of array that contains codes of modules for execution in one request.
 */
define("CORE_MODULES_CODE", "modules");
/**
 * Key of descriptor for execute module.
 */
define("CORE_MODULES_DESCRIPTORS_CODE", "modules_descriptors");
/**
 * Key of array that contains codes of renderers.
 */
define("CORE_RENDERERS_CODE", "renderers");
/**
 * Key of descriptor one render.
 */
define("CORE_RENDERERS_DESCRIPTORS_CODE", "renderers_descriptors");
/**
 * Key that contains path to resources.
 */
define("CORE_COMMON_RESOURCES", "resources/resources");
/**
 * Key of array that contains all common resources.
 */
define("CORE_COMMON_RESOURCES_KEY", "common_resources");

/**
 * Key of command to Core.
 */
define("CORE_COMMAND_KEY", "u");
/**
 * Key of "reset" command of Core.
 */
define("CORE_COMMAND_RESET_KEY", "r");

/**
 * Key of module key.
 */
define("CORE_MODULE_PARAM_KEY_KEY", "module_key");
/**
 * Key of module path.
 */
define("CORE_MODULE_PARAM_PATH_KEY", "module_path");
/**
 * Name of "start" function of module.
 */
define("CORE_MODULE_START_KEY", "start");
/**
 * Name of "execute" function of module.
 */
define("CORE_MODULE_EXECUTE_KEY", "execute");

/**
 * Contains path to sites.
 */
define("CORE_SITES_PATH", "sites/");
/**
 * Contains path to "start point" of sites.
 */
define("CORE_SITES_START_PATH", "sites/start.php");
/**
 * Name of function that returns code of site to start.
 */
define("CORE_SITES_START_EXECUTE", "start_execute");
/**
 * Name of "prepare" function of site.
 */
define("CORE_SITES_PREPARE", "prepare");

/**
 * Site key in request.
 */
define("CORE_REQUEST_SITE_KEY", "s");
/**
 * Page key in request.
 */
define("CORE_REQUEST_PAGE_KEY", "p");
/**
 * Lang key in request.
 */
define("CORE_REQUEST_LANG_KEY", "l");

/**
 * Key of module descriptor.
 */
define("CORE_MODULE_DESCRIPTOR", "module_descriptor");
/**
 * Key of module key.
 */
define("CORE_MODULE_DESCRIPTOR_KEY", "module_key");
/**
 * Key of module verstion.
 */
define("CORE_MODULE_DESCRIPTOR_VERSION", "module_version");
/**
 * Key of module name.
 */
define("CORE_MODULE_DESCRIPTOR_NAME", "module_name");
/**
 * Key of module description.
 */
define("CORE_MODULE_DESCRIPTOR_DESCRIPTION", "module_description");
/**
 * Key of module url.
 */
define("CORE_MODULE_DESCRIPTOR_URL", "module_url");

/*
 * Finish - Constants.
 */

/*
 * Start - Global Core Functions.
 */

/**
 * Executes function.
 * @param string $function_name name of function.
 * @return object result.
 */
function core_call_func($function_name) {
	return call_user_func($function_name);
}

/**
 * Executes functions with params.
 * @param string $function_name name of function.
 * @param array $params params.
 * @return object result.
 */
function core_call_func_with_params($function_name, &$params=array()) {
	return call_user_func($function_name, $params);
}

/**
 * Executes function of module ("module key"_"function name").
 * @param string $function_name part of name of function.
 * @param string $module_key key of module.
 * @return object result.
 */
function core_call_func_from_module($function_name, $module_key) {
	return call_user_func($module_key."_".$function_name);
}

/**
 * Executes function of module with params ("module key"_"function name").
 * @param string $function_name part of name of function.
 * @param string $module_key key of module.
 * @param array $params params.
 * @return object result.
 */
function core_call_func_from_module_with_params($function_name, $module_key, &$params=array()) {
	return call_user_func($module_key."_".$function_name, $params);
}

/**
 * Returns global web path.
 * @param string $path inner path. 
 * @return string global path.
 */
function core_get_path($path) {
	return $_SESSION[START_PATH].$path;
}

/*
 * Finish - Global Core Functions.
 */

/*
 * Start - Core API Functions.
 */

/**
 * Runs Core.
 */
function core_go() {
	if(!core_is_start()) {
		core_start();
	}
	core_execute();
}

/**
 * Executes all modules per session.
 * @param array $params params.
 */
function core_start($params=array()) {
	$_SESSION[CORE_MODULES_KEYS] = array();
	core_init_modules(CORE_MODULES_SYSTEM_PATH);
	core_init_modules(CORE_MODULES_CUSTOM_PATH);
	$start_params = array();
	$result_start_params = core_call_modules(CORE_MODULE_START_KEY, $start_params);
	$_SESSION[CORE_MODULES_START_PARAMS] = $start_params;
	$_SESSION[CORE_MODULES_RESULT_START_PARAMS] = $result_start_params;
	return array(CORE_MODULE_KEY => array());
}

/**
 * Calculates data per one request.
 * @param array $params params.
 * @return array of results.
 */
function core_execute($params=array()) {	
	if(isset($_GET[CORE_COMMAND_KEY]) && 
		$_GET[CORE_COMMAND_KEY] === CORE_COMMAND_RESET_KEY) {
		core_reset();
		core_start();	
	}
	
	core_include_modules();
	$result = core_start_sites();
	$data = $result[1];
	core_render($data);	
	return array(CORE_MODULE_KEY => array(CORE_RESULT => $result));
}

/*
 * Finish - Core API Functions.
 */

/*
 * Start - Core API Extended Functions.
 */

/**
 * Returns current lang code.
 * @return string lang code.
 */
function core_get_current_lang_code() {
	$v = core_get_param_from_get(CORE_REQUEST_LANG_KEY);
	if(empty($v)) {
		return "";
	}
	return $v;
}

/**
 * Returns array of all common resources.
 * @return array of all common resources.
 */
function core_get_common_resources() {
	return $_SESSION[CORE_COMMON_RESOURCES_KEY];
}

/**
 * Registers common resources.
 * @param string $k key of resourses.
 * @param string $path path of resources.
 */
function core_register_common_resources($k, $path) {
	if(empty($_SESSION[CORE_COMMON_RESOURCES_KEY])) {
		$a = array();
		$a[$k] = $path;
		$_SESSION[CORE_COMMON_RESOURCES_KEY] = $a;
	} else {
		$_SESSION[CORE_COMMON_RESOURCES_KEY][$k] = $path;
	}
}

/**
 * Returns path of common resources of module.
 * @param string $p path of module.
 * @param string $k key of module.
 * @return string path.
 */
function core_get_module_common_resources_path($p, $k) {
	$p = core_get_path($p.$k.CORE_SLASH.CORE_COMMON_RESOURCES.core_get_current_lang_code().CORE_XML_EXT);
	if(!file_exists($p)) {
		$p = core_get_path($p.$k.CORE_SLASH.CORE_COMMON_RESOURCES.CORE_XML_EXT);
	}
	return $p;
}

/**
 * Returns path of common resources of site.
 * @param string $k key of site.
 * @return string path.
 */
function core_get_site_common_resources_path($k) {
	$p = core_get_path(CORE_SITES_PATH.$k.CORE_SLASH.CORE_COMMON_RESOURCES.core_get_current_lang_code().CORE_XML_EXT);
	if(!file_exists($p)) {
		$p = core_get_path(CORE_SITES_PATH.$k.CORE_SLASH.CORE_COMMON_RESOURCES.CORE_XML_EXT);
	}
	return $p;
}

/**
 * Returns value of resources by key.
 * @param string $key key.
 * @param string $default default value if resources are not exists.
 * @return string value.
 */
function core_get_resources($key, $default) {
	$a = core_get_common_resources();
	foreach($a as $k => $v) {
		if(!file_exists($v)) {
			continue;
		}
		$xml = simplexml_load_file($v);
		foreach($xml->children() as $child)
		{
			foreach($child->attributes() as $ak => $av) {
				if($ak === CORE_KEY && ((string)$av) === $key) {
					return (string)$child;
				}
			}
		}
	}
	return $default;
}

/**
 * Returns value from request by key.
 * @param string $key key.
 * @return string value.
 */
function core_get_param_from_get($key) {
	if(isset($_GET[$key])) {
		return $_GET[$key];
	}
	return null;
}

/**
 * Returns site value from request.
 * @param string $default default value.
 * @return string site.
 */
function core_get_site_key($default="") {
	$v = core_get_param_from_get(CORE_REQUEST_SITE_KEY);
	if(empty($v)) {
		return $default;
	}
	return $v;
}

/**
 * Returns page value from request.
 * @param string $default default value.
 * @return string page.
 */
function core_get_page_key($default="") {
	$v = core_get_param_from_get(CORE_REQUEST_PAGE_KEY);
	if(empty($v)) {
		return $default;
	}
	return $v;
}

/**
 * Adds page to request.
 * @param string $page page.
 * @return string request.
 */
function core_add_page_to_query_string($page) {
	$result = $_SERVER["REQUEST_URI"];
	$s = parse_url($result);
	if(!empty($s["query"])) {
		$qa = array();
		parse_str($s["query"], $qa);
		if(!empty($qa[CORE_REQUEST_PAGE_KEY])) {
			$pv = $qa[CORE_REQUEST_PAGE_KEY];
			$result = str_replace(CORE_REQUEST_PAGE_KEY."=".$pv, CORE_REQUEST_PAGE_KEY."=".$page, $result);
			return $result;
		}
	}
	if(strpos($result, CORE_REQUEST_PAGE_KEY."=") !== false) {
		return $result;
	}
	if(strpos($result, "?") !== false) {
		$result .= "&".CORE_REQUEST_PAGE_KEY."=".$page;
	} else {
		$result .= "?".CORE_REQUEST_PAGE_KEY."=".$page;
	}
	return $result;
}

/*
 * Finish - Core API Extended Functions.
 */

/*
 * Start - Core Private Functions.
 */

/**
 * Resets all values from session.
 */
function core_reset() {
	unset($_SESSION[CORE_MODULES_KEYS]);
	unset($_SESSION[CORE_MODULES_START_PARAMS]);
	unset($_SESSION[CORE_MODULES_RESULT_START_PARAMS]);
	unset($_SESSION[CORE_COMMON_RESOURCES_KEY]);
}

/**
 * Returns true if Core is started.
 * @return boolean true if Core is started.
 */
function core_is_start() {
	return !empty($_SESSION[CORE_MODULES_KEYS]);
}

/**
 * Runs initialization for all modules. Also registers common resources.
 * @param string $path path to modules.
 */
function core_init_modules($path) {
	$d = dir(core_get_path($path));
	while (false !== ($entry = $d->read())) {
		if(strpos($entry, ".") === false && strpos($entry, CORE_MODULE_KEY) === false) {
			$p = core_get_module_path($path, $entry);
			if(!core_module_is_register($entry)) {
				require_once $p;
				core_module_register($entry, $p);
				core_register_common_resources($entry, core_get_module_common_resources_path($path, $entry));
			}
		}
	}
	$d->close();
}

/**
 * Calculates and returns module path.
 * @param string $p  start path.
 * @param string $k key of module.
 * @return string full path. 
 */
function core_get_module_path($p, $k) {
	return core_get_path($p.$k.CORE_SLASH.$k.CORE_PHP_EXT);
}

/**
 * Returns true if module is registred.
 * @param string $key key of module.
 * @return boolean true if module is registred.
 */
function core_module_is_register($key) {
	return in_array($key, $_SESSION[CORE_MODULES_KEYS]);
}

/**
 * Registers modules.
 * @param string $key key of module.
 * @param string $path path of module.
 */
function core_module_register($key, $path) {
	$ma = array();
	$ma[CORE_MODULE_PARAM_KEY_KEY] = $key;
	$ma[CORE_MODULE_PARAM_PATH_KEY] = $path;
	array_push($_SESSION[CORE_MODULES_KEYS], $ma);
}

/**
 * Returns infomration about modules by keys.
 * @return array infomration about modules by keys.
 */
function core_get_modules_keys() {
	return $_SESSION[CORE_MODULES_KEYS];
}

/**
 * Includes all modules.
 */
function core_include_modules() {
	$a = core_get_modules_keys();
	for($i = 0; $i < count($a); $i++) {
		$d = $a[$i];
		$path = $d[CORE_MODULE_PARAM_PATH_KEY];
		require_once $path;
	}
}

/**
 * Calls function in all modules.
 * @param string $method function name.
 * @param string $params params.
 * @return array result.
 */
function core_call_modules($method, &$params) {
	$a = core_get_modules_keys();
	$result = array();
	for($i = 0; $i < count($a); $i++) {
		$d = $a[$i];
		$path = $d[CORE_MODULE_PARAM_PATH_KEY];
		require_once $path;
		$key = $d[CORE_MODULE_PARAM_KEY_KEY];
		$result[$i] = core_call_func_from_module_with_params($method, $key, $params);
	}
	return $result;
}

/**
 * Executes sites.
 * @return array information about sites and modules.
 */
function core_start_sites() {
	$params = array(CORE_PARAMS => array());
	$data_result = array();
	$r_result = array();
	$start_path = core_get_path(CORE_SITES_START_PATH);
	require_once $start_path;
	$start = core_call_func(CORE_SITES_START_EXECUTE);
	require_once core_get_path(CORE_SITES_PATH.$start.CORE_SLASH.$start.CORE_PHP_EXT);
	core_register_common_resources($start, core_get_site_common_resources_path($start));
	$m = core_call_func($start."_".CORE_SITES_PREPARE);
	$index = 0;
	$r_index = 0;
	foreach($m as $k => $v) {
		if($k === CORE_MODULES_CODE) {
			foreach($v as $mdk => $mdv) {
				if($mdk === CORE_MODULES_DESCRIPTORS_CODE) {
					foreach($mdv as $desk => $desv) {
						$data_result[$index] = core_call_func_from_module_with_params(
						CORE_MODULE_EXECUTE_KEY, $desk, $params);
						$index++;
					}
				}
			}
		} elseif ($k === CORE_RENDERERS_CODE) {
			foreach($v as $mdk => $mdv) {
				if($mdk === CORE_RENDERERS_DESCRIPTORS_CODE) {
					foreach($mdv as $desk => $desv) {
						$r_params = array($data_result, $desv);
						$r_result[$r_index] = core_call_func_with_params($desk, $r_params);
						$r_index++;
					}
				}
			}
		}
	}
	return array($data_result, $r_result);
}

/**
 * Renders data.
 * @param array $data text.
 */
function core_render($data) {
	foreach($data as $d) {
		foreach ($d as $t) {
			echo $t;
		}
	}
}

/*
 * Finish - Core Private Functions.
 */

?>