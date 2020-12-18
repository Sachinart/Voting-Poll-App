<?php

/* include_once 'db_connect.php'; */
include_once '../util/functions.php';

header('Content-Type: application/json');

$main_array = Array();
$data = Array();
$main_array['data'] = $data;
$success = 1;
$rtnMsg = "";

$params = json_decode($_POST['params'], TRUE);

sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($params['email'], $params['p'])) {
    /* $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['p']; // The hashed password. */
	
	$email = filter_var($params['email'], FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Not a valid email
        /* $error_msg .= '<p class="error"></p>'; */
		/* die('{"success":"0","rtnMsg":"The email address you entered is not valid."}'); */
		$success = 0;
		$rtnMsg = "The email address you entered is not valid.";
    }
	
	$password = filter_var($params['p'], FILTER_SANITIZE_STRING);
	
    if (login($email, $password, $mysqli) == true) {
        // Login success 
		if (isset($_SESSION['redirectLogin']))
			$redirectLogin = $_SESSION['redirectLogin'];
		else
			$redirectLogin = 'index.php';
		
		$data['redirectLogin'] = $redirectLogin;
		$success = 1;
		$rtnMsg = "Login Successful.";
		
        //header("Location: ../review.php") or
		/* die('{"success":"1","rtnMsg":"Login Successful.","redirectLogin":"' . $redirectLogin . '"}'); */
    } else {
        // Login failed 
        /* header('Location: ../index.php?error=1'); */
		/* die('{"success":"0","rtnMsg":"Incorrect Password or Email.","redirectLogin":""}'); */
		$success = 0;
		$rtnMsg = "Incorrect Password or Email.";
    }
} else {
    // The correct POST variables were not sent to this page. 
    /* header('Location: ../error.php?err=Could not process login'); */
	/* die('{"success":"0","rtnMsg":"Please Enter Email and password.","redirectLogin":""}'); */
	$success = 0;
	$rtnMsg = "Please Enter Email and password.";
}

$main_array['success'] = $success;
$main_array['rtnMsg'] = $rtnMsg;
$main_array['data'] = $data;
$json_array = json_encode($main_array, JSON_FORCE_OBJECT);
die($json_array);