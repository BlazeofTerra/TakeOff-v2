<?php require_once("config/account_db.php");
require_once("config/general_db.php");
require_once("parts/posts/post-card.php");

if(isset($_GET['a'])) {
	$date = date("Ymdhms");
	$type = 0;
	$userType = 2;
	$userId = $_GET['a'];
	
	$countCheck = "SELECT COUNT(*) FROM accounts WHERE id = $userId AND type = 2";
	
	$total = mysqli_query($dbca, $countCheck);
	$total = mysqli_fetch_assoc($total);
	
	$total = $total['COUNT(*)'];
	
	if($total == 1) {
		$postStatment = "SELECT * FROM posts WHERE date_expired > ? AND deleted = 0 AND userid = ? ORDER BY date_post DESC";
		
		if($stmt = mysqli_prepare($dbcg, $postStatment)) {
			mysqli_stmt_bind_param($stmt, "si", $date, $userId);
			
			mysqli_stmt_execute($stmt);
			$meta = $stmt->result_metadata();
			
		    while ($field = $meta->fetch_field()) {
			  	$parameters[] = &$row[$field->name];
			}
			
			call_user_func_array(array($stmt, 'bind_result'), $parameters);
			
			while ($stmt->fetch()) {
			  	foreach($row as $key => $val) {
			    	$x[$key] = $val;
			  	}
			  	$posts[] = $x;
			}
			
			$parameters = "";
			$row = "";
			$x = "";
		    mysqli_stmt_fetch($stmt);
		    mysqli_stmt_close($stmt);
		}
	}
} else {
	//header("Location:Home");
} ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8"/>
		<title>Individual Page</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require_once("config/main-css.php"); ?>
		
		<!-- Slider js and css -->
		<link rel="stylesheet" type="text/css" href="css/indv-page.css" />
		<link rel="stylesheet" type="text/css" href="parts/posts/post-card.css" />
		<script>
			$(document).ready(function() {
				var id = <?php echo $userId; ?>;
				
				$("#indvContact").click(function() {
					var type = "c";
					
					$.post("ajax/indv-info.php", {id: id, type: type})
					.done(function(data) {
						$("#indvContact").replaceWith(data);
					});
				});
				
				$("#indvEmail").click(function() {
					var type = "e";
					
					$.post("ajax/indv-info.php", {id: id, type: type})
					.done(function(data) {
						$("#indvEmail").replaceWith(data);
					});
				});
			});
		</script>
	</head>
		
	<body>
		<div id="outerWrapper">
			<div id="wrapper">
				<!--- NAV ----->
				<?php require_once("parts/nav/upper-nav/upper-nav.php");
					  require_once("parts/nav/lower-nav/lower-nav.php"); ?>
				<!--- NAV CLOSED ----->
				
				<?php if($total == 1) { ?>
					<div id="contactInfo">
						<p>Thomas Hunt</p>
						<p id="indvContact">Click to view contact</p>
						<p id="indvEmail">Click to view email</p>
					</div>
					
					<div class="Post-Container">
						<div class="Post-Wrapper">
							<?php if(isset($posts)) {
								foreach($posts as $key => $value) {
									itemPost($counter, $value['title'], $value['pic'], $value['blurb'], $value['price'], $value['userid']."_".$value['user_type'], $value['location']);
									
									$counter++;
									global $counter;
								}
							} ?>
						</div>	
					</div>
				<?php } else { ?>
					
				<?php } ?>
				
				<?php require_once("parts/footer/footer.php"); ?>
			</div>
		</div>
	</body>
</html>