<?php
/**
 * Framework functions.
 */

require_once "lcore_i.php";

/* Base. Start.*/

/**
 * Represents ".".
 */
define("LCORE_DOT", ".");

/**
 * Represents "..". 
 */
define("LCORE_DOT2", "..");

/**
 * Represents "/". 
 */
define("LCORE_SLASH", "/");

/**
 * Represents ".php". 
 */
define("LCORE_PHP", ".php");

/**
 * Represents "_". 
 */
define("LCORE__", "_");

/**
 * Represents "&". 
 */
define("LCORE_AMPERSAND", "&");

/**
 * Represents "?". 
 */
define("LCORE_QUESTION", "?");

/**
 * Represents "=". 
 */
define("LCORE_EQUAL", "=");

/**
 * Represents ".xml". 
 */
define("LCORE_XML", ".xml");

/**
 * Represents "Content-type". 
 */
define("LCORE_CONTENT_TYPE", "Content-type");

/**
 * Represents ":". 
 */
define("LCORE_COLON", ":");

/* Base. Finish.*/

/* Common. Start.*/

/**
 * Key of configuration data in session. 
 */
define("LCORE_START_CONFIGURATION", "start_configuration");

/**
 * Key of framework path. 
 */
define("LCORE_START_ROOT_PATH", "start_root_path");

/**
 * Key of application path. 
 */
define("LCORE_START_PATH", "start_path");

/**
 * Key of data of framework in session. 
 */
define("LCORE_DATA", "lcore_data");

/**
 * Key of default plugin in session. 
 */
define("LCORE_DEFAULT_PLUGIN", "default_plugin");

/**
 * Key of default theme in session. 
 */
define("LCORE_DEFAULT_THEME", "default_theme");

/**
 * Key of framework settings. 
 */
define("LCORE_DATA_SESSION_SETTINGS", "settings");

/**
 * Key of locale. 
 */
define("LCORE_DATA_SESSION_SETTINGS_LOCALE", "locale");

/* Common. Finish.*/

/* Plugins. Start.*/

/**
 * Key of plugins in session. 
 */
define("LCORE_PLUGINS", "plugins");

/**
 * Key of plugins path. 
 */
define("LCORE_PLUGINS_PATH", "plugins");

/**
 * Key of templates path. 
 */
define("LCORE_PLUGINS_PATH_TEMPLATES", "templates");

/**
 * Key of templates in session. 
 */
define("LCORE_PLUGINS_TEMPLATES", "plugins_templates");

/**
 * Key of plugins start data in session. 
 */
define("LCORE_PLUGINS_START_DATA", "plugins_start_data");

/**
 * Key of themes path. 
 */
define("LCORE_PLUGINS_PATH_THEMES", "themes");

/**
 * Key of resources path. 
 */
define("LCORE_PLUGINS_PATH_RESOURCES", "resources");

/**
 * Key of resources in session. 
 */
define("LCORE_PLUGINS_RESOURCES", "plugins_resources");

/* Plugins. Finish.*/

/* Descriptor. Start. */

/**
 * Descriptor key. 
 */
define("LCORE_DESCRIPTOR_KEY", "descriptor_key");

/**
 * Descriptor path. 
 */
define("LCORE_DESCRIPTOR_PATH", "descriptor_path");

/* Descriptor. Finish. */

/* Plugin. Start.*/

/**
 * Plugin key. 
 */
define("LCORE_PLUGIN_KEY", "plugin_key");

/**
 * Plugin path. 
 */
define("LCORE_PLUGIN_PATH", "plugin_path");

/**
 * Plugin full path. 
 */
define("LCORE_PLUGIN_FULL_PATH", "plugin_full_path");

/**
 * Plugin start function name. 
 */
define("LCORE_PLUGIN_FUNCTION_START", "start");

/**
 * Plugin execute function name. 
 */
define("LCORE_PLUGIN_FUNCTION_EXECUTE", "execute");

/**
 * Plugin reset function name. 
 */
define("LCORE_PLUGIN_FUNCTION_RESET", "reset");

/**
 * Plugin key in query. 
 */
define("LCORE_ENTITY_KEY", "e");

/**
 * Key of result for text data. 
 */
define("LCORE_PLUGIN_RESULT_TEXT", "text");

/**
 * Key of result for json data. 
 */
define("LCORE_PLUGIN_RESULT_JSON", "application/json");

/**
 * Locale key in query. 
 */
define("LCORE_LOCALE_KEY", "l");

/**
 * Area key in query. 
 */
