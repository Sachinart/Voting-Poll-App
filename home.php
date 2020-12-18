<?php
	/* include basic functions needed */
	include_once 'util/functions.php';

	// Start session
	sec_session_start();
	unset($_SESSION['redirectLogin']);
	
	// Use to redirect to the homepage when comeback from another webpage
	$_SESSION['redirectLogin'] = "home.php";
	
	$pagename = "Home";
	
	// Check if user is logged in, else redirect to login page
	if (!login_check($mysqli)) {
		header("Location: login.php");
	} 
	
	// get value of allow from table to check if user is allowed to vote or not?
	$id = $_SESSION['user_id'];
	$query = "SELECT isAllowed from members WHERE id != '".$id."'";
	$stmt = $mysqli->prepare($query);

	if ($stmt) {
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($allow);
		$stmt->execute();
		$stmt->fetch();
		$stmt->close();
		}
	else {
		$rtnMsg = 'Database error, query: ' . $query . ', errno: ' . $mysqli->errno . ', error: ' . $mysqli->error;
		die($rtnMsg);
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo SITE_NAME; ?> | <?php echo $pagename; ?></title>
    <?php
		include("includes/library.php");
	?>

</head><!--/head-->

<body>
<?php
	include("includes/headerhtml.php");
?>
	
<div class="top-pane">
	<div class="topImage" style="margin-top: -19px;transition: all 0.5s ease-in-out;">
		<div class="container">			
			<div class="well" id="poll">
				<div class="row" id="divCatalogue">

				</div>
				
				<div class='row text-center'>
					<div class='col-sm-12'>
						<div class='btn-wrapper'>
						<button class='button2 btn-vote' onclick='voteUp()'>Vote</button>
						<button class='button1 btn-result' id="resultBtn" onclick='location.href="votes.php"'>Result</button>
					</div>
					</div>
				</div>
				<div class="row" style="padding: 0px 15px;">
					<div id = 'divCatalogueMsg' class="col-xs-12 alert alert-danger" style='display:none;border-radius: 0 0 4px 4px;margin-bottom: 0;'></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
	include("includes/footerhtml.php");
?>

<script>
	
	// fetch id and allow value from PHP to JS variables
	var id = '<?php echo $id; ?>';
	var allow = '<?php echo $allow; ?>';
	//console.log(allow);
	
	// Call function
	getList(1);
	
	function getList(page) {
			
			content = document.getElementById("divCatalogue");
			content.style.display = "none";
			container = document.getElementById("divCatalogueMsg");
			container.style.display = "none";
			
			var rows		= document.getElementById("inpRows");
			var rtnMsg = '';
			var success = '0';
			var params ={};
			
			// Insert id into param array and passed it to get-list.php in action folder
			params["id"]		= id;
			
			$.ajax(  {
				data: { params : JSON.stringify(params) },
				url: 'action/get-list.php',
				type: 'POST',
				dataType: 'text',
				
				success: function(out) {
					// alert(out.trim());
					var arrResponse = JSON.parse(out);
					rtnMsg = arrResponse.rtnMsg;
					success = arrResponse.success;
					if(success === 1){
						voteList = arrResponse.data.voteList; // Get resultant data from get-list.php in voteList variable
					}
				},
			
				error: function(jqXHR, exception) {
					if (jqXHR.status === 0) {
						rtnMsg = 'Not connect.\n Verify Network.';
					} else if (jqXHR.status == 404) {
						rtnMsg = 'Requested page not found. [404]';
					} else if (jqXHR.status == 500) {
						rtnMsg = 'Internal Server Error [500].';
					} else if (exception === 'parsererror') {
						rtnMsg = 'Requested JSON parse failed.';
					} else if (exception === 'timeout') {
						rtnMsg = 'Time out error.';
					} else if (exception === 'abort') {
						rtnMsg = 'Ajax request aborted.';
					} else {
						rtnMsg = 'Uncaught Error.\n' + jqXHR.responseText;
					}
					success = '0';
					showMsg();
				}
			}).done(function() {
					showMsg();
			});
					
			//Nested function. because in case of error, it is not going in done function.
			function showMsg(){
				
				if(success === 1){ // If successfully voted
					content.innerHTML = voteList;
					content.style.display = "block";
				}
				else if(success === 0){ // If vote is already counted
					container.innerHTML = '<strong>Error! </strong>' + rtnMsg;
					container.classList.remove("alert-success");
					container.classList.add("alert-danger");
					container.style.display = "block";
				}
				else if(success === 2){ // If no data found on table
					container.innerHTML = '<strong></strong>' + rtnMsg;
					container.classList.remove("alert-success");
					container.classList.remove("alert-danger");
					container.classList.add("alert-warning");
					container.style.display = "block";
				}
			}
		
		}
		
		
	function voteUp(){
		if(document.querySelector('input[name="inpVote"]:checked')){
			var id = document.querySelector('input[name="inpVote"]:checked').value;
			var rows = document.getElementById("inpRows");
			
			// Begin
			
			container = document.getElementById("divCatalogueMsg");
			container.style.display = "none";
			
			var rtnMsg = '';
			var success = '0';
			var params ={};
			
			params["id"] = id;
			
			$.ajax(  {
				data: { params : JSON.stringify(params) },
				url: 'action/vote.php',
				type: 'POST',
				dataType: 'text',
				
				success: function(out) {
					// alert(out.trim());
					var arrResponse = JSON.parse(out);
					rtnMsg = arrResponse.rtnMsg;
					success = arrResponse.success;
					if(success === 1){
						//window.location.href= "votes.php";
					}
				},
			
				error: function(jqXHR, exception) {
					if (jqXHR.status === 0) {
						rtnMsg = 'Not connect.\n Verify Network.';
					} else if (jqXHR.status == 404) {
						rtnMsg = 'Requested page not found. [404]';
					} else if (jqXHR.status == 500) {
						rtnMsg = 'Internal Server Error [500].';
					} else if (exception === 'parsererror') {
						rtnMsg = 'Requested JSON parse failed.';
					} else if (exception === 'timeout') {
						rtnMsg = 'Time out error.';
					} else if (exception === 'abort') {
						rtnMsg = 'Ajax request aborted.';
					} else {
						rtnMsg = 'Uncaught Error.\n' + jqXHR.responseText;
					}
					success = '0';
					showMsg();
				}
			}).done(function() {
					showMsg();
			});
					
			//Nested function. because in case of error, it is not going in done function.
			function showMsg(){
				if(success === 1){
					container.style.display = "block";
					document.getElementById("resultBtn").style.display = "inline-block";
					container.innerHTML = '<strong>Success! </strong>' + rtnMsg;
					container.classList.remove("alert-danger");
					container.classList.add("alert-success");
					
					window.setTimeout(function(){
					// Move to a new location or you can do something else
					window.location.href = "votes.php";
					}, 3000);
				}
				else{
					container.innerHTML = '<strong>Error! </strong>' + rtnMsg;
					container.classList.remove("alert-success");
					container.classList.add("alert-danger");
					container.style.display = "block";
				}
			}
							
			// End
		}
		else{
			alert("you must select an option");
		}
	}
	</script>

</body>
</html>