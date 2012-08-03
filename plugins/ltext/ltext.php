<?php
/**
 * ltext plugin.
 */

/* Public. Start. */

/**
 * Key of plugin. 
 */
define("LTEXT_KEY", "ltext");

/**
 * Version of plugin.
 */
define("LTEXT_VERSION", "next");

/* Public. Fiinsh. */

/* Common. Start. */

/**
 * Represents index. 
 */
define("LTEXT_LEX_INDEX", "i");

/**
 * Represents value. 
 */
define("LTEXT_LEX_VALUE", "l");

/**
 * Represents exec flag. 
 */
define("LTEXT_LEX_EXECUTEABLE", "e");

/**
 * Represents raw value. 
 */
define("LTEXT_LEX_RAW", "r");

/**
 * Represents name. 
 */
define("LTEXT_LEX_NAME", "n");

/**
 * Represents data. 
 */
define("LTEXT_LEX_DATA", "d");

/**
 * Represents data bind function. 
 */
define("LTEXT_LEX_DATA_BIND_FUNCTION", "d");

/**
 * Represents include function. 
 */
define("LTEXT_LEX_INCLUDE_FUNCTION", "i");

/**
 * Represents r include function. 
 */
define("LTEXT_LEX_INCLUDE_R_FUNCTION", "ir");

/**
 * Represents theme path function. 
 */
define("LTEXT_LEX_PATH_T_FUNCTION", "pt");

/**
 * Represents call function. 
 */
define("LTEXT_LEX_CALL_FUNCTION", "c");

/**
 * Represents call function of core - lcore_dt. 
 */
define("LTEXT_LEX_CALL_TEXT_FUNCTION", "ct");

/* Common. Finish. */

/* Base. Start. */

/**
 *  Represents "/".
 */
define("LTEXT_SLASH", "/");

/* Base. Finish. */

/* ltext. Common Functions. Start. */

/** 
 * Starts plugin.
 * @param $parameters parameters.
 * @return data.
 */
function ltext_start(&$parameters=array()) {
	return array("key" => LTEXT_KEY, "version" => LTEXT_VERSION);	
}

/**
 * Executes plugin. 
 * @param $parameters parameters.
 * @return results.
 */
function ltext_execute(&$parameters=array()) {	
	$result = array();
	$empty_result = array(
		"r" => array("activity" => "ok")
	);
	if($parameters["in"]["template"] !== null) {
		$common = $parameters["common"];
		$key = $parameters["in"]["template"];
		
		$template_path = ltext_get_path($common, $key);			
		$contents = file_get_contents($template_path, FILE_USE_INCLUDE_PATH);
		$lexs = ltext_get_lexs($contents);
				
		$parse_result = ltext_parse_template_with_lexs(
			$common, $key, $template_path, $contents, $lexs, $parameters["in"]["data"]);
		$start_result = array(
			"0" => array(
				"activity" => "data",
				"response" => array("text" => $parse_result)),
			"r" => array("activity" => "ok")
		);
		
		$result = $start_result;
	} else {
		$result = $empty_result;
	}
	return $result;
}

/** 
 * Resets plugin.
 * @param $parameters parameters.
 * @return result.
 */
function ltext_reset(&$parameters=array()) {	
	return array();
}

/* ltext. Common Functions. Finish. */

/**
 * Returns path of template. Can return null. 
 * @param $key key.
 * @param $data data.
 * @return path.
 */
function ltext_get_template_path($key, $data) {
	foreach($data as $k_t => $k_v) {
		foreach($k_v as $key_inner => $value_inner) {
			if($key_inner === $key) {
				return $value_inner;
			}
		}		
	}	
	return null;
}

/**
 * Parses template. 
 * @param $common data.
 * @param $key key.
 * @param $parameters parameters.
 * @return text. 
 */
function ltext_parse_template($common, $key, &$parameters=array()) {	
	$template_path = ltext_get_path($common, $key);
	$contents = file_get_contents($template_path, FILE_USE_INCLUDE_PATH);	
	$lexs = ltext_get_lexs($contents);
	return ltext_parse_template_with_lexs(
		$common, $key, $template_path, $contents, $lexs, $parameters);	
}

/**
 * Parses template. 
 * @param $common data.
 * @param $key key.
 * @param $template_path path.
 * @param $contents contents.
 * @param $lexs lexs.
 * @param $parameters parameters.
 * @return text.
 */
function ltext_parse_template_with_lexs(
		$common, $key, $template_path, $contents, &$lexs, &$parameters=array()) {		
	return ltext_bind($common, $key, $template_path, $contents, $lexs, $parameters);	
}

/**
 * Returns lexs. 
 * @param $contents contents.
 * @return lexs.
 */
