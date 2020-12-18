<?php

include_once '../util/functions.php';

header('Content-Type: application/json');

$main_array = Array();
$data = Array();
$main_array['data'] = $data;
$success = 1;
$rtnMsg = "";

$params = json_decode($_POST['params'], TRUE);
// till here
if (isset($params['firstName'], $params['lastName'], $params['email'], $params['p'], $params['vote'])) {
	
    // Sanitize and validate the data passed in
    /* $userName = filter_var(INPUT_POST, 'userName', FILTER_SANITIZE_STRING); */
    $firstName = filter_var($params['firstName'], FILTER_SANITIZE_STRING);
	$lastName = filter_var($params['lastName'], FILTER_SANITIZE_STRING);
	$email = filter_var($params['email'], FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Not a valid email
		$success = 0;
		$rtnMsg = "The email address you entered is not valid.";
    }
	
	$password = filter_var($params['p'], FILTER_SANITIZE_STRING);
    if (strlen($password) != 128) {
        // The hashed pwd should be 128 characters long.
        // If it's not, something really odd has happened
		/* die('{"success":"0","rtnMsg":"Invalid password configuration."}'); */
		$success = 0;
		$rtnMsg = "Invalid password configuration.";		
    }
	
	$voteCheck = filter_var($params['vote'], FILTER_SANITIZE_STRING);
	if($voteCheck!= 1){
		$success = 0;
		$rtnMsg = "Please accept the document first.";	
	}
}
else {
	$success = 0;
	$rtnMsg = "Please Enter All the mandatory parameters.";		
}		
		
if($success == 0) {
	$main_array['success'] = $success;
	$main_array['rtnMsg'] = $rtnMsg;
	$json_array = json_encode($main_array, JSON_FORCE_OBJECT);
	die($json_array);
}	


$prep_stmt = "SELECT id FROM members WHERE email = ? LIMIT 1";
$stmt = $mysqli->prepare($prep_stmt);

if ($stmt) {
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$stmt->store_result();
	
	if ($stmt->num_rows == 1) {
		$success = 0;
		$rtnMsg = "A user with this email address already exists.";
	}
} else {
	$success = 0;
	$rtnMsg = 'Database error. , query: ' . $prep_stmt . ', errno: ' . $mysqli->errno . ', error: ' . $mysqli->error;
}
		
if($success == 0) {
	$main_array['success'] = $success;
	$main_array['rtnMsg'] = $rtnMsg;
	$json_array = json_encode($main_array, JSON_FORCE_OBJECT);
	die($json_array);
}

// Create a random salt
$random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));

// Create salted password 
$password = hash('sha512', $password . $random_salt);

$queryMember = "INSERT INTO members (firstName, lastName, email, password, salt, isAllowed) VALUES (?, ?, ?, ?, ?, 1)";

$insert_stmt = $mysqli->prepare($queryMember);
// Insert the new user into the database 
if ($insert_stmt) {
	$insert_stmt->bind_param('sssss', $firstName, $lastName, $email, $password, $random_salt);
	
	// Execute the prepared query.
	if ($insert_stmt->execute()) {
		$success = 1;
		$rtnMsg = "Registered Successfully, Please Login. <br />";		
		/* die('{"success":"1","rtnMsg":"Registered Successfully, Please Login."}'); */
	}
	else {
		$success = 0;
		$rtnMsg = 'Database error, query: ' . $queryMember . ', errno: ' . $mysqli->errno . ', error: ' . $mysqli->error;
		/* die('{"success":"0","rtnMsg":"' . $rtnMsg . '"}'); */
	}
}
else {
	$success = 0;
	$rtnMsg = 'Database error, query: ' . $queryMember . ', errno: ' . $mysqli->errno . ', error: ' . $mysqli->error;
	/* die('{"success":"0","rtnMsg":"' . $rtnMsg . '"}'); */
}

$main_array['success'] = $success;
$main_array['rtnMsg'] = $rtnMsg;
$json_array = json_encode($main_array, JSON_FORCE_OBJECT);
die($json_array);