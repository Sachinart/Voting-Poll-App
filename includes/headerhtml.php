<?php
	/* include necessary functions */
	include_once 'util/functions.php';
	
	$currentPageClass = "active";
?>
<nav class="navbar navbar-inverse navbar-fixed-top" id="menuHeader">
	<div class="container">
	<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>
		<!-- Collection of nav links and other content for toggling -->
		<div id="navbarCollapse" class="collapse navbar-collapse">
			<ul class="nav navbar-nav navbar-right">
				<li class='<?php if($pagename == "Home") echo $currentPageClass; ?>'> <a href='home.php'><i class="fa fa-home"></i> Home</a> </li> 
				<?php if (login_check($mysqli) == false) { ?>
				<li class='<?php if($pagename == "Sign Up") echo $currentPageClass; ?>'><a href="sign-up.php"><i class="fa fa-share-square-o"></i> Sign up</a></li>
				<li class='<?php if($pagename == "Login") echo $currentPageClass; ?>'><a href="login.php"><i class="fa fa-sign-in"></i> Login</a></li>
				<?php }
				else {
				?>
				<li class='<?php if($pagename == "Votes") echo $currentPageClass; ?>'> <a href='votes.php'><i class="fa fa-thumbs-up"></i> Votes</a> </li>
				<li class='<?php if($pagename == "Logout") echo $currentPageClass; ?>'><a href="action/logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
				<?php }
				?>
			</ul>
		</div>
	</div>
</nav>