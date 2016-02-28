<?php 
include_once "common/access.php";
if (!loggedIn())
	header("Location:login.php");
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Login</title>
	<!--Font Awesome-->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" type="text/css" charset="utf-8">
	<!--Our CSS-->
	<link rel="stylesheet" href="css/main.css" type="text/css" charset="utf-8">
	<link rel="stylesheet" href="css/profile.css" type="text/css" charset="utf-8">
</head>

<body>
	<div class="container">
		<?php include "nav.php" ?>
		<div class="profile">
			<h1>Your Profile</h1>
			<div class="profile-social">
				<h2>Link your social media accounts</h2>
				<span class="profile-social-facebook">
					<a href="#facebookAuth">
						<i class="fa fa-facebook"></i>
					</a>
				</span>
				<span class="profile-social-twitter">
					<a href="#twitterAuth">
						<i class="fa fa-twitter"></i>
					</a>
				</span>
				<span class="profile-social-github">
					<a href="#githubAuth">
						<i class="fa fa-github"></i>
					</a>
				</span>
				<span class="profile-social-google">
					<a href="#googleAuth">
						<i class="fa fa-google-plus"></i>
					</a>
				</span>
				<span class="profile-social-warwick">
					<a href="#warwickAuth">
						<i class="icon-warwick"></i>
					</a>
				</span>
			</div>
			<hr>
			<div class="profile-resetPassword">
				<a href="#resetPassword">Reset your password</a>
			</div>
			<div class="profile-background">
				Select background image:
				<div class="profile-background-chooseFile">
					<input type="file" name="background" id="background">
				</div>
				<div class="profile-background-submit">
					<input type="submit" value="Upload Image" name="submit">
				</div>
			</div>
			<hr>
			<div class="profile-mobile">
				<h2>Verify phone number</h2>
				<div class="profile-mobile-text">
					Mobile Number:
					<input type="text" name="number">
				</div>
				<div class="profile-mobile-verify">
					<a href="#verify">Verify </a>
				</div>
			</div>
		</div>
	</div>
	<?php include "footer.php" ?>
</body>

</html>