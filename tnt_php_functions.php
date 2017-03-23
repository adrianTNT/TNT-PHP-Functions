
<?php

if(!function_exists("do_post_request")){
	
	function do_post_request($post_url, $post_data_array){
	
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

/*		
$post_data_array['var1'] = 'sadkldak';
$post_data_array['var2'] = 'sadkldak';

echo do_post_request('http://www.adriantnt.com/demos/_ajax-contact-form/x_post.php', $post_data_array);

?>
