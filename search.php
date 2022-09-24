<?php require_once("config/general_db.php");

if($stmt = mysqli_prepare($dbcg, "SELECT id, label, number_posts, number_businesses FROM category ORDER BY pos")) {
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
	  	$categories[$x['label']] = $x;
	}
	
	$x = "";
	$row = "";
	$parameters = "";
	
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
} ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8"/>
		<title>Search</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require_once("config/main-css.php"); ?>
		
		<!-- Slider js and css -->
		<link rel="stylesheet" type="text/css" href="css/search.css" />
		<link rel="stylesheet" type="text/css" href="parts/sliders/responsiveslides/responsiveslides-index.css" />
		<script src="js/responsiveslides.min.js"></script>
	</head>
		
	<body>
		<div id="outerWrapper">
			<div id="wrapper">
				<!--- NAV ----->
				<?php require_once("parts/nav/upper-nav/upper-nav.php");
					  require_once("parts/nav/lower-nav/lower-nav.php"); ?>
				
				<form id="search" action="posts.php" method="get">
					<input id="searchIndex" type="text" name="s" />
					<select id="searchDropdown" name="c">
						<option value="all">All</option>
						<?php foreach ($categories as $key => $value) { ?>
							<option value="<?php echo $value['id']; ?>"><?php echo $value['label']; ?></option>
						<?php } ?>
					</select>
					<input id="searchImage" value="" type="submit" />
				</form>
				
				<?php require_once("parts/footer/footer.php"); ?>
			</div>
		</div>
	</body>
</html>