<?php

/**
 * Prepares set of modules to call and renders.
 * @param array $params params.
 * @return array set of modules to call and renders.
 */
function default_prepare($params=array()) {
	return array(
		"modules" => array("modules_descriptors" => array("logo" => array())), 
		"renderers" => array("renderers_descriptors" => array("templates_default_render" => array("function_template" => "deault_template"))));	
}

/*
 * Start - Default Private Functions.
 */

function deault_template($params=array()) {
	return templates_get_data(default_get_templates_path("index1.html"));
}

function default_get_start1($params=array()) {
	return templates_get_data(default_get_templates_path("start1.html"));
}

function default_get_body1($params=array()) {
	if(core_get_page_key() === "about") {
		return templates_get_data(default_get_templates_path("body2.html"));
	}
	return templates_get_data(default_get_templates_path("body1.html"));
}

function default_get_finish1($params=array()) {
	return templates_get_data(default_get_templates_path("finish1.html"));
}

function default_get_script1($params=array()) {
	return default_get_resources_path("scripts.js");
}

function default_get_css1($params=array()) {
	return default_get_resources_path("global.css");
}

function default_get_image1($params=array()) {
	return default_get_images_path("image1.png");
}

function default_get_href1($params=array()) {
	if(!isset($params["link_value"])) {
		return "#";
	}
	$page = $params["link_value"];
	if(empty($page)) {
		$page = "#";
	}
	return core_add_page_to_query_string($page);
}

function default_get_templates_path($path) {
	return core_get_path("sites/default/templates/".$path);
}

function default_get_resources_path($path) {
	return core_get_path("sites/default/themes/default/".$path);
}

function default_get_images_path($path) {
	return core_get_path("sites/default/themes/default/images/".$path);
}

/*
 * Finish - Default Private Functions.
 */

?>