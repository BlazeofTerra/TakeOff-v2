<?php session_start();

require_once("config/general_db.php");

if(isset($_COOKIE['user'])) {
	$userid = explode("_", $_COOKIE['user']);
	$userid = $userid[0];
} else {
	header("Location:Home");
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

if(isset($_POST['offName'])) {
	foreach($_POST as $key => $value) {
		${$key} = $value;
	}
	
	if($stmt = mysqli_prepare($dbcg, "INSERT INTO offices (busid, offname, phone, fax, address, pobox, country) 
													VALUES (?, ?, ?, ?, ?, ?, ?)")) {
	
	    mysqli_stmt_bind_param($stmt, "issssss", $userid, $offName, $phone, $fax, $address, $pobox, $country);
		
	    mysqli_stmt_execute($stmt);
		printf("Error: %d.\n", mysqli_stmt_errno($stmt));
	    mysqli_stmt_close($stmt);
	    
	}
}

if($stmt = mysqli_prepare($dbcg, "SELECT * FROM offices WHERE busid = ?")) {
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
} ?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8"/>
		<title>Office Create</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require_once("config/main-css.php"); ?>
		
		<!-- Slider js and css -->
		<link rel="stylesheet" type="text/css" href="css/office-reg.css" />
		<script src="parts/tinymce/tinymce.min.js"></script>
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
			    
			    $(".officeDelete").click(function() {
			    	var classes = this.className.split(/\s+/),
			    		officeClass = classes[1],
			    		id = classes[1].split("_"),
			    		id = id[1],
			    		userid = "<?php echo $userid; ?>";
   					
   					$.post("ajax/office-delete.php", {id: id, userid: userid})
					.done(function(data) {
						alert(data);
						$("#" + officeClass).remove();
					});
			    	
			    	return false;
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
				
				<form id="formWrapper" action="office-reg.php" method="post">
					<h1>Office Creation</h1>
					
					<div class="inputContainer">
						<h2>Office Information Information</h2>
						<div class="inputRow"><label for="firstName">Office Name:</label><input id="offName" type="text" name="offName" /></div>
						<div class="inputRow"><label for="firstName">Country:</label>
							<select id="countrieSort" name="country">
								<?php foreach($countries as $key => $value) { ?>
									<option value="<?php echo $value['name']; ?>"><?php echo $value['name']; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="inputRow"><label for="contactNumber">Phone Number:</label><input id="phone" type="text" name="phone" /></div>
						<div class="inputRow"><label for="email">Fax:</label><input id="fax" type="text" name="fax" /></div>
						<div class="inputRow"><label for="password">Physical Address:</label><input id="address" type="text" name="address" /></div>
						<div class="inputRow"><label for="rePassword">Post Code:</label><input id="postbox" type="text" name="postbox" /></div>
					</div>
					
					<div id="submitHolder">
						<input id="submit" type="submit" name="submit" />
					</div>
				</form>
				
				<h1 id="curOffices">Current Offices</h1>
				<?php foreach ($offices as $key => $value) { ?>
					<div id="office_<?php echo $value['id']; ?>" class="office">
						<h3 id="office-<?php echo $value['id']; ?>" class="office-click-me"><?php echo $value['offname']; ?></h3>
						<div id="office-<?php echo $value['id']; ?>-content" class="office-content">
							<p class="basic-info-contact"><?php echo $value['country']; ?></p>
							<p class="basic-info-contact"><?php echo $value['phone']; ?></p>
							<p class="basic-info-contact"><?php echo $value['fax']; ?></p>
							<p class="basic-info-contact"><?php echo $value['address']; ?></p>
							<p class="basic-info-contact"><?php echo $value['pobox']; ?></p>
							<a href="office-edit.php?o=<?php echo $value['id']; ?>"><button class="editButton">Edit</button></a>
							<a class="officeDelete office_<?php echo $value['id']; ?>"><button class="editButton">Delete</button></a>
						</div>
					</div>
				<?php } ?>
				
				<?php require_once("parts/footer/footer.php"); ?>
			</div>
		</div>
	</body>
</html>