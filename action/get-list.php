<?php

/* include necessary functions */
include_once '../util/functions.php';

header('Content-Type: application/json');
	
$main_array = Array();
$data = Array();
$main_array['data'] = $data;
$success = 1;
$rtnMsg = "";

$params = json_decode($_POST['params'], TRUE);

sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($params['id'])) { // Check if id is passed or not?
	$id = $params['id'];
} else {
	$success = 0;
	$rtnMsg = "Please pass all parameters.";
}

if($success == 0) { // If success ==0, close the file and send success = 0 to home.php
	$main_array['success'] = $success;
	$main_array['rtnMsg'] = $rtnMsg;
	$json_array = json_encode($main_array, JSON_FORCE_OBJECT);
	die($json_array);
}	

// Create the query
$query = "SELECT id, firstName, lastName FROM members WHERE id != '" . $id . "'";

// Prepare the query
$stmt = $mysqli->prepare($query);

if ($stmt) {
	$result = $mysqli->query($query);
} else {
	$success = 0;
	$rtnMsg = 'Database error, query: ' . $query . ', errno: ' . $mysqli->errno . ', error: ' . $mysqli->error;
	$main_array['success'] = $success;
	$main_array['rtnMsg'] = $rtnMsg;
	$json_array = json_encode($main_array, JSON_FORCE_OBJECT);
	die($json_array);
}

if(!empty($result)){ // If data exist on database table, print it using a while loop
	$result_data = "
	<div class='row'>
		<div class='col-sm-12'>
			<h2 style='text-align: center;'>Vote now!</h2>
		</div>
	</div>
	<hr style=''>
	<div class='form-controls covert-list' id='field-options'>";
	$i=1;
	while($row = $result->fetch_assoc()){
		$id	=$row["id"];
		$firstName	=$row["firstName"];
		$lastName	=$row["lastName"];
		
		$result_data = $result_data . '
		<div id="field-options-'.$id.'-container">
			<input type="radio" name="inpVote" class="option-input radio" value="'.$id.'" id="field-options-'.$id.'"> 
			<label title="" id="field-options" for="field-options-'.$id.'"><span>'.  $firstName . ' ' . $lastName . '</span></label>
		</div>
		';
		$i++;
	}
	
	$result_data= $result_data . "</div>";
	
	$data['voteList'] = $result_data; // Pass result to $data array
	$success = 1;
	$rtnMsg = "Search Complete.";
}	
else {
	$data = Array();
	$success = 2;
	$rtnMsg = "No Records found.";
}

// pass all the needed information back to home.php
$main_array['success'] = $success;
$main_array['rtnMsg'] = $rtnMsg;
$main_array['data'] = $data;
$json_array = json_encode($main_array, JSON_FORCE_OBJECT);
die($json_array);