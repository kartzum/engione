<?php
require_once "lcore_i.php";

define("LCORE_DOT", ".");
define("LCORE_DOT2", "..");
define("LCORE_SLASH", "/");
define("LCORE_PHP", ".php");
define("LCORE__", "_");
define("LCORE_AMPERSAND", "&");
define("LCORE_QUESTION", "?");
define("LCORE_EQUAL", "=");
define("LCORE_XML", ".xml");

define("LCORE_START_CONFIGURATION", "start_configuration");
define("LCORE_START_ROOT_PATH", "start_root_path");
define("LCORE_START_PATH", "start_path");
define("LCORE_DATA", "lcore_data");
define("LCORE_DEFAULT_PLUGIN", "default_plugin");
define("LCORE_DEFAULT_THEME", "default_theme");

define("LCORE_PLUGINS", "plugins");
define("LCORE_PLUGINS_PATH", "plugins");
define("LCORE_PLUGINS_PATH_TEMPLATES", "templates");
define("LCORE_PLUGINS_TEMPLATES", "plugins_templates");
define("LCORE_PLUGINS_START_DATA", "plugins_start_data");
define("LCORE_PLUGINS_PATH_THEMES", "themes");
define("LCORE_PLUGINS_PATH_RESOURCES", "resources");
define("LCORE_PLUGINS_RESOURCES", "plugins_resources");

define("LCORE_DESCRIPTOR_KEY", "descriptor_key");
define("LCORE_DESCRIPTOR_PATH", "descriptor_path");

define("LCORE_PLUGIN_KEY", "plugin_key");
define("LCORE_PLUGIN_PATH", "plugin_path");
define("LCORE_PLUGIN_FULL_PATH", "plugin_full_path");
define("LCORE_PLUGIN_FUNCTION_START", "start");
define("LCORE_PLUGIN_FUNCTION_EXECUTE", "execute");
define("LCORE_PLUGIN_FUNCTION_RESET", "reset");
define("LCORE_ENTITY_KEY", "e");
define("LCORE_PLUGIN_RESULT_TEXT", "text");
define("LCORE_LOCALIZATION_KEY", "l");
define("LCORE_AREA_KEY", "a");

define("LCORE_COMMAND_KEY", "u");
define("LCORE_COMMAND_RESET_KEY", "r");

define("LCORE_MAIN_PARAMETERS_CALLBACK", "callback");
define("LCORE_MAIN_PARAMETERS_COMMON", "common");
define("LCORE_MAIN_PARAMETERS_COMMON_DATA", "common_data");
define("LCORE_MAIN_PARAMETERS_IN", "in");
define("LCORE_MAIN_PARAMETERS_SESSION", "session");
define("LCORE_MAIN_PARAMETERS_THEME", "theme");
define("LCORE_MAIN_PARAMETERS_THEMES", "themes");

define("LCORE_RESOURCES_KEY", "key");

/* lcore. Common Functions. Start. */

function lcore_get_query_parameter($key) {
	if(isset($_GET[$key])) {
		return $_GET[$key];
	}
	return null;
}

function lcore_go() {	
	if(!lcore_is_start()) {
		lcore_start();		
	} else {
		lcore_execute();
	}
}

function lcore_start() {
	$_SESSION[LCORE_DATA] = array(LCORE_PLUGINS => array());
	$_SESSION[LCORE_DATA][LCORE_PLUGINS_TEMPLATES] = array();
	lcore_plugins_load();
	lcore_plugins_include();
	$_SESSION[LCORE_DATA][LCORE_PLUGINS_START_DATA] = 
		lcore_plugins_call_function(LCORE_PLUGIN_FUNCTION_START);			
}

function lcore_execute() {	
	lcore_plugins_include();
	$comman_key = lcore_get_query_parameter(LCORE_COMMAND_KEY);
	if($comman_key !== null && $comman_key === LCORE_COMMAND_RESET_KEY) {
		lcore_reset();
		lcore_start();	
	}
	lcore_dispatch();
}

function lcore_reset() {	
	lcore_plugins_call_function(LCORE_PLUGIN_FUNCTION_RESET);
	unset($_SESSION[LCORE_DATA]);
}

function lcore_is_start() {	
	return !empty($_SESSION[LCORE_DATA]);
}

/* lcore. Common Functions. Finish. */

