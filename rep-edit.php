<?php session_start();

if(isset($_GET['r'])) {
	$rep = $_GET['r'];
} else {
	header("Location:rep-reg.php");
}

if(isset($_COOKIE['user'])) {
	$userid = explode("_", $_COOKIE['user']);
	$userid = $userid[0];
} else {
	header("Location:Home");
}

require_once("config/general_db.php");

if(isset($_POST['office'])) {
	foreach($_POST as $key => $value) {
		${$key} = $value;
	}
	
	if($stmt = mysqli_prepare($dbcg,
		"UPDATE representative SET 
		officeid = ?,
		name = ?,
		position = ?,
		email = ?,
		num = ?
		WHERE id = ?")) {
		
	    mysqli_stmt_bind_param($stmt,
	    	"issssi",
	   		$office,
	   		$name,
	   		$position,
	   		$email,
	   		$num,
	   		$rep);
		
	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_close($stmt);
	}
	
	header("Location:rep-reg.php");
}

if($stmt = mysqli_prepare($dbcg, "SELECT id, offname FROM offices WHERE busid = ?")) {
	mysqli_stmt_bind_param($stmt, "i", $userid);
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
	  	$offices[$x['id']] = $x;
	}
	
	$parameters = "";
	
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

if($stmt = mysqli_prepare($dbcg, "SELECT * FROM representative WHERE busid = ? AND id = ?")) {
	mysqli_stmt_bind_param($stmt, "ii", $userid, $rep);
	mysqli_stmt_execute($stmt);
	$meta = $stmt->result_metadata();
	
    while ($field = $meta->fetch_field()) {
	  	$parameters[] = &$row[$field->name];
	}
	
	call_user_func_array(array($stmt, 'bind_result'), $parameters);
	
	while ($stmt->fetch()) {
	  	foreach($row as $key => $val) {
	    	$reps[$key] = $val;
	  	}
	}
	
	$parameters = "";
	
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
} print_r($reps); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8"/>
		<title>Representative Edit</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require_once("config/main-css.php"); ?>
		
		<!-- Slider js and css -->
		<link rel="stylesheet" type="text/css" href="css/rep-edit.css" />
	</head>
		
	<body>
		<div id="outerWrapper">
			<div id="wrapper">
				<!--- NAV ----->
				<?php require_once("parts/nav/upper-nav/upper-nav.php");
					  require_once("parts/nav/lower-nav/lower-nav.php"); ?>
				<!--- NAV CLOSED ----->
				
				<form id="formWrapper" action="rep-edit.php?r=<?php echo $rep; ?>" method="post">
					<h1>Representative Creation</h1>
					
					<div class="inputContainer">
						<h2>Basic Information</h2>
						<div class="inputRow"><label for="office">Office connected to:</label>
							<select name="office">
								<?php foreach ($offices as $key => $value) { ?>
									<option value="<?php echo $value['id']; ?>" <?php if($value['id'] == $reps['officeid']) { echo "selected"; } ?>><?php echo $value['offname']; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="inputRow"><label for="name">Name:</label><input id="name" type="text" name="name" value="<?php echo $reps['name']; ?>" /></div>
						<div class="inputRow"><label for="position">Position:</label><input id="position" type="text" name="position" value="<?php echo $reps['position']; ?>" /></div>
						<div class="inputRow"><label for="email">Email:</label><input id="email" type="email" name="email" value="<?php echo $reps['email']; ?>" /></div>
						<div class="inputRow"><label for="phone">Phone:</label><input id="num" type="text" name="num" value="<?php echo $reps['num']; ?>" /></div>
					</div>
					
					<div id="submitHolder">
						<input id="submit" type="submit" name="submit" />
					</div>
				</form>
				
				<?php require_once("parts/footer/footer.php"); ?>
			</div>
		</div>
	</body>
</html>