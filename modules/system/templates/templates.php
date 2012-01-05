<?php

/**
 * Key of module.
 */
define("TEMPLATES_MODULE_KEY", "templates");
/**
 * Version of module.
 */
define("TEMPLATES_MODULE_VERSION", "next");
/**
 * Name of module.
 */
define("TEMPLATES_MODULE_NAME", "Templates");
/**
 * Description of module.
 */
define("TEMPLATES_MODULE_DESCRIPTION", "Templates - contains functions for renders.");
/**
 * Url of module.
 */
define("TEMPLATES_MODULE_URL", "");

/**
 * Key of name of function that returns content for rendering.
 */
define("TEMPLATES_FUNCTION_TEMPLATE_CODE", "function_template");
/**
 * Key of content array.
 */
define("TEMPLATES_CONTENT_CODE", "content");

/**
 * Start tag of part of script in common place.
 */
define("TEMPLATES_PART_1_START", "<!--");
/**
 * Finish tag of part of script in common place.
 */
define("TEMPLATES_PART_1_FINISH", "-->");
/**
 * Start tag of part of script in specific place.
 */
define("TEMPLATES_PART_2_START", "{%");
/**
 * Finish tag of part of script in specific place.
 */
define("TEMPLATES_PART_2_FINISH", "%}");

/**
 * Provides initialization of module. Runs only ones per session.
 * @param array $params params.
 * @return information about module.
 */
function templates_start($params=array()) {
	return array(TEMPLATES_MODULE_KEY => array(
			CORE_MODULE_DESCRIPTOR => array(
					CORE_MODULE_DESCRIPTOR_KEY => TEMPLATES_MODULE_KEY,
					CORE_MODULE_DESCRIPTOR_VERSION => TEMPLATES_MODULE_VERSION,
					CORE_MODULE_DESCRIPTOR_NAME => TEMPLATES_MODULE_NAME,
					CORE_MODULE_DESCRIPTOR_DESCRIPTION => TEMPLATES_MODULE_DESCRIPTION,
					CORE_MODULE_DESCRIPTOR_URL => TEMPLATES_MODULE_URL))
			);
}

/**
 * Calculates data per one request.
 * @param array $params params
 * @return result of calulations.
 */
function templates_execute($params=array()) {
	return array(TEMPLATES_MODULE_KEY => array());
}

/*
 * Start - Templates API Functions.
 */

/**
 * Renders data into text.
 * @param array $params initial information.
 * @return array text.
 */
function templates_default_render($params=array()) {
	$f = $params[1][TEMPLATES_FUNCTION_TEMPLATE_CODE];
	$data = core_call_func($f);
	$temp_data = $data;
	foreach($params[0] as $v) {
		foreach($v as $key => $value) {
			foreach($value as $content_key => $content_value) {
				if($content_key === TEMPLATES_CONTENT_CODE) {
					foreach($content_value as $ck => $cv) {
						$temp_data = templates_default_parse($temp_data, $cv);
					}
				}
			}
		}
	}
	return array($temp_data);
}

/**
 * Returns text from file.
 * @param string $path path.
 * @return string text.
 */
function templates_get_data($path) {
	$c = file_get_contents($path, FILE_USE_INCLUDE_PATH);
	return $c;
}

/*
 * Finish - Templates API Functions.
 */

/**
 * Returns array about one part in text.
 * @param string $data text.
 * @param stirng $start start tag.
 * @param string $finish finish tag.
 * @param int $position start position.
 * @return array array with 4 values (start position, finish position, value, value with tags).
 */
function templates_find_part(&$data, $start, $finish, $position) {
	$p1 = strpos($data, $start, $position);
	if($p1 !== false) {
		$p2 = strpos($data, $finish, $p1 + 1);
		if($p2 !== false) {
			$s1 = substr($data, $p1 + strlen($start), $p2 - ($p1 + strlen($start)));
			$s2 = substr($data, $p1, $p2 - $p1 + strlen($finish));
			return array($p1, $p2, $s1, $s2);
		}
	}
	return false;
}

/**
 * Returns only value between tags.
 * @param string $data text.
 * @param string_type $s start tag.
 * @param string $f finish tag.
 * @return string value.
 */
function templates_default_parse_params(&$data, $s, $f) {
	$d = templates_find_part($data, $s, $f, 0);
	if($d !== false) {
		return $d[2];
	}
	return false;
}

/**
 * Returns name of function.
 * @param string $data text.
 * @return string name of function.
 */
function templates_default_parse_f(&$data) {
	return templates_default_parse_params($data, "f(", ")");
}

/**
 * Returns name of bind item.
 * @param string $data text.
 * @return string bind item.
 */
function templates_default_parse_b(&$data) {
	return templates_default_parse_params($data, "b(", ")");
}

/**
 * Returns name of repeat item.
 * @param string $data text.
 * @return string repeat item.
 */
function templates_default_parse_r(&$data) {
	return templates_default_parse_params($data, "r(", ")");
}

/**
 * Returns name of bind item for resources.
 * @param string $data text.
 * @return string bind item.
 */