/* lcore. Define Functions. Start. */

function lcore_start_root_path() {
	return $_SESSION[LCORE_START_CONFIGURATION][LCORE_START_ROOT_PATH];
}

function lcore_start_path() {
	return $_SESSION[LCORE_START_CONFIGURATION][LCORE_START_PATH];
}

function lcore_default_plugin() {
	return $_SESSION[LCORE_START_CONFIGURATION][LCORE_DEFAULT_PLUGIN];
}

/* lcore. Define Functions. Finish. */

/* lcore. Path Functions. Start. */

function lcore_get_root_path($path) {
	return lcore_start_root_path().$path;
}

function lcore_get_path($path) {
	return lcore_start_path().$path;
}

/* lcore. Path Functions. Finish. */

/* lcore. Plugins. Start. */

function lcore_plugins_load() {
	lcore_plugins_register(lcore_get_root_path(LCORE_PLUGINS_PATH));
	lcore_plugins_register(lcore_get_path(LCORE_PLUGINS_PATH));
}

function lcore_plugins_include() {
	foreach($_SESSION[LCORE_DATA][LCORE_PLUGINS] as $k => $v) {
		require_once $v[LCORE_PLUGIN_FULL_PATH];
	}
}

function lcore_plugins_register($path) {
	$descriptors = lcore_get_descriptors($path);
	foreach($descriptors as $k => $v) {		
		if(!lcore_plugin_is_register($k)) {
			lcore_plugin_register($v);						
		}
	}
}

function lcore_plugin_is_register($key) {
	return in_array($key, $_SESSION[LCORE_DATA][LCORE_PLUGINS]);
}

function lcore_plugin_register($descriptor) {	
	$holder = array();
	$holder[LCORE_PLUGIN_KEY] = $descriptor[LCORE_DESCRIPTOR_KEY];
	$holder[LCORE_PLUGIN_PATH] = $descriptor[LCORE_DESCRIPTOR_PATH];
	$holder[LCORE_PLUGIN_FULL_PATH] = 
		$holder[LCORE_PLUGIN_PATH].LCORE_SLASH.$holder[LCORE_PLUGIN_KEY].LCORE_PHP; 

	lcore_load_descriptors($descriptor, LCORE_PLUGINS_PATH_TEMPLATES, LCORE_PLUGINS_TEMPLATES);
	lcore_load_descriptors($descriptor, LCORE_PLUGINS_PATH_RESOURCES, LCORE_PLUGINS_RESOURCES);
		 	
	$_SESSION[LCORE_DATA][LCORE_PLUGINS][$descriptor[LCORE_DESCRIPTOR_KEY]] = $holder;
}

function lcore_get_descriptors($path) {
	$result = array();
	if(file_exists($path) === false) {
		return $result;
	}
	$dir = dir($path);	
	while (false !== ($entry = $dir->read())) {
		if($entry !== LCORE_DOT && $entry !== LCORE_DOT2) {			
			$result[$entry] = array();
			$result[$entry][LCORE_DESCRIPTOR_KEY] = $entry;			
			$result[$entry][LCORE_DESCRIPTOR_PATH] = $path.LCORE_SLASH.$entry;						
		}
	}
	return $result;
}

function lcore_plugin_call_function($plugin_key, $function_name, &$parameters=array()) {
	return lcore_call_function($plugin_key.LCORE__.$function_name, $parameters);
}

function lcore_plugins_call_function($function_name, &$parameters=array()) {
	$result = array();
	foreach($_SESSION[LCORE_DATA][LCORE_PLUGINS] as $k => $v) {
		$result[$v[LCORE_PLUGIN_KEY]] = 
			lcore_plugin_call_function($v[LCORE_PLUGIN_KEY], $function_name, $parameters);			
	}
	return $result;
}

function lcore_load_descriptors($descriptor, $path_pattern, $data_key) {
	$path = $descriptor[LCORE_DESCRIPTOR_PATH].LCORE_SLASH.$path_pattern;
	$descriptors = lcore_get_descriptors($path); 
	if(count($descriptors) <= 0) {
		return;
	}
	$_SESSION[LCORE_DATA][$data_key][$descriptor[LCORE_DESCRIPTOR_KEY]] = array();
	foreach ($descriptors as $t_k => $t_v) {
		$_SESSION[LCORE_DATA][$data_key][$descriptor[LCORE_DESCRIPTOR_KEY]][$t_k] = 
			$t_v[LCORE_DESCRIPTOR_PATH];	
	}		 	
}

