<?php 
// Only serve if logged in
include_once "common/access.php";
if (!loggedIn())
	header("Location:login.php");
	var_dump($_SESSION["user"]);
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
		<?php
		$user = $_SESSION["user"];
		$lists = $user->getLists();
		?>
		<script>
			window.lists = {};
			window.userID = <?php echo $user->getUserID(); ?>;
		</script>
			<!--Example List-->
			<ul>
				<!--PHP/JS inserts list item here-->
				<?php
					foreach ($lists as $list) {
				        $name = $list->getName();
				?>
				<script>
				var js_data = "<?php echo json_encode($list); ?>";
				var js_obj_data = JSON.parse(js_data);
					window.lists[<?php echo $list->getListID() ?>] = js_obj_data;
				</script>
				
				<li>
					<div class="lists-list">
						<div class="lists-list-title">
							<span class="lists-list-title-heading">
								<h2><?php echo $name ?> </h2>
							</span>
							<input hidden type="text" placeholder="List Name" name="name">
							<div class="lists-list-actions">
								<span class="lists-list-actions-add">
								<a href=""><i class="fa fa-plus fa-2x"></i></i></a>
							</span>
								<span class="lists-list-actions-share">
								<a href=""><i class="fa fa-share fa-2x"></i></a>
							</span>
								<span class="lists-list-actions-sort">
								<a href=""><i class="fa fa-sort fa-2x"></i></a>
							</span>
								<!--Share Dialog - Separate -->
								<div class="lists-list-actions-share-dialog" hidden>
									<h4>Currently shared with:</h4>
									<div class="lists-list-actions-share-dialog-people">
										<table>
											<tr>
												<td>Harjot Singh</td>
												<td>H.Singh.4@warwick.ac.uk</td>
												<td>
													<span class="lists-list-actions-share-dialog-person-delete">
													<a href="">Delete</a>
												</span>
												</td>
											</tr>
										</table>
										<div class="lists-list-actions-share-dialog-add">
											<input type="email" name="email">
											<a href="">Add</a>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="lists-list-items">
							<ul>
							<?php 
								$items = $list->getDueItems();
								foreach ($items as $item) {
								$properties = $item->getItem();
							?>
								<li>
									<div class="lists-list-items-item">
										<h3><?php echo $properties["name"] ?></h3>
										<div class="lists-list-items-item-actions">
											<span class="lists-list-items-item-actions-due">
												<a href=""><i class="fa fa-calender"></i></a>
												<input type="date" name="due">
											</span>
											<span class="lists-list-items-item-actions-location">
												<a href=""><i class="fa fa-map-marker"></i></a>
											</span>
											<span class="lists-list-items-item-actions-priority">
												<a href=""><i class="fa fa-star"></i></a>
											</span>
										</div>
									</div>
								</li>
								
								<?php 
									}
								?>
							</ul>
						</div>
						<div class="lists-list-completed">
							<div class="lists-list-completed-bar">
								<a href="">Show Completed Items</a>
							</div>
							<ul hidden>
							<?php 
								$items = $list->getCompletedItems();
								foreach ($items as $item) {
								$properties = $item->getItem();
							?>
								<li>
									<div class="lists-list-items-item">
										<h3><?php echo $properties["name"] ?></h3>
										<div class="lists-list-items-item-actions">
											<span class="lists-list-items-item-actions-due">
												<a href=""><i class="fa fa-calender"></i></a>
												<input type="date" name="due">
											</span>
											<span class="lists-list-items-item-actions-location">
												<a href=""><i class="fa fa-map-marker"></i></a>
											</span>
											<span class="lists-list-items-item-actions-priority">
												<a href=""><i class="fa fa-star"></i></a>
											</span>
										</div>
									</div>
								</li>
								
								<?php 
									}
								?>
							</ul>
						</div>
					</div>
				</li>
			
				
					<?php
					}
				?>
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