define("LCORE_AREA_KEY", "a");

/* Plugin. Finish.*/

/* Command. Start. */

/**
 * Command key in query. 
 */
define("LCORE_COMMAND_KEY", "u");

/**
 * Command "reset" in query. 
 */
define("LCORE_COMMAND_RESET_KEY", "r");

/* Command. Finish. */

/* Parameters. Start. */

/**
 * Key of callback parameter. 
 */
define("LCORE_MAIN_PARAMETERS_CALLBACK", "callback");

/**
 * Key of common parameter from framework. 
 */
define("LCORE_MAIN_PARAMETERS_COMMON", "common");

/**
 * Key of common data parameter from framework. 
 */
define("LCORE_MAIN_PARAMETERS_COMMON_DATA", "common_data");

/**
 * Key of in parameter. 
 */
define("LCORE_MAIN_PARAMETERS_IN", "in");

/**
 * Key of session parameter. 
 */
define("LCORE_MAIN_PARAMETERS_SESSION", "session");

/**
 * Key of theme. 
 */
define("LCORE_MAIN_PARAMETERS_THEME", "theme");

/**
 * Key of themes. 
 */
define("LCORE_MAIN_PARAMETERS_THEMES", "themes");

/**
 * Query parameters key. 
 */
define("LCORE_MAIN_PARAMETERS_QUERY_PARAMETERS", "query_parameters");

/**
 * Query parameters - all key. 
 */
define("LCORE_MAIN_PARAMETERS_QUERY_PARAMETERS_ALL", "$");

/**
 * Key of script name. 
 */
define("LCORE_MAIN_PARAMETERS_SCRIPT_NAME", "script_name");

/**
 * Key of script name. 
 */
define("LCORE_MAIN_PARAMETERS_SYS_SCRIPT_NAME", "SCRIPT_NAME");

/* Parameters. Finish. */

/* Other. Start. */

/**
 * Key of resource key. 
 */
define("LCORE_RESOURCES_KEY", "key");

/* Other. Finish. */

/* lcore. Common Functions. Start. */

/** 
 * Returns query parameter by key. Can return null.
 * @param $key key.
 * @return value.
 */
function lcore_get_query_parameter($key) {
	if(isset($_GET[$key])) {
		return $_GET[$key];
	}
	return null;
}

/**
 * Executes framework.
 * @return void.
 */
function lcore_go() {	
	if(!lcore_is_start()) {
		lcore_start();		
	}
	lcore_execute();
}

/**
 * Prepares start. Runs only one for session.
 * @return void.
 */
function lcore_start() {
	$_SESSION[LCORE_DATA] = array(LCORE_PLUGINS => array());
	$_SESSION[LCORE_DATA][LCORE_PLUGINS_TEMPLATES] = array();
	$_SESSION[LCORE_DATA][LCORE_DATA_SESSION_SETTINGS] = 
		array(LCORE_DATA_SESSION_SETTINGS_LOCALE => "");	
	lcore_plugins_load();
	lcore_plugins_include();
	$_SESSION[LCORE_DATA][LCORE_PLUGINS_START_DATA] = 
		lcore_plugins_call_function(LCORE_PLUGIN_FUNCTION_START);			
}

/**
 * Runs query.
 * @return void.
 */
function lcore_execute() {	
	lcore_plugins_include();
	$comman_key = lcore_get_query_parameter(LCORE_COMMAND_KEY);
	if($comman_key !== null && $comman_key === LCORE_COMMAND_RESET_KEY) {
		lcore_reset();
		lcore_start();	
	}
	lcore_dispatch();
}

/**
 * Resets all plugins.
 * @return void.
 */
function lcore_reset() {	
	lcore_plugins_call_function(LCORE_PLUGIN_FUNCTION_RESET);
	unset($_SESSION[LCORE_DATA]);
}

/**
 * Returns true if framework is started.
 * @return flag.
 */
function lcore_is_start() {	
	return !empty($_SESSION[LCORE_DATA]);
}

/* lcore. Common Functions. Finish. */

/* lcore. Define Functions. Start. */

/**
 * Returns path of framewok.
 * @return path.
 */
function lcore_start_root_path() {
	return $_SESSION[LCORE_START_CONFIGURATION][LCORE_START_ROOT_PATH];
}

/**
 * Returns path of application.
 * @return path.
 */
function lcore_start_path() {
	return $_SESSION[LCORE_START_CONFIGURATION][LCORE_START_PATH];
}

/**
 * Returns default plugin key.
 * @return key.
 */
