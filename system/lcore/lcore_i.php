<?php
define("LCORE_PARAMETERS_CALLBACK", "callback");
define("LCORE_PARAMETERS_COMMON", "common");
define("LCORE_PARAMETERS_IN", "in");
define("LCORE_PARAMETERS_FUNCTION", "function");
define("LCORE_PARAMETERS_ACTIVITY", "activity");
define("LCORE_PARAMETERS_OK", "ok");
define("LCORE_PARAMETERS_CALL", "call");
define("LCORE_PARAMETERS_DATA", "data");
define("LCORE_PARAMETERS_RESPONSE", "response");

function lcore_call_function($function, &$parameters=array()) {	
	return call_user_func($function, $parameters);
}

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