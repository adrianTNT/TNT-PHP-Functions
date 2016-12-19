<?php

if(!function_exists("add_var_to_url")){
	function add_var_to_url($variable_name,$variable_value,$url_string){
		// first we will remove the var (if it exists)
		// test if url has variables (contains "?")
		if(strpos($url_string,"?")!==false){
			$start_pos = strpos($url_string,"?");
			$url_vars_strings = substr($url_string,$start_pos+1);
			$names_and_values = explode("&",$url_vars_strings);
			$url_string = substr($url_string,0,$start_pos);
			foreach($names_and_values as $value){
				list($var_name,$var_value)=explode("=",$value);
				if($var_name != $variable_name){
					if(strpos($url_string,"?")===false){
						$url_string.= "?";
					} else {
						$url_string.= "&";
					}
					$url_string.= $var_name."=".$var_value;
				}
			}
		} 
		// add variable name and variable value
		if(strpos($url_string,"?")===false){
			$url_string .= "?".$variable_name."=".$variable_value;
		} else {
			$url_string .= "&".$variable_name."=".$variable_value;
		}
		return $url_string;
	}
}


?>