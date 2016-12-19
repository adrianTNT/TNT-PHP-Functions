<?php 

// can remove variables from: full url, from urls related to site root, form just a query string like "a=1&b=2"
if(!function_exists("remove_var_from_url")){
	function remove_var_from_url($variable_name, $url_string){
		
		// this is anything before the "?" sign
		$base_url = '';
		// the variable separator, can be "?" if is a full URL or can be empty, if we just have "&sort=sales&oprder=asc"
		$separator = "";
		$start_pos = 0;
		$return_string = "";
		//
		if(strpos($url_string,"?")!==false){
			$start_pos = strpos($url_string, "?")+1;
			$separator = "?";
			$base_url = substr($url_string, 0, $start_pos-1);
		}
		// start building the string from the base url (which can be empty)
		$return_string = $base_url;
		$url_vars_string = substr($url_string, $start_pos);
		$names_and_values = explode("&", $url_vars_string);

		//
		foreach($names_and_values as $value){
			list($var_name, $var_value) = explode("=", $value);
			if($var_name != $variable_name){
				// add the "?" once if needed
				if(!$separator_added){
					$return_string.= $separator;
					$separator_added = true;
				} else {
					$return_string.= "&";
				}
				$return_string.= $var_name."=".$var_value;
			}
		}
		
		// remove "&" from margins
		$return_string = trim($return_string, "&");
		
		// remove the "?" if is at the end, it means it was just one variable that was removed
		$return_string = rtrim($return_string, "?");
		
		return $return_string;
	}
}

?>
