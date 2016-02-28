<?php
include_once "common/access.php";
if (loggedIn())
	header("Location:index.php");
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
	<!--Font Awesome-->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" type="text/css" charset="utf-8">
	<!--Our CSS-->
	<link rel="stylesheet" href="css/main.css" type="text/css" charset="utf-8">
	<link rel="stylesheet" href="css/login.css" type="text/css" charset="utf-8">
</head>

<body>
	<div class="container">
		<div class="login">
			<h1>Login</h1>
			<div class="login-social">
				<h2>Using social media:</h2>
				<span class="login-social-facebook">
					<a href="#facebookAuth">
						<i class="fa fa-facebook"></i>
					</a>
				</span>
				<span class="login-social-twitter">
					<a href="#twitterAuth"> 
						<i class="fa fa-twitter"></i>
					</a>
				</span>
				<span class="login-social-github">
					<a href="#githubAuth">
						<i class="fa fa-github"></i>
					</a>
				</span>
				<span class="login-social-google">
					<a href="#googleAuth">
						<i class="fa fa-google-plus"></i>
					</a>
				</span>
				<span class="login-social-warwick">
					<a href="#warwickAuth">
						<i class="icon-warwick"></i>
					</a>
				</span>
			</div>
			<hr>
			<h2>Or Email:</h2>
			<div class="login-form">
				<form>
					<div class="login-form-input">
						<input type="email" name="email" placeholder="john.doe@gmail.com">
					</div>
					<div class="login-form-input">
						<input type="password" name="password" placeholder="******">
					</div>
					<div class="login-form-error" hidden>
						<p>Your email or password was not recognised. Try again?</p>
					</div>
					<div class="login-form-submit">
						<input type="submit" value="Login">
					</div>
				</form>
				<div class="login-form-link">
					<a href="reset.php">Forgot your password?</a>
				</div>
				<div class="login-form-link">
					<a href="register.php">Need to register?</a>
				</div>
			</div>
		</div>
	</div>
	<?php include "footer.php" ?>
	<script type="text/javascript" src="js/login.js"></script>
</body>

</html>