function lcore_default_plugin() {
	return $_SESSION[LCORE_START_CONFIGURATION][LCORE_DEFAULT_PLUGIN];
}

/* lcore. Define Functions. Finish. */

/* lcore. Path Functions. Start. */

/**
 * Returns path of framework + given path.
 * @param $path path.
 * @return path.
 */
function lcore_get_root_path($path) {
	return lcore_start_root_path().$path;
}

/**
 * Returns path of application + given path.
 * @param $path path.
 * @return path.
 */
function lcore_get_path($path) {
	return lcore_start_path().$path;
}

/* lcore. Path Functions. Finish. */

/* lcore. Plugins. Start. */

/**
 * Loads all plugins.
 * @return void.
 */
function lcore_plugins_load() {
	lcore_plugins_register(lcore_get_root_path(LCORE_PLUGINS_PATH));
	lcore_plugins_register(lcore_get_path(LCORE_PLUGINS_PATH));
}

/**
 * Includes all plugins.
 * @return void.
 */
function lcore_plugins_include() {
	foreach($_SESSION[LCORE_DATA][LCORE_PLUGINS] as $k => $v) {
		require_once $v[LCORE_PLUGIN_FULL_PATH];
	}
}

/**
 * Registers all plugins.
 * @param $path root of plugins.
 * @return void.
 */
function lcore_plugins_register($path) {
	$descriptors = lcore_get_descriptors($path);
	foreach($descriptors as $k => $v) {		
		if(!lcore_plugin_is_register($k)) {
			lcore_plugin_register($v);						
		}
	}
}

/**
 * Returns true if plugin is registered.
 * @param $key key.
 * @return flag.
 */
function lcore_plugin_is_register($key) {
	return in_array($key, $_SESSION[LCORE_DATA][LCORE_PLUGINS]);
}

/**
 * Registers plugin. 
 * @param $descriptor descriptor.
 * @return void.
 */
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

/**
 * Returns descriptors for path. 
 * @param $path path.
 * @return array of descriptors.
 */
function lcore_get_descriptors($path) {
	$result = array();
	if(file_exists($path) === false) {
		return $result;
	}
	$dir = dir($path);	
	while (false !== ($entry = $dir->read())) {
		if($entry !== LCORE_DOT && $entry !== LCORE_DOT2 && $entry[0] !== LCORE_DOT) {			
			$result[$entry] = array();
			$result[$entry][LCORE_DESCRIPTOR_KEY] = $entry;			
			$result[$entry][LCORE_DESCRIPTOR_PATH] = $path.LCORE_SLASH.$entry;						
		}
	}
	return $result;
}

/**
 * Calls plugin function.
 * @param $plugin_key key.
 * @param $function_name name.
 * @param $parameters parameters.
 * @return result.
 */
function lcore_plugin_call_function($plugin_key, $function_name, &$parameters=array()) {
	return lcore_call_function($plugin_key.LCORE__.$function_name, $parameters);
}

/**
 * Calls function from all plugins. 
 * @param $function_name function name.
 * @param $parameters parameters.
 * @return results as array. 
 */
function lcore_plugins_call_function($function_name, &$parameters=array()) {
	$result = array();
	foreach($_SESSION[LCORE_DATA][LCORE_PLUGINS] as $k => $v) {
		$result[$v[LCORE_PLUGIN_KEY]] = 
			lcore_plugin_call_function($v[LCORE_PLUGIN_KEY], $function_name, $parameters);			
	}
	return $result;
}

/**
 * Loads descriptors (for templates, resources...). 
 * @param $descriptor descriptor. 
 * @param $path_pattern path.
 * @param $data_key key in session.
 * @return void.
 */
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

/**
 * Executes framework.
 * @return void.
 */
