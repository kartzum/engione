<?php
/**
 * start plugin. 
 */

/* Public. Start. */

/**
 * Plugin key. 
 */
define("START_KEY", "start");

/**
 * Plugin version.
 */
define("START_VERSION", "next");

/* Public. Finish. */

/**
 * Starts plugin. 
 * @param $parameters parameters.
 * @return data.
 */
function start_start(&$parameters=array()) {
	return array("key" => START_KEY, "version" => START_VERSION);	
}

/**
 * Executes plugin. 
 * @param $parameters parameters.
 * @return results.
 */
function start_execute(&$parameters=array()) {
	$result = null;
	
	$start_result = array(
		"0" => array(
			"activity" => "call",
			"function" => "ltext_execute",
			"in" => array(
				"template" => "start.thtml", 
				"data" => array(
					"title" => array("start_title", "start")))
			)
	);
	
	if(count($parameters["callback"]) === 0) {
		$result = $start_result;
	} else {
		$callback = $parameters["callback"];
		$result = array(
			"0" => array(
				"activity" => "data",
				"response" => $callback["0"]["0"]),
			"r" => array("activity" => "ok"));
	}
	
	return $result;
}

/** 
 * Resets plugin.
 * @param $parameters parameters.
 * @return result.
 */
function start_reset(&$parameters=array()) {
	return array();	
}