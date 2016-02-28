<?php
include_once "common/access.php";
if (!loggedIn())
	header("Location:login.php");
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Map</title>
	<!--Font Awesome-->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" type="text/css" charset="utf-8">
	<!--Our CSS-->
	<link rel="stylesheet" href="css/main.css" type="text/css" charset="utf-8">
</head>

<body>
	<div class="container">
		<nav class="navigation">
			<ul>
				<li class="navigation-active">
					<a href="index.php">Lists</a>
				</li>
				<li>
					<a href="map.php">Map</a>
				</li>
				<li>
					<a href="about.html">About</a>
				</li>
				<li>
					<div class="navigation-dropdown">
						<a href="#">&dArr; Profile </a>
						<div class="navigation-dropdown-content">
							<a href="profile.php">Settings</a>
							<a href="">Logout</a>
						</div>
					</div>
				</li>
			</ul>
		</nav>
		<div class="map">
			<h1>Map</h1>
			<div class="map-about">
				Check out where you created your todos.
			</div>
			<div class="map-continer">
				<!--Google Maps-->
			</div>
		</div>
	</div>
		<?php include "footer.php" ?>
</body>

</html>
