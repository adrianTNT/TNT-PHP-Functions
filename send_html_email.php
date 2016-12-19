<?php

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
tnt_multipart_mail("receiver@example.com", "testing html email", "hello (this is plain text)", "Hello <strong>(this is html)</strong>", "Sender Name <sender@example.com>");

?>
