<?php require_once("config/general_db.php");
require_once("parts/posts/post-card.php");

if(!isset($_COOKIE['user'])) {
	header("Location:Home");
} else {
	$user = $_COOKIE['user'];
}

$user = explode("_", $user);

$user = $user[0];

$date = date("Ymdhms");
$statement = "SELECT * FROM posts WHERE deleted = 0 AND userid = ? AND type = 0 ORDER BY date_post DESC";

if($stmt = mysqli_prepare($dbcg, $statement)) {
	mysqli_stmt_bind_param($stmt, "i", $user);
	
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
} ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8"/>
		<title>My Posts</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require_once("config/main-css.php"); ?>
		
		<!-- Slider js and css -->
		<link rel="stylesheet" type="text/css" href="parts/posts/post-card.css" />
		<script>
			$(document).ready(function() {
				$(".postDelete").click(function() {
					var id = this.id;
					
					if(confirm('Are you sure?')) {
				        $.post("ajax/post-delete.php", {id: id})
						.done(function(data) {
							alert(data);
						});
				    }
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
				
				<div class="Post-Container">
					<div class="Post-Wrapper">
						<?php $counter = 1;
						if(isset($posts)) {
							foreach($posts as $key => $value) {
								editPost($counter, $value['title'], $value['pic'], $value['blurb'], $value['cur'], $value['price'], $value['id']."_".$value['user_type'], $value['location']);
								
								$counter++;
								global $counter;
							}
						} ?>
					</div>	
				</div>
				
				<?php require_once("parts/footer/footer.php"); ?>
			</div>
		</div>
	</body>
</html>