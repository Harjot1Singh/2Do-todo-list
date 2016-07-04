<?php 
// Only serve if logged in
include_once "common/access.php";
if (!loggedIn())
	header("Location:login.php");
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>2Do</title>
	<!--Font Awesome-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" type="text/css" charset="utf-8">
	<!--Our CSS-->
	<link rel="stylesheet" href="css/main.css" type="text/css" charset="utf-8">
	<link rel="stylesheet" href="css/index.css" type="text/css" charset="utf-8">
</head>

<body>
	<div class="container">
	<?php include "nav.php" ?>
		<div class="lists">
			<ul>
				<!--JS inserts lists and items here, using templates/-->
				<li>
					<div class="lists-new">
						<a href="">
							<i class="fa fa-plus"></i>
						</a>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<?php include "footer.php" ?>
	<script src="js/index.js" type="text/javascript"></script>
</body>

</html>
