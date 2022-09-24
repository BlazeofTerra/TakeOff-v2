<?php if(isset($_GET['a'])) {
	$user = $_GET['a'];
} else {
	//header("Location:Home");
}

$user = explode("_", $user);

$user = $user[0];

require_once("config/general_db.php");
require_once("parts/posts/post-card.php");

if($stmt = mysqli_prepare($dbcg, "SELECT * FROM posts WHERE userid = ? AND type = 1")) {
	mysqli_stmt_bind_param($stmt, "i", $user);
	mysqli_stmt_execute($stmt);
	$meta = $stmt->result_metadata();
	
    while ($field = $meta->fetch_field()) {
	  	$parameters[] = &$row[$field->name];
	}
	
	call_user_func_array(array($stmt, 'bind_result'), $parameters);
	
	while ($stmt->fetch()) {
	  	foreach($row as $key => $val) {
	    	$busInfo[$key] = $val;
	  	}
	}
	
	$parameters = "";
	$row = "";
	$x = "";

    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

$date = date("Ymdhms");
$statement = "SELECT * FROM posts WHERE date_expired > ? AND deleted = 0 AND userid = ? AND type = 0";

if($stmt = mysqli_prepare($dbcg, $statement)) {
	mysqli_stmt_bind_param($stmt, "si", $date,$user);
	
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

if($stmt = mysqli_prepare($dbcg, "SELECT COUNT(*) FROM offices WHERE busid = ?")) {
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
	  	$officesCount = $x['COUNT(*)'];
	}
	
	$parameters = "";
	
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

if($officesCount != 0) {
	if($stmt = mysqli_prepare($dbcg, "SELECT * FROM offices WHERE busid = ?")) {
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
		  	$offices[$x['offname']] = $x;
		}
		
		$parameters = "";
		
	    mysqli_stmt_fetch($stmt);
	    mysqli_stmt_close($stmt);
	}
} ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8"/>
		<title>Business Page</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require_once("config/main-css.php"); ?>
		
		<!-- Slider js and css -->
		<link rel="stylesheet" type="text/css" href="parts/posts/post-card.css" />
		<link rel="stylesheet" type="text/css" href="css/business-page.css" />
		<link rel="stylesheet" type="text/css" href="parts/sliders/responsiveslides/responsiveslides-alt.css" />
		<script src="js/responsiveslides.min.js"></script>
		<script>
			$(document).ready(function() {
			    $(".office-click-me").click(function() {
			    	var office = $("#" + this.id + "-content");
			    	
			    	if(office.hasClass("office-show")) {
			    		office.removeClass("office-show");
			    	} else {
			    		office.addClass("office-show");
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
				<div id="post-back">
					<div id="post-info">
						<div id="name-item">
							<div id="logoHolder">
								<?php $aspect = substr($busInfo['image_1'], 0);
		
								if($aspect[0] == 0) {
									$aspect = "imageHeight";
								} elseif($aspect[0] == 1) {
									$aspect = "imageWidth";
								}  ?>
								<img class="<?php echo $aspect; ?>" src="image/user/<?php echo $busInfo['image_1']; ?>" alt="">
							</div>
							
							<div id="nameHolder">
								<h1><?php echo $busInfo['business_name']; ?></h1>
							</div>
							
						</div>
						<?php require_once("parts/sliders/business-page/business-page.php"); ?>
						<div id="item-information">
							<h2 id="basic-info">Basic contact information</h2>
							<p class="basic-info-contact"><?php echo $busInfo['contact']; ?></p>
							<p class="basic-info-contact"><?php echo $busInfo['email']; ?></p>
							<p class="basic-info-contact"><?php echo $busInfo['country']; ?></p>
							<a href="<?php echo $busInfo['website']; ?>"><p class="basic-info-contact">Our website</p></a>
							<div id="offices">
								<?php if($officesCount != 0) {
									foreach($offices as $key => $value) {
										if($stmt = mysqli_prepare($dbcg, "SELECT * FROM representative WHERE busid = ? AND officeid = ?")) {
											mysqli_stmt_bind_param($stmt, "ii", $user, $value['id']);
											mysqli_stmt_execute($stmt);
											$meta = $stmt->result_metadata();
											
										    while ($field = $meta->fetch_field()) {
											  	$parameters[] = &$row[$field->name];
											}
											
											call_user_func_array(array($stmt, 'bind_result'), $parameters);
											
											$reps = "";
											
											while ($stmt->fetch()) {
											  	foreach($row as $key => $val) {
											    	$x[$key] = $val;
											  	}
											  	$reps[$x['id']] = $x;
											}
											
											$parameters = "";
											
										    mysqli_stmt_fetch($stmt);
										    mysqli_stmt_close($stmt);
										} ?>
										<div class="office">
											<h3 id="office-<?php echo $value['id']; ?>" class="office-click-me"><?php echo $value['offname']; ?></h3>
											<div id="office-<?php echo $value['id']; ?>-content" class="office-content">
												<p class="basic-info-contact"><?php echo $value['country']; ?></p>
												<p class="basic-info-contact"><?php echo $value['phone']; ?></p>
												<p class="basic-info-contact"><?php echo $value['fax']; ?></p>
												<p class="basic-info-contact"><?php echo $value['address']; ?></p>
												<p class="basic-info-contact"><?php echo $value['pobox']; ?></p>
												<div class="reps">
													<?php foreach($reps as $key => $value) { ?>
														<div class="rep">
															<p><?php echo $value['name']; ?></p>
															<p><?php echo $value['position']; ?></p>
															<p><?php echo $value['email']; ?></p>
															<p><?php echo $value['num']; ?></p>
														</div>
													<?php } ?>
												</div>
											</div>
											
										</div>
									<?php }
								} ?>
							</div>
						</div>
						<div id="content-text">
							<?php echo $busInfo['content']; ?>
						</div>
					</div>
				</div>
				
				<div class="Post-Container">
					<div class="Post-Wrapper">
						<?php if(isset($posts)) {
							foreach($posts as $key => $value) {
								itemPost($counter, $value['title'], $value['pic'], $value['blurb'], $value['cur'], $value['price'], $value['id']."_".$value['userid']."_".$value['user_type'], $value['location']);
								
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