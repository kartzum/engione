<?php

/**
 * Prepares set of modules to call and renders.
 * @param array $params params.
 * @return array set of modules to call and renders.
 */
function zero_prepare($params=array()) {	
	return array(
		"modules" => array("modules_descriptors" => array("logo" => array())), 
		"renderers" => array("renderers_descriptors" => array("templates_default_render" => array("function_template" => "zero_template"))));	
}

/*
 * Start - Zero Private Functions.
 */

function zero_template($params=array()) {
	return "<!DOCTYPE html><html lang=\"en\"><head><meta charset=\"utf-8\"/><title>{%b(name)%}</title></head><body></body></html>";
}

/*
 * Finish - Zero Private Functions.
 */

?>