<?php

/* include_once 'db_connect.php'; */
include_once '../util/functions.php';

header('Content-Type: application/json');
	
$main_array = Array();
$success = 1;
$rtnMsg = "";
$voteCount= "";
$id ="";

$params = json_decode($_POST['params'], TRUE);

// start session
sec_session_start(); // Our custom secure way of starting a PHP session.

// fetch user id who is voting
$voteFrom =  $_SESSION['user_id'];

if (isset($params['id'])) {
	$voteTo = $params['id'];    // fetch user who is getting the vote
} else {
	$success = 0;
	$rtnMsg = "Please pass all parameters.";
}

if($success == 0) {
	$main_array['success'] = $success;
	$main_array['rtnMsg'] = $rtnMsg;
	$json_array = json_encode($main_array, JSON_FORCE_OBJECT);
	die($json_array);
}	
            
// check if the voter is allowed to vote or not
$query = "SELECT isAllowed FROM members WHERE id = '" . $voteFrom."'";
$stmt = $mysqli->prepare($query);

if ($stmt) {
	//$result = $mysqli->query($query);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($allow);
	$stmt->execute();
	$stmt->fetch();
	$stmt->close();
	//$totalPageRows = mysqli_num_rows($result);
} else {
	$success = 0;
	$rtnMsg = 'Database error, query: ' . $query . ', errno: ' . $mysqli->errno . ', error: ' . $mysqli->error;
	$main_array['success'] = $success;
	$main_array['rtnMsg'] = $rtnMsg;
	$json_array = json_encode($main_array, JSON_FORCE_OBJECT);
	die($json_array);
}

// if he is allowed to vote, insert entry of his vote in votes table
if($allow == 1){
	$queryVote = "INSERT into votes(voteFrom,voteTo) VALUES(?,?)";

	$stmtVote = $mysqli->prepare($queryVote);

	if ($stmtVote) {
		$stmtVote->bind_param('ss', $voteFrom, $voteTo);		
		if (! $stmtVote->execute()) {
			$success = 0;
			$rtnMsg = 'Database error, query: ' . $queryVote . ', errno: ' . $mysqli->errno . ', error: ' . $mysqli->error;
		}
		else {
			$success = 1;
			$rtnMsg = "Your votes has been successfully submitted!";
		}
	} 
	else {
		$success = 0;
		$rtnMsg = 'Database error, query: ' . $queryVote . ', errno: ' . $mysqli->errno . ', error: ' . $mysqli->error;
	}

	// And update that members to not allow to vote more than once
	$queryMem = "UPDATE members SET isAllowed = 0 WHERE id = ?";
	$stmtMem = $mysqli->prepare($queryMem);

	if ($stmtMem) {
		$stmtMem->bind_param('s', $voteFrom);		
		if (! $stmtMem->execute()) {
			$success = 0;
			$rtnMsg = 'Database error, query: ' . $queryMem . ', errno: ' . $mysqli->errno . ', error: ' . $mysqli->error;
		}
		else {
			$success = 1;
			$rtnMsg = "Voted!";
		}
	} 
	else {
		$success = 0;
		$rtnMsg = 'Database error, query: ' . $queryMem . ', errno: ' . $mysqli->errno . ', error: ' . $mysqli->error;
	}
}
else{ // if not allowed to vote
	$success = 0;
	$rtnMsg = 'Your Vote has been Counted! You have already voted on this poll.';
	$main_array['success'] = $success;
	$main_array['rtnMsg'] = $rtnMsg;
	$json_array = json_encode($main_array, JSON_FORCE_OBJECT);
	die($json_array);
}

$main_array['success'] = $success;
$main_array['rtnMsg'] = $rtnMsg;
$json_array = json_encode($main_array, JSON_FORCE_OBJECT);
die($json_array);