<?php

require_once 'config.php';

//Ensures no one loads page and does simple spam check.
if(isset($_POST['name']) && empty($_POST['spam-check'])) {
	
	//Include our email validator for later use 
	require 'email-validator.php';
	$validator = new EmailAddressValidator();
	
	//Declare our $errors variable we will be using later to store any errors.
	$error = '';
	
	//Setup our basic variables
	$input_name = strip_tags($_POST['name']);
	$input_email = strip_tags($_POST['email']);
	$input_message = strip_tags($_POST['message']);
	
	//We'll check and see if any of the required fields are empty.
	if (strlen($input_name) < 2) {
		$error['name'] = "Please enter your name.";
	}

	if (strlen($input_message) < 5) {
		$error['comments'] = "Please leave a comment.";
	}
	
	//Make sure the email is valid.
	if (!$validator->check_email_address($input_email)) {
		$error['email'] = "Please enter a valid email address.";
	}
	
	//Now check to see if there are any errors 
	if(!$error) {
		//No errors, send mail using conditional to ensure it was sent.
		if(mail($your_email, "Message from $input_name", $input_message . "\n\n---\nThis email was sent by contact form.", "From: $input_email")) {
			echo '<p class="success">Your email has been sent!</span>';
		} else {
			echo '<p class="error">There was a problem sending your email!</span>';
		}
		
	} else {
		
		//Errors were found, output all errors to the user.
		$response = (isset($error['name'])) ? $error['name'] . "<br /> \n" : null;
		$response .= (isset($error['email'])) ? $error['email'] . "<br /> \n" : null;
		$response .= (isset($error['comments'])) ? $error['comments'] . "<br /> \n" : null;

		echo '<p class="error">' . $response . '</span>';
		
	}
	
} else {
	die('Direct access to this page is not allowed.');
}