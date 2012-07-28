<?php
define("START_KEY", "start");
define("START_VERSION", "next");
 
function start_start(&$parameters=array()) {
	return array("key" => START_KEY, "version" => START_VERSION);	
}

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

function start_reset(&$parameters=array()) {	
}