function ltext_get_lexs($contents) {
	$result = array();
	$n = 0;
	
	$l = null;
	$s = -1;
	
	for($i = 0; $i < strlen($contents); $i++) {
		$symbol = $contents[$i];
		if($symbol === "<") {
			$l = $symbol;
			$s = $i;
			continue;			
		}			
		if($l !== null) {
			if($symbol === " " || $symbol === "<" || $symbol === ">") {
				$raw = $l.$symbol;
				$result[$n] = array();
				$result[$n][LTEXT_LEX_INDEX] = $s;
				$result[$n][LTEXT_LEX_VALUE] = str_replace(array("<", "/", "!", "%"), "", $l);
				$result[$n][LTEXT_LEX_EXECUTEABLE] = strpos($l, "%") === false ? false : true;
				$result[$n][LTEXT_LEX_RAW] = $raw;

				$n += 1;
				
				$l = null;
				$s = -1;
			} else {
				$l = $l.$symbol;
			}
		}				
	}
	
	return $result; 
}

/**
 * Binds data.
 * @param $common data.
 * @param $key key.
 * @param $template_path path.
 * @param $contents contents.
 * @param $lexs lexs.
 * @param $parameters parameters.
 * @return text.
 */
function ltext_bind($common, $key, $template_path, $contents, $lexs, &$parameters) {
	$result = "";
	$start = 0;	
	foreach ($lexs as $k => $v) {
		$l = $v[LTEXT_LEX_VALUE];	
		$s = $v[LTEXT_LEX_INDEX];
		$l_values = ltext_parse_lex($l);
		$l_name = $l_values[LTEXT_LEX_NAME];
		$l_data = null;
		if($l_name === LTEXT_LEX_DATA_BIND_FUNCTION) {
			$l_data = $parameters[$l_values[LTEXT_LEX_DATA][0]];						
		} else if($l_name === LTEXT_LEX_INCLUDE_FUNCTION) {
			$l_data = ltext_parse_template(
				$common,
				$l_values[LTEXT_LEX_DATA][0], 
				$parameters[$l_values[LTEXT_LEX_DATA][1]]);
		} else if($l_name === LTEXT_LEX_INCLUDE_R_FUNCTION) {
			$k_inner = $l_values[LTEXT_LEX_DATA][0];
			$s_inner = $parameters[$l_values[LTEXT_LEX_DATA][1]];
			foreach($s_inner as $s_k => $s_v) {
				$l_data = $l_data.ltext_parse_template($common, $k_inner, $s_v);
			}
		} else if($l_name === LTEXT_LEX_PATH_T_FUNCTION) {
			$l_data = ltext_get_root($template_path).LTEXT_SLASH
				.$common["session"]["themes"].LTEXT_SLASH.$common["session"]["theme"].LTEXT_SLASH.
				$parameters[$l_values[LTEXT_LEX_DATA][0]];			
		} else if($l_name === LTEXT_LEX_CALL_FUNCTION) {			
			$call_function = $l_values[LTEXT_LEX_DATA][0];
			$call_parameters = $l_values[LTEXT_LEX_DATA][1];
			$l_data = ltext_call_function($call_function, $parameters[$call_parameters]);			
		} else if($l_name === LTEXT_LEX_CALL_TEXT_FUNCTION) {			
			$call_ct_l = $l_values[LTEXT_LEX_DATA][0]."_execute";
			$call_ct_p = $parameters[$l_values[LTEXT_LEX_DATA][1]];
			$call_ct_parameters = array(
				"common" => $common,
				"in" => $call_ct_p,
				"callback" => array() 	
			);
			$call_ct_p = array($call_ct_l, $call_ct_parameters);
			$l_data = ltext_call_function("lcore_dt", $call_ct_p);			
		}	
		if($l_data !== null) {
			$t = substr($contents, $start, $s - $start);
			$result = $result.$t.$l_data;
			$start = $s + strlen($v[LTEXT_LEX_RAW]);
		}
	}
	$tail = substr($contents, $start, strlen($contents) - $start);
	$result = $result.$tail;
	return $result;
}

/**
 * Parses lex. 
 * @param $raw data.
 * @return array of pure data.
 */
function ltext_parse_lex($raw) {
	$result = array();
	$s = strpos($raw, "(");
	$f = strpos($raw, ")");
	$name = substr($raw, 0, $s);
	$body = substr($raw, $s + 1, $f - $s - 1);
	$result[LTEXT_LEX_NAME] = $name;
	$result[LTEXT_LEX_DATA] = explode(",", $body);
	return $result;
}

/**
 * Returns path. 
 * @param $common data.
 * @param $key key.
 * @return path.
 */
function ltext_get_path($common, $key) {
	return ltext_get_template_path($key, $common["common_data"]["plugins_templates"]);	
}

/**
 * Returns root of path. 
 * @param $template_path path.
 * @return root of path.
 */
function ltext_get_root($template_path) {
	$template_info = pathinfo($template_path);
	return dirname($template_info["dirname"]);	
}

/**
 * Calls function. 
 * @param $function function name.
 * @param $parameters parameters.
 * @return result. 
 */
function ltext_call_function($function, &$parameters=array()) {
	return call_user_func($function, $parameters);
}