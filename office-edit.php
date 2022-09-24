<?php session_start();

require_once("config/general_db.php");

if(isset($_GET['o'])) {
	$office = $_GET['o'];
} else {
	header("Location:office-reg.php");
}

if($stmt = mysqli_prepare($dbcg, "SELECT id, label, name FROM countries ORDER BY name")) {
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
	  	$countries[$x['name']] = $x;
	}
	
	$parameters = "";
	
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

if(isset($_POST['offname'])) {
	if($stmt = mysqli_prepare($dbcg,
		"UPDATE offices SET 
		offname = ?,
		phone = ?,
		fax = ?,
		address = ?,
		pobox = ?,
		country = ?
		WHERE id = ?")) {
		
	    mysqli_stmt_bind_param($stmt,
	    	"ssssssi",
	   		$_POST['offname'],
	   		$_POST['phone'],
	   		$_POST['fax'],
	   		$_POST['address'],
	   		$_POST['pobox'],
	   		$_POST['country'],
			$office);
		
	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_close($stmt);
	}
	
	header("Location:office-reg.php");
}

if($stmt = mysqli_prepare($dbcg, "SELECT * FROM offices WHERE id = ?")) {
	mysqli_stmt_bind_param($stmt, "i", $office);
	mysqli_stmt_execute($stmt);
	$meta = $stmt->result_metadata();
	
    while ($field = $meta->fetch_field()) {
	  	$parameters[] = &$row[$field->name];
	}
	
	call_user_func_array(array($stmt, 'bind_result'), $parameters);
	
	while ($stmt->fetch()) {
	  	foreach($row as $key => $val) {
	    	$officeInfo[$key] = $val;
	  	}
	}
	
	$parameters = "";
	
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
} 

foreach($officeInfo as $key => $value) {
	${$key} = $value;
} ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8"/>
		<title>Office Edit</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require_once("config/main-css.php"); ?>
		
		<!-- Slider js and css -->
		<link rel="stylesheet" type="text/css" href="css/office-reg.css" />
	</head>
		
	<body>
		<div id="outerWrapper">
			<div id="wrapper">
				<!--- NAV ----->
				<?php require_once("parts/nav/upper-nav/upper-nav.php");
					  require_once("parts/nav/lower-nav/lower-nav.php"); ?>
				<!--- NAV CLOSED ----->
				
				<form id="formWrapper" action="office-edit.php?o=<?php echo $office; ?>" method="post">
					<h1>Office Edit</h1>
					
					<div class="inputContainer">
						<h2>Office Information Information</h2>
						<div class="inputRow"><label for="firstName">Office Name:</label><input id="offname" type="text" name="offname" value="<?php echo $offname; ?>" /></div>
						<div class="inputRow"><label for="firstName">Country:</label>
							<select id="countrieSort" name="country">
								<?php foreach($countries as $key => $value) { ?>
									<option value="<?php echo $value['name']; ?>" <?php if($country == $value['name']) { echo "selected"; } ?>><?php echo $value['name']; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="inputRow"><label for="contactNumber">Phone Number:</label><input id="phone" type="text" name="phone" value="<?php echo $phone; ?>" /></div>
						<div class="inputRow"><label for="email">Fax:</label><input id="fax" type="text" name="fax" value="<?php echo $fax; ?>" /></div>
						<div class="inputRow"><label for="password">Physical Address:</label><input id="address" type="text" name="address" value="<?php echo $address; ?>" /></div>
						<div class="inputRow"><label for="rePassword">Post Code:</label><input id="pobox" type="text" name="pobox" value="<?php echo $pobox; ?>" /></div>
					</div>
					
					<div id="submitHolder">
						<input id="submit" type="submit" name="Edit" />
					</div>
				</form>
				
				
				<?php require_once("parts/footer/footer.php"); ?>
			</div>
		</div>
	</body>
</html>