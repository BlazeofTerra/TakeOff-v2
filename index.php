<?php require_once("config/general_db.php");
require_once("parts/posts/post-card.php");

$date = date("Ymdhms");
$statement = "SELECT * FROM posts WHERE date_expired > ? AND deleted = 0 ORDER BY date_post DESC LIMIT 15";

if($stmt = mysqli_prepare($dbcg, $statement)) {
	mysqli_stmt_bind_param($stmt, "s", $date);
	
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
		<title>Home</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require_once("config/main-css.php"); ?>
		
		<!-- Slider js and css -->
		<link rel="stylesheet" type="text/css" href="parts/posts/post-card.css" />
		<link rel="stylesheet" type="text/css" href="parts/sliders/responsiveslides/responsiveslides-index.css" />
		<script src="js/responsiveslides.min.js"></script>
	</head>
		
	<body>
		<div id="outerWrapper">
			<div id="wrapper">
				<!--- NAV ----->
				<?php require_once("parts/nav/upper-nav/upper-nav.php");
					  require_once("parts/nav/lower-nav/lower-nav.php"); ?>
				<!--- NAV CLOSED ----->
				<?php require_once("parts/sliders/index/index-slider.php"); ?>
				
				<div class="Post-Container">
					<div class="Post-Wrapper">
						<?php $counter = 1;
						if(isset($posts)) {
							foreach($posts as $key => $value) {
								if(isset($value['title'])) {
									itemPost($counter, $value['title'], $value['pic'], $value['blurb'], $value['cur'], $value['price'], $value['userid']."_".$value['user_type'], $value['location']);
								} else {
									businessPost($counter, $value['business_name'], $value['image_1'], $value['userid']."_1", $value['address'], $value['website']);
								}
								
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