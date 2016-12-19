<?php

// from a large url with variables, it extracts and returns the value of a given variable
if(!function_exists("extract_var_from_url")){
	function extract_var_from_url($var, $url){
		$value_to_return = ''; 
		
		$question_position = strpos($url, "?");
		if($question_position){
			$url = substr($url, $question_position+1);
		}
		$variables_and_values = explode("&", $url);
		foreach($variables_and_values as $variable_and_value){
			list($var_name, $var_value) = explode("=", $variable_and_value);
			if($var_name == $var){
				$value_to_return = $var_value;
			}
		}
		return $value_to_return;
	}
}
// will return "zzz"
// echo extract_var_from_url("foo", 'site.com/file.php?asd=zxc&foo=zzz');


?>
