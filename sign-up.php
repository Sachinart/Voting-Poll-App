<?php
	/* include_once 'includes/db_connect.php'; */
	include_once 'util/functions.php';
	
	//sec_session_start();
	/* unset($_SESSION['redirectLogin']);
	
	$_SESSION['redirectLogin'] = "index.php"; */

	$pagename = "Sign Up";
	
	if (login_check($mysqli)) {
		header("Location: index.php");
	} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo SITE_NAME; ?> | <?php echo $pagename ?></title>
	<?php
		include("includes/library.php");
	?>
</head><!--/head-->

<body>
	<?php
		include("includes/headerhtml.php");
	?>

	<div class="container top-pane ">
		<div class="row" style=''>
			<div class="col-sm-6 col-sm-offset-3">
				<div class="well">
					<h2>New User Signup!</h2>
					<form name="registration_form" id="registration_form" >
						<!--
						<div class="row">
							<div class="col-sm-12 form-group required">
								<input type='text' class="form-control input-lg" name='inpUserName' id='inpUserName' maxlength="50"  placeholder="Choose User Name" />
							</div>
						</div>
						-->
						<div class="row">
							<div class="col-sm-12 form-group required">
								<input type='text' class="form-control input-lg" name='inpFirstName' id='inpFirstName' maxlength="50"  placeholder="Enter First Name" />
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 form-group required">
								<input type='text' class="form-control input-lg" name='inpLastName' id='inpLastName' maxlength="50"  placeholder="Enter Last Name" />
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 form-group required">
								<input type='email' class="form-control input-lg" name='inpEmail' id='inpEmail' maxlength="50"  placeholder="Enter your Email Address" />
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 form-group required">
								<input type='password' class="form-control input-lg" name='inpPassword' id='inpPassword' maxlength="50"  placeholder="Password" />
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 form-group required">
								<input type='password' class="form-control input-lg" name='inpConfirmPassword' id='inpConfirmPassword' maxlength="50"  placeholder="Confirm Password" />
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 form-group checkbox required">
								<label style="color:#ec971f"><input id="inpToggleVote" type="checkbox" >Are you agree with <a href="tnc.docx">Terms and Conditions?</a></label>
							</div>
						</div>
						<div class="row text-center">
							<button type="button" class="btn btn-warning btn-lg active " onclick="register('divRegMsg', this.form);">Register</button>
						</div>
						<br />
						<div class="row text-center">
							<a href="login.php" >Already registered? Login!</a>
						</div>
					</form>
					<br>
					<div class="row">
						<div id = 'divRegMsg' class="col-xs-12 alert alert-danger" style='display:none;'></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
		include("includes/footerhtml.php");
	?>

</body>

	<script>
	
		function register(containerId, form) {

			container = document.getElementById(containerId);
			container.style.display = "none";
			var rtnMsg = '';
			var success = '0';

			//userName	 	= form.inpUserName
			firstName    	= form.inpFirstName
			lastName     	= form.inpLastName
			email        	= form.inpEmail
			password		= form.inpPassword
			confirmPassword	= form.inpConfirmPassword
			  
			// Check the username
			/*  re = /^\w+$/; 
			if(!re.test(userName.value)) { 
				rtnMsg = "Username must contain only letters, numbers and underscores. Please try again."; 
				userName.focus();
				showMsg();
				throw new Error(rtnMsg);
			}  */

			if (firstName.value == '') {
				rtnMsg = 'Please enter first name';
				firstName.focus();
				showMsg();
				throw new Error(rtnMsg);
			}
			if (lastName.value == '') {
				rtnMsg = 'Please enter last name';
				lastName.focus();
				showMsg();
				throw new Error(rtnMsg);
			}
			if (! validateEmail(email.value)) {
				rtnMsg = 'Please enter valid email';
				email.focus();
				showMsg();
				throw new Error(rtnMsg);
			}
			
			// Check password and confirmation are the same
			if (password.value != confirmPassword.value) {
				rtnMsg = 'Your password and confirmation password do not match. Please try again';
				password.focus();
				showMsg();
				throw new Error("Your password and confirmation password do not match. Please try again.");
			}
			
			// Check that the password is sufficiently long (min 6 chars)
			// The check is duplicated below, but this is included to give more
			// specific guidance to the user
			if (password.value.length < 6) {
				rtnMsg = 'Passwords must be at least 6 characters long. Please try again';
				password.focus();
				showMsg();
				throw new Error("Passwords must be at least 6 characters long. Please try again.");
			}
			
			// At least one number, one lowercase and one uppercase letter 
			// At least six characters 
			var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/; 
			if (!re.test(password.value)) {
				rtnMsg = 'Passwords must contain at least one number, one lowercase and one uppercase letter.  Please try again.';
				showMsg();
				throw new Error("Passwords must contain at least one number, one lowercase and one uppercase letter.  Please try again.");
			}
				
			// Check each field has a value
			/* if (firstName.value == '' || lastName.value == '' || email.value == '' || mobile.value == '' || password.value == '' || confirmPassword.value == '' ) {
				rtnMsg = 'You must fill all the mandatory fields. Please try again.';
				showMsg();
				throw new Error(rtnMsg);
			} */
			
			var params ={};
			params["firstName"]		= firstName.value;
			params["lastName"]		= lastName.value;
			params["email"]			= email.value;
			params["p"]				= hex_sha512(password.value);
			
			var btnEnable = $("#inpToggleVote").is(":checked");
			if(btnEnable){
				params["vote"] = 1;
			}
			else {
				params["vote"] = "";
			}
			
			$.ajax(  {
				data: { params : JSON.stringify(params) },
				url: 'action/register.php',
				type: 'POST',
				dataType: 'text',
				
				success: function(out) {
					// alert(out.trim());
					var arrResponse = JSON.parse(out);
					rtnMsg = arrResponse.rtnMsg;
					success = arrResponse.success;
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
				password.value = "";
				confirmPassword.value = "";

				if(success === 1){
					container.innerHTML = '<strong>Success! </strong>' + rtnMsg;
					container.classList.remove("alert-danger");
					container.classList.add("alert-success");
					
					window.setTimeout(function(){
					// Move to a new location or you can do something else
					window.location.href = "home.php";
					}, 3000);
				}
				else {
					container.innerHTML = '<strong>Error! </strong>' + rtnMsg;
					container.classList.remove("alert-success");
					container.classList.add("alert-danger");
				}
				container.style.display = "block";
			}
		}
	</script> 
</html>