function templates_default_parse_l(&$data) {
	return templates_default_parse_params($data, "l(", ")");
}

/**
 * Returns result of call function.
 * @param string $f function name.
 * @param array $set params.
 * @return object result of call function.
 */
function templates_call_f($f, &$set) {
	return core_call_func_with_params($f, $set);
}

/**
 * Calculates function part.
 * @param string $data text.
 * @param string $f function name.
 * @param string $full full function name.
 * @param array $set params.
 * @return string text.
 */
function templates_default_calculate_part_f(&$data, $f, $full, &$set) {
	$sub_value_new = templates_call_f($f, $set);
	$sub_value_new_after_parse = templates_default_parse($sub_value_new, $set);
	return str_replace($full, $sub_value_new_after_parse, $data);
}

/**
 * Calculates repeat part.
 * @param string $data text.
 * @param string $f name.
 * @param string $full full name.
 * @param array $set params.
 * @return string text.
 */
function templates_default_calculate_part_r(&$data, $f, $full, &$set) {
	if(strpos($data, TEMPLATES_PART_2_START, 0) === false) {
		return $data;
	}
	$r = templates_default_parse_r($f);
	if($r !== false) {
		$r_data = $set[$r];
		$full_t = TEMPLATES_PART_1_START.$full.TEMPLATES_PART_1_FINISH;
		$pos1 = strpos($data, $full_t, 0);
		if($pos1 !== false) {
			$pos2 = strpos($data, $full_t, $pos1 + strlen($full_t) + 1);
			if($pos2 !== false) {
				$temp = substr($data, $pos1 + strlen($full_t), $pos2 - $pos1 - strlen($full_t));
				$start = substr($data, 0, $pos1);
				$finish = substr($data, $pos2 + strlen($full_t), strlen($data) - ($pos2 + strlen($full_t)));
				$finish = str_replace($full_t, "", $finish);
				$result = "";
				foreach($r_data as $k => $v) {
					$result .= templates_default_parse($temp, $v);
				}
				return $start.$result.$finish;
			}
		}
		return $data;
	} else {
		return $data;
	}
}

/**
 * Calculates one part.
 * @param string $data text.
 * @param array $temlate_part_descriptor part descriptor.
 * @param array $set params.
 * @return string text.
 */
function templates_default_calculate_part(&$data, $temlate_part_descriptor, &$set) {
	$result = $data;
	$sub_value = $temlate_part_descriptor[2];
	$sub_value_full = $temlate_part_descriptor[3];
	$sub_part_descriptor =
	templates_find_part($sub_value, TEMPLATES_PART_2_START, TEMPLATES_PART_2_FINISH, 0);
	if($sub_part_descriptor !== false) {
		$f = templates_default_parse_f($sub_part_descriptor[2]);
		if($f !== false) {
			$result = templates_default_calculate_part_f($data, $f, $sub_value_full, $set);
		}
	}
	return $result;
}

/**
 * Calculates all text.
 * @param string $data text.
 * @param array $set set of params.
 * @return string result.
 */
function templates_default_parse(&$data, &$set) {
	$data_value = $data;
	$temp_data = $data;
	$t_start = TEMPLATES_PART_1_START;
	$t_finish = TEMPLATES_PART_1_FINISH;
	$phase = 0;
	$start = 0;

	do {
		$temlate_part_descriptor = templates_find_part($data_value, $t_start, $t_finish, $start);
		if($temlate_part_descriptor === false) {
			$phase += 1;
			if($phase === 1) {
				$t_start = TEMPLATES_PART_2_START;
				$t_finish = TEMPLATES_PART_2_FINISH;
				$start = 0;
				$data_value = $temp_data;
			}
		}
		if($phase === 0) {
			$temp_data = templates_default_calculate_part($temp_data, $temlate_part_descriptor, $set);
			$start = $temlate_part_descriptor[1] + 1;
		} elseif($phase === 1) {
			if($temlate_part_descriptor !== false) {
				$b = templates_default_parse_b($temlate_part_descriptor[2]);
				if($b !== false) {
					if(isset($set[$b])) {
						$temp_data = str_replace($temlate_part_descriptor[3], $set[$b], $temp_data);
					}
				} else {
					$l = templates_default_parse_l($temlate_part_descriptor[2]);
					if($l !== false) {
						if(isset($set[$l])) {
							$l_value = core_get_resources($set[$l], $set[$l]);
							$temp_data = str_replace($temlate_part_descriptor[3], $l_value, $temp_data);
						}
					} else {
						$f = templates_default_parse_f($temlate_part_descriptor[2]);
						if($f !== false) {
							$temp_data = templates_default_calculate_part_f($temp_data, $f, $temlate_part_descriptor[3], $set);
						} else {
							$temp_data = templates_default_calculate_part_r(
									$temp_data, $temlate_part_descriptor[2], $temlate_part_descriptor[3], $set);
						}
					}
				}
				$start = $temlate_part_descriptor[1] + 1;
			}
		}
		if($phase === 2) {
			break;
		}
	} while(true);

	return $temp_data;
}

?>