/* lcore. Plugins. Finish. */

/* lcore. Dispatch. Start. */

function lcore_dispatch() {
	$e = lcore_get_query_parameter(LCORE_ENTITY_KEY);
	if($e === null) {
		$e = lcore_default_plugin();
	}	
	$session_data = array(
		LCORE_MAIN_PARAMETERS_THEME => lcore_theme(),
		LCORE_MAIN_PARAMETERS_THEMES => LCORE_PLUGINS_PATH_THEMES
	);
	$call_parameters = array(
		LCORE_MAIN_PARAMETERS_COMMON => array(
			LCORE_MAIN_PARAMETERS_COMMON_DATA => $_SESSION[LCORE_DATA],
			LCORE_MAIN_PARAMETERS_SESSION => $session_data			
			),
		LCORE_MAIN_PARAMETERS_IN => array(),
		LCORE_MAIN_PARAMETERS_CALLBACK => array());
	$result = lcore_d($e.LCORE__.LCORE_PLUGIN_FUNCTION_EXECUTE, $call_parameters);
	foreach ($result as $r_k => $r_v) {
		foreach ($r_v as $k => $v) {
			if($k === LCORE_PLUGIN_RESULT_TEXT) {
				echo $v;
			}
		}
	}	
}

/* lcore. Dispatch. Finish. */

/* lcore. Session Functions. Start. */

function lcore_theme() {
	return $_SESSION[LCORE_START_CONFIGURATION][LCORE_DEFAULT_THEME];
}

function lcore_locale() {
	$v = lcore_get_query_parameter(LCORE_LOCALIZATION_KEY);
	if($v === null) {
		$v = "";
	}	
	return $v;
}

/* lcore. Session Functions. Finish. */

/* lcore. Resources Functions. Start. */

function lcore_r(&$parameters=array()) {
	$key = $parameters[0]; 
	$default = $parameters[1];	
	$start_set = $_SESSION[LCORE_DATA][LCORE_PLUGINS_RESOURCES];	
	foreach($start_set as $k_t => $k_v) {
		$result = lcore_get_resource_by_set($k_v, lcore_locale(), $key);
		if($result !== null) {
			return $result;
		} else {
			$result = lcore_get_resource_by_set($k_v, "", $key);
			if($result !== null) {
				return $result;
			}
		}
	}
	return $default;
}

function lcore_get_resource_by_set($set, $l, $key) {	
	foreach($set as $key_inner => $value_inner) {
		$resource_file = $value_inner;
		if($l !== "") {
			$resource_file = str_replace(LCORE_XML, "", $value_inner);
			$resource_file = $resource_file.LCORE__.$l.LCORE_XML;
		}		
		$result = lcore_get_resource($resource_file, $key);
		if($result !== null) {
			return $result;
		}
	}
	return null;
}

function lcore_get_resource($path, $key) {	
	if(!file_exists($path)) {
		continue;
	}
	$xml = simplexml_load_file($path);
	foreach($xml as $child)
	{
		foreach($child->attributes() as $ak => $av) {
			if($ak === LCORE_RESOURCES_KEY && ((string)$av) === $key) {
				return (string)$child;
			}
		}
	}
	return null;
}

/* lcore. Resources Functions. Finish. */

/* lcore. Links Functions. Start. */

function lcore_l(&$parameters=array()) {
	$r = $parameters[0]; /* Start Resource. */
	$e = $parameters[1]; /* Entity - Plugin. */
	$l = $parameters[2]; /* Localization. */
	$a = $parameters[3]; /* Area. */
	$t = $parameters[4]; /* Tail. */
	$start = LCORE_AMPERSAND;
	if(strpos($r, LCORE_QUESTION) === false) {
		$start = LCORE_QUESTION;
	}	
	return $r.$start.LCORE_ENTITY_KEY.LCORE_EQUAL.$e.
		LCORE_AMPERSAND.LCORE_LOCALIZATION_KEY.LCORE_EQUAL.$l.
		LCORE_AMPERSAND.LCORE_AREA_KEY.LCORE_EQUAL.$a.$t;	
}

/* lcore. Links Functions. Finish. */