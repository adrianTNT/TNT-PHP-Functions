
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


// from a large url with variables, it reads and returns the value of a given variable
if(!function_exists("get_var_from_url")){
	function get_var_from_url($var, $url){
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



/*
This is like getting url contents by get_url() but instead it gets the reply when posting variables to that URL 
like using form POST over GET
make an array of variables, then post them

$post_data_array['first_name'] = 'John';
$post_data_array['last_name'] = 'Smith';

echo tnt_post_url('script.php', $post_data_array);
*/
if(!function_exists("tnt_post_url")){
	
	function tnt_post_url($post_url, $post_data_array){
	
		// use key 'http' even if you send the request to https://...
		$post_options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($post_data_array),
			),
		);
		$post_context  = stream_context_create($post_options);
		$post_result = file_get_contents($post_url, false, $post_context);
		
		if($post_result === FALSE) {
			return "post error";
		}
		
		return $post_result;
		
	}
}


?>
