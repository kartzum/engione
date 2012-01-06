<?php

/**
 * Key of module.
 */
define("LOGO_MODULE_KEY", "logo");
/**
 * Version of module.
 */
define("LOGO_MODULE_VERSION", "next");
/**
 * Name of module.
 */
define("LOGO_MODULE_NAME", "Logo");
/**
 * Description of module.
 */
define("LOGO_MODULE_DESCRIPTION", "Logo - welcome module.");
/**
 * Url of module.
 */
define("LOGO_MODULE_URL", "");

/**
 * Provides initialization of module. Runs only ones per session.
 * @param array $params params.
 * @return information about module.
 */
function logo_start($params=array()) {
	return array(LOGO_MODULE_KEY => array(
			CORE_MODULE_DESCRIPTOR => array(
					CORE_MODULE_DESCRIPTOR_KEY => LOGO_MODULE_KEY,
					CORE_MODULE_DESCRIPTOR_VERSION => LOGO_MODULE_VERSION,
					CORE_MODULE_DESCRIPTOR_NAME => LOGO_MODULE_NAME,
					CORE_MODULE_DESCRIPTOR_DESCRIPTION => LOGO_MODULE_DESCRIPTION,
					CORE_MODULE_DESCRIPTOR_URL => LOGO_MODULE_URL))
			);
}

/**
 * Calculates data per one request.
 * @param array $params params
 * @return result of calulations.
 */
function logo_execute($params=array()) {
	return array(LOGO_MODULE_KEY => array("content" => array("data" => array(
			"name" => "Engione",
			"l__logo_text1" => "l__logo_text1",
			"l__logo_text2" => "l__logo_text2",
			"l__logo_text3" => "l__logo_text3",
			"l__logo_text4" => "l__logo_text4",
			"l__logo_text5" => "l__logo_text5",
			"links" => array(
					array("link_value"=>"home", "link_name"=>"l__home_page"), 
					array("link_value"=>"about", "link_name"=>"l__about_page"))
			))));	
}

?>