function lcore_dispatch() {
	$e = lcore_get_query_parameter(LCORE_ENTITY_KEY);
	if($e === null) {
		$e = lcore_default_plugin();
	}
	$query_parameters = array(
		LCORE_MAIN_PARAMETERS_QUERY_PARAMETERS_ALL => lcore_copy_get(),
		LCORE_ENTITY_KEY => lcore_get_query_parameter(LCORE_ENTITY_KEY),
		LCORE_LOCALE_KEY => lcore_get_query_parameter(LCORE_LOCALE_KEY),
		LCORE_AREA_KEY => lcore_get_query_parameter(LCORE_AREA_KEY)
	);	
	$session_data = array(
		LCORE_MAIN_PARAMETERS_THEME => lcore_theme(),
		LCORE_MAIN_PARAMETERS_THEMES => LCORE_PLUGINS_PATH_THEMES,
		LCORE_MAIN_PARAMETERS_QUERY_PARAMETERS => $query_parameters,
		LCORE_MAIN_PARAMETERS_SCRIPT_NAME => $_SERVER[LCORE_MAIN_PARAMETERS_SYS_SCRIPT_NAME]
	);
	$call_parameters = array(
		LCORE_MAIN_PARAMETERS_COMMON => array(
			LCORE_MAIN_PARAMETERS_COMMON_DATA => $_SESSION[LCORE_DATA],
			LCORE_MAIN_PARAMETERS_SESSION => $session_data			
			),
		LCORE_MAIN_PARAMETERS_IN => array(),
		LCORE_MAIN_PARAMETERS_CALLBACK => array());
	$d = lcore_d($e.LCORE__.LCORE_PLUGIN_FUNCTION_EXECUTE, $call_parameters);
	$result = lcore_get_text_from_data($d); 
	if(strlen($result) > 0) echo $result;
	else {
		$result = lcore_get_json_from_data($d);		
		header(LCORE_CONTENT_TYPE.LCORE_COLON." ".LCORE_PLUGIN_RESULT_JSON);
		echo $result;
	} 		 
}

/**
 * Executes plugin and return text.
 * @param $parameters 0 - plugin function, 1 - parameters.
 * @return text.
 */
function lcore_dt(&$parameters=array()) {
	return lcore_get_text_from_data(lcore_d($parameters[0], $parameters[1]));	
}

/**
 * Returns text data from result of plugin call.
 * @param $data data.
 * @return text.
 */
function lcore_get_text_from_data($data) {
	$result = "";
	foreach ($data as $r_k => $r_v) {
		foreach ($r_v as $k => $v) {
			if($k === LCORE_PLUGIN_RESULT_TEXT) {
				$result = $result.$v;
			} elseif ($k === LCORE_PLUGIN_RESULT_JSON) {
				return "";
			}
		}
	}
	return $result;
}

/**
 * Returns json data from result of plugin call.
 * @param $data data.
 * @return json.
 */
function lcore_get_json_from_data($data) {
	$result = array();
	foreach ($data as $r_k => $r_v) {
		foreach ($r_v as $k => $v) {
			if($k === LCORE_PLUGIN_RESULT_JSON) {
				array_push($result, $v);
			}
		}
	}
	return json_encode($result);
}

/**
 * Copies $_GET.
 * @return array.
 */
function lcore_copy_get() {
	$result = array();
	foreach ($_GET as $k => $v) {
		$result[$k] = $v;
	}
	return $result;
}

/* lcore. Dispatch. Finish. */

/* lcore. Session Functions. Start. */

/**
 * Returns theme.
 * @return theme.
 */
function lcore_theme() {
	return $_SESSION[LCORE_START_CONFIGURATION][LCORE_DEFAULT_THEME];
}

/**
 * Returns locale.
 * @return locale.
 */
function lcore_locale() {
	$v = lcore_get_query_parameter(LCORE_LOCALE_KEY);
	if($v === null || strlen($v) <= 0) {		
		$v = $_SESSION[LCORE_DATA][LCORE_DATA_SESSION_SETTINGS][LCORE_DATA_SESSION_SETTINGS_LOCALE];
	}	
	return $v;
}

function lcore_change_locale($locale) {
	$_SESSION[LCORE_DATA][LCORE_DATA_SESSION_SETTINGS][LCORE_DATA_SESSION_SETTINGS_LOCALE] = $locale;	
}

/* lcore. Session Functions. Finish. */

/* lcore. Resources Functions. Start. */

/**
 * Returns value from resources.
 * @param $parameters parameters (0 - key, 1 - default value).
 * @return value.
 */
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

/**
 * Returns value from resources. Can return null. 
 * @param $set paths.
 * @param $l key of locale.
 * @param $key key.
 * @return value.
 */
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

/**
 * Returns value from resource. 
 * @param $path path.
 * @param $key key.
 * @return value.
 */
function lcore_get_resource($path, $key) {	
	if(!file_exists($path)) {
		return null;
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

/**
 * Prepares link.
 * @param $parameters parameters.
 * @return link.
 */
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
		LCORE_AMPERSAND.LCORE_LOCALE_KEY.LCORE_EQUAL.$l.
		LCORE_AMPERSAND.LCORE_AREA_KEY.LCORE_EQUAL.$a.$t;	
}

/* lcore. Links Functions. Finish. */