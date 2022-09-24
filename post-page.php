<?php if(isset($_GET['a'])) {
	$post = $_GET['a'];
} else {
	//header("Location:Home");
}

require_once("config/general_db.php");

if($stmt = mysqli_prepare($dbcg, "SELECT * FROM posts WHERE id = ? and type = 0")) {
	mysqli_stmt_bind_param($stmt, "i", $post);
	mysqli_stmt_execute($stmt);
	$meta = $stmt->result_metadata();
	
    while ($field = $meta->fetch_field()) {
	  	$parameters[] = &$row[$field->name];
	}
	
	call_user_func_array(array($stmt, 'bind_result'), $parameters);
	
	while ($stmt->fetch()) {
	  	foreach($row as $key => $val) {
	    	$postsArray[$key] = $val;
	  	}
	}
	
	$parameters = "";
	$row = "";
	$x = "";
	
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

if($stmt = mysqli_prepare($dbcg, "SELECT * FROM postinfo WHERE post_id = ?")) {
	mysqli_stmt_bind_param($stmt, "i", $post);
	mysqli_stmt_execute($stmt);
	$meta = $stmt->result_metadata();
	
    while ($field = $meta->fetch_field()) {
	  	$parameters[] = &$row[$field->name];
	}
	
	call_user_func_array(array($stmt, 'bind_result'), $parameters);
	
	while ($stmt->fetch()) {
	  	foreach($row as $key => $val) {
	    	$postInfoArray[$key] = $val;
	  	}
	}
	
	$parameters = "";
	$row = "";
	$x = "";
	
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
} 

$postInfo = array_merge($postsArray, $postInfoArray); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8"/>
		<title>Posts Page</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require_once("config/main-css.php"); ?>
		
		<!-- Slider js and css -->
		<link rel="stylesheet" type="text/css" href="css/post-page.css" />
		<link rel="stylesheet" type="text/css" href="parts/sliders/responsiveslides/responsiveslides-alt.css" />
		<script src="js/responsiveslides.min.js"></script>
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
							<h1><?php echo $postInfo['title']; ?></h1>
						</div>
						<?php require_once("parts/sliders/post-page/post-page.php"); ?>
						<div id="item-information">
							<div id="item-info">
								<div class="post-info item-info">
									<p><?php echo $postInfo['price']; ?></p>
								</div>
								<p><?php echo $postInfo['blurb']; ?></p>
							</div>
							<div id="contact-info">
								<div class="post-info">
									<p>Take-Off</p>
								</div>
								<div class="post-info">
									<p><?php echo $postInfo['contact']; ?></p>
								</div>
								<div class="post-info">
									<p><?php echo $postInfo['email']; ?></p>
								</div>
								<div class="post-info">
									<p><?php echo $postInfo['country']; ?></p>
								</div>
							</div>
						</div>
						<div id="content-text">
							<?php echo $postInfo['content']; ?>
						</div>
					</div>
				</div>
				<?php require_once("parts/footer/footer.php"); ?>
			</div>
		</div>
	</body>
</html>