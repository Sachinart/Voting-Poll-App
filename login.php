<?php
	/* include necessary functions */
	include_once 'util/functions.php';

	$pagename = "Login";
	
	// if already logged in, redirect to index.php
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
					<h2>Login to your account</h2>
					<form name="login_form" id="login_form">
						<!--<input type="text" placeholder="Name" />-->
						<div class="row">
							<div class="col-sm-12 form-group required">
								<!-- <label for="inpEmail" class="control-label">First Name</label> -->
								<input type='email' class="form-control input-lg" name='inpEmail' id='inpEmail' maxlength="50"  placeholder="Email Address" />
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 form-group required">
								<!-- <label for="inpPassword" class="control-label">First Name</label> -->
								<input type='password' class="form-control input-lg" name='inpPassword' id='inpPassword' maxlength="50"  placeholder="Password" />
							</div>
						</div>
						<!--<span>
							<input type="checkbox" class="checkbox"> 
							Keep me signed in
						</span>-->
						<div class="row text-center">
							<button type="button" class="btn btn-warning btn-lg active " onclick="authenticate('divAuthMsg', this.form);" >Login</button>
						</div>
						<br />
						<div class="row text-center">
							<a href="sign-up.php" >Not registered? Sign Up!</a>
						</div>
					</form>
					<br>
					<div class="row">
						<div id = 'divAuthMsg' class="col-xs-12 alert alert-danger" style='display:none;'></div>
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
	
	// function to authenticate the user
		function authenticate(containerId, form) {
			
			container = document.getElementById(containerId);
			container.style.display = "none";
			var rtnMsg = '';
			var success = '0';
			var redirectLogin = '';

			email		= form.inpEmail
			password	= form.inpPassword
			
			// Check password and confirmation are the same
			if (! validateEmail(email.value)) {
				rtnMsg = 'Please enter valid email';
				email.focus();
				showMsg();
				throw new Error(rtnMsg);
			}
				
			// Check each field has a value
			if (email.value == '' || password.value == '') {
				rtnMsg = 'You must fill all the mandatory fields. Please try again.';
				showMsg();
				throw new Error(rtnMsg);
			}
			
			var params ={};
			params["email"]		= email.value;
			params["p"]			= hex_sha512(password.value);
			
			$.ajax(  {
				data: { params : JSON.stringify(params) },
				url: 'action/login.php',
				type: 'POST',
				dataType: 'text',
				
				success: function(out) {
					// alert(out.trim());
					var arrResponse = JSON.parse(out);
					rtnMsg = arrResponse.rtnMsg;
					success = arrResponse.success;
					if(success === 1){
						redirectLogin = arrResponse.data.redirectLogin;
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
					if((success === 1) && (redirectLogin != '')){
						window.location = redirectLogin;
					}
			});	


			//Nested function. because in case of error, it is not going in done function.
			function showMsg(){
				password.value = "";

				if(success === 1){
					container.innerHTML = '<strong>Success! </strong>' + rtnMsg;
					container.classList.remove("alert-danger");
					container.classList.add("alert-success");
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