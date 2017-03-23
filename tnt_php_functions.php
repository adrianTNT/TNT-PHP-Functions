
<?php


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
