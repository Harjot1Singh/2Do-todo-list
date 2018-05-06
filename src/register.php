<?php
include_once "common/access.php";
if (loggedIn())
	header("Location:index.php");
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Register</title>
	<!--Font Awesome-->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" type="text/css" charset="utf-8">
	<!--Our CSS-->
	<link rel="stylesheet" href="css/main.css" type="text/css" charset="utf-8">
	<link rel="stylesheet" href="css/register.css" type="text/css" charset="utf-8">
</head>

<body>
	<div class="container">
		<div class="register">
			<h1>Register</h1>
			<div class="register-form">
				<form>
					<div class="register-form-input">
						<span class="fa-stack">
  							<i class="fa fa-user fa-stack-1x"></i>
						</span>
						<input type="text" name="name" placeholder="Name">
					</div>
					<br>
					<div class="register-form-input">
						<span class="fa-stack">
  							<i class="fa fa-envelope fa-stack-1x"></i>
						</span>
						<input type="email" name="email" placeholder="Email Address">
					</div>
					<div class="register-form-input">
						<span class="fa-stack">
  							<i class="fa fa-envelope fa-stack-1x"></i>
							<i class="fa fa-check fa-stack-2x green"></i>
						</span>
						<input type="text" name="confirmEmail" placeholder="Confirm Email">
					</div>
					<br>
					<div class="register-form-input">
						<span class="fa-stack">
  							<i class="fa fa-lock fa-stack-1x"></i>
						</span>
						<input type="password" name="password" placeholder="Password">
					</div>
					<div class="register-form-input">
						<span class="fa-stack">
  							<i class="fa fa-lock fa-stack-1x"></i>
							<i class="fa fa-check fa-stack-2x green"></i>
						</span>
						<input type="password" name="confirmPassword" placeholder="Confirm Password">
					</div>
					<div class="register-form-error" hidden>
						<p>Your email or password was not recognised. Try again?</p>
					</div>
					<div class="register-form-submit">
						<input type="submit" value="Register!">
					</div>
					<div class="register-form-link">
						<a href="login.php">Already have an account?</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php include "footer.php" ?>
	<script type="text/javascript" src="js/register.js"></script>
</body>
</html>