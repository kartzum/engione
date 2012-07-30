<?php
/**
 * Interface function of framework.
 */

/**
 * Key of callback parameter. 
 */
define("LCORE_PARAMETERS_CALLBACK", "callback");

/**
 * Key of common parameter of framework.
 */
define("LCORE_PARAMETERS_COMMON", "common");

/**
 * Key of in parameter. 
 */
define("LCORE_PARAMETERS_IN", "in");

/**
 * Key of function parameter. 
 */
define("LCORE_PARAMETERS_FUNCTION", "function");

/**
 * Key of activity parameter. 
 */
define("LCORE_PARAMETERS_ACTIVITY", "activity");

/**
 * Key of ok activity. 
 */
define("LCORE_PARAMETERS_OK", "ok");

/**
 * Key of call activity. 
 */
define("LCORE_PARAMETERS_CALL", "call");

/**
 * Key of data. 
 */
define("LCORE_PARAMETERS_DATA", "data");

/**
 * Key of response. 
 */
define("LCORE_PARAMETERS_RESPONSE", "response");

/**
 * Calls function.
 * @param $function function name.
 * @param $parameters parameters.
 * @return results.
 */
function lcore_call_function($function, &$parameters=array()) {	
	return call_user_func($function, $parameters);
}

/**
 * Executes plugin function.
 * @param $function function name.
 * @param $parameters parameters (array with keys: "common", "in", "callback").
 * @return results.
 */
function lcore_d($function, &$parameters=array()) {
	$result = array();
	$callback = $parameters[LCORE_PARAMETERS_CALLBACK];
	for(;;) {		
		$function_parameters = array(
			LCORE_PARAMETERS_COMMON => $parameters[LCORE_PARAMETERS_COMMON], 
			LCORE_PARAMETERS_IN => $parameters[LCORE_PARAMETERS_IN],
			LCORE_PARAMETERS_CALLBACK => $callback);
		$function_result = lcore_call_function($function, $function_parameters);		
		$ok = false;
		$callback = array();
		foreach($function_result as $k => $v) {
			$activity = $v[LCORE_PARAMETERS_ACTIVITY];
			if(LCORE_PARAMETERS_OK === $activity) {
				$ok = true;
				break;
			} else if(LCORE_PARAMETERS_CALL === $activity) {
				$activity_function_parameters = array(
					LCORE_PARAMETERS_COMMON => $parameters[LCORE_PARAMETERS_COMMON],
					LCORE_PARAMETERS_IN => $v[LCORE_PARAMETERS_IN],	
					LCORE_PARAMETERS_CALLBACK => array()
				);				
				$callback[$k] = lcore_d($v[LCORE_PARAMETERS_FUNCTION], $activity_function_parameters);				 
			} else if(LCORE_PARAMETERS_DATA === $activity) {
				$result[$k] = $v[LCORE_PARAMETERS_RESPONSE];
			} 
		}
		if($ok === true) {
			break;
		}
	}	
	return $result;
}