
<?php


// a function that tests if computer is connected to internet, returns true/false
// test can be made against a domain and port 80, or google dns 8.8.8.8 and port 53 (dns)
// timeout of 5 seems to timeout in 10, maybe some cumulated connection procedures
if(!function_exists("is_connected")){
	
	function is_connected(){

		$connection = @fsockopen("8.8.8.8", 53, $error_number, $error_text, 5);  
		
		if($connection){
			$is_connected = true; 
			fclose($connection);
		}else{
			$is_connected = false; 
		}
		return $is_connected;
	
	}
}

// function to time how long an operation takes, returns a number like 0.120 (seconds)
// timer('name'), do operation, timer('name'), print($timer);
if(!function_exists("timer")){
	function timer($timer_name){
		global $timer, $timer_start, $is_admin;
		if(!isset($timer_start[$timer_name])){
			$timer_start[$timer_name] = microtime(true);
		} else {
			
			// define a precision (decimals) because deducting two numbers with many decimals woult result in error (123E-somethig)
			// specify '.' separator and blank '' thousands separator to avoid comparing strings like "1,490,891,288.942" instead of number 1490891288.942
			$timer[$timer_name] = (number_format(microtime(true), 6, '.', '') - number_format($timer_start[$timer_name], 6, '.', ''));
			// format final result
			$timer[$timer_name] = (number_format($timer[$timer_name], 3, '.', ''));

		}
	}
}

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


if(!function_exists("tnt_multipart_mail")){
	function tnt_multipart_mail($email_receiver, $email_subject, $email_text, $email_html, $email_from){
		
		// bountry is a string that will delimit the text from html version of the message
		$email_boundary = uniqid('', true);
		
		// set the return (Bounce) address
		$return_path = $email_from;
		// if sender is formatted as "Name <user@site.com>" then take sender from inside the <> (last occurance)
		if(preg_match('/<([^<]*)>$/', $email_from, $receiver_matches)){
			$return_path = $receiver_matches[1];
		}
		// note that ORDER is important, e.g. content-type AFTER mime (says SpamAssassin score)
		$email_headers  = "From: ".$email_from."\n"; 
		$email_headers .= "MIME-Version: 1.0\n";
		$email_headers .= "Content-Type: multipart/alternative;\n";
		$email_headers .= " boundary=\"$email_boundary\"\n";
									
		// start building the text AND html parts of the message body
		$email_message = "";
		$email_message .= "--$email_boundary\n";
		$email_message .= "Content-Type: text/plain; charset=UTF-8\n";
		$email_message .= "\n";
		$email_message .= $email_text."\n";
		//
		$email_message .= "--$email_boundary\n";
		$email_message .= "Content-Type: text/html; charset=UTF-8\n";
		$email_message .= "\n";
		$email_message .= $email_html."\n";
		$email_message .= "--$email_boundary--";
		
		mail($email_receiver, $email_subject, $email_message, $email_headers, "-f$return_path");
	}
}
// replace "receiver@example.com" with the name of your receiver
// replace "Sender Name <sender@example.com>" with your name and email, you can also just use email without name
// replace the content of the plain text and html part of the email
// tnt_multipart_mail("receiver@example.com", "testing html email", "hello (this is plain text)", "Hello <strong>(this is html)</strong>", "Sender Name <sender@example.com>");



// timestamp to "x days/hours ago" converter
if(!function_exists("timestamp_to_ago")){
	function timestamp_to_ago($timestamp){
		if(isset($timestamp) and $timestamp !=''){  
			$difference = time() - $timestamp;
			$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
			$lengths = array("60","60","24","7","4.35","12","10");
			for($j = 0; isset($lengths[$j]) && $difference >= $lengths[$j]; $j++){
				$difference /= $lengths[$j]; // <<< line with problem
			}
			$difference = round($difference);
			if($difference != 1) $periods[$j].= "s";
			$text = "$difference $periods[$j] ago";
			return $text;
		}
	}
}
// echo timestamp_to_ago(1482124507);



// auto rotates an image file based on exif data from camera
if(!function_exists("gd_auto_rotate")){
	function gd_auto_rotate($original_file, $destination_file=NULL){
		
		$original_extension = strtolower(pathinfo($original_file, PATHINFO_EXTENSION));
		if(isset($destination_file) and $destination_file!=''){
			$destination_extension = strtolower(pathinfo($destination_file, PATHINFO_EXTENSION));
		}
		
		// try to auto-rotate image by gd if needed (before editing it)
		// by imagemagik it has an easy option
		if(function_exists("exif_read_data")){
			
			$exif_data = exif_read_data($original_file);
			$exif_orientation = $exif_data['Orientation'];
			
			// value 1 = normal ?! keep it ?!
			
			if($exif_orientation=='3'  or $exif_orientation=='6' or $exif_orientation=='8'){
				
				$new_angle[3] = 180;
				$new_angle[6] = -90;
				$new_angle[8] = 90;
				
				// load the image
				if($original_extension == "jpg" or $original_extension == "jpeg"){
					$original_image = imagecreatefromjpeg($original_file);
				}
				if($original_extension == "gif"){
					$original_image = imagecreatefromgif($original_file);
				}
				if($original_extension == "png"){
					$original_image = imagecreatefrompng($original_file);
				}
				
				$rotated_image = imagerotate($original_image, $new_angle[$exif_orientation], 0);
				
				// if no destination file is set, then show the image
				if(!$destination_file){
					header('Content-type: image/jpeg');
					imagejpeg($rotated_image, NULL, 100);
				}
						
				// save the smaller image FILE if destination file given
				if($destination_extension == "jpg" or $destination_extension=="jpeg"){
					imagejpeg($rotated_image, $destination_file,100);
				}
				if($destination_extension == "gif"){
					imagegif($rotated_image, $destination_file);
				}
				if($destination_extension == "png"){
					imagepng($rotated_image, $destination_file,9);
				}
				
				imagedestroy($original_image);
				imagedestroy($rotated_image);
			
			}
		}
	}
}


?>
