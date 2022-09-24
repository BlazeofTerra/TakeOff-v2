<?php session_start();

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
	
	if($stmt = mysqli_prepare($dbcg, "INSERT INTO representative (busid, officeid, name, position, email, num) 
														VALUES (?, ?, ?, ?, ?, ?)")) {
	
	    mysqli_stmt_bind_param($stmt, "iissss", $userid, $office, $name, $position, $email, $phone);
		
	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_close($stmt);
	}
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

if($stmt = mysqli_prepare($dbcg, "SELECT * FROM representative WHERE busid = ?")) {
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
	  	$reps[$x['id']] = $x;
	}
	
	$parameters = "";
	
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
} ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8"/>
		<title>Representative Registration</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require_once("config/main-css.php"); ?>
		
		<!-- Slider js and css -->
		<link rel="stylesheet" type="text/css" href="css/rep-reg.css" />
		<script>
			$(document).ready(function() {
			    $(".repDelete").click(function() {
			    	var id = this.id,
			    		userid = "<?php echo $userid; ?>";
   					
   					$.post("ajax/rep-delete.php", {id: id, userid: userid})
					.done(function(data) {
						alert(data);
						$("#rep_" + id).remove();
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
				
				<form id="formWrapper" action="rep-reg.php" method="post">
					<h1>Representative Creation</h1>
					
					<div class="inputContainer">
						<h2>Basic Information</h2>
						<div class="inputRow"><label for="office">Office connected to:</label>
							<select name="office">
								<?php foreach ($offices as $key => $value) { ?>
									<option value="<?php echo $value['id']; ?>"><?php echo $value['offname']; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="inputRow"><label for="name">Name:</label><input id="name" type="text" name="name" /></div>
						<div class="inputRow"><label for="position">Position:</label><input id="position" type="text" name="position" /></div>
						<div class="inputRow"><label for="email">Email:</label><input id="email" type="email" name="email" /></div>
						<div class="inputRow"><label for="phone">Phone:</label><input id="phone" type="text" name="phone" /></div>
					</div>
					
					<div id="submitHolder">
						<input id="submit" type="submit" name="submit" />
					</div>
				</form>
				
				<div id="existingReps">
					<h2>Existing Representatives</h2>
						<?php foreach ($offices as $key => $value) {
							$officeId = $value['id']; ?>
							<div class="office">
							<h3><?php echo $value['offname']; ?></h3>
							<?php foreach ($reps as $key => $value) {
								if($value['officeid'] == $officeId) { ?>
									<div id="rep_<?php echo $value['id']; ?>" class="rep">
										<p><?php echo $value['name']; ?></p>
										<p><?php echo $value['position']; ?></p>
										<p><?php echo $value['email']; ?></p>
										<p><?php echo $value['num']; ?></p>
										<a href="rep-edit.php?r=<?php echo $value['id']; ?>"><button>Edit</button></a>
										<button id="<?php echo $value['id']; ?>" class="repDelete">Delete</button>
									</div>
								<?php }
							} ?>
							</div>
						<?php } ?>
				</div>
				
				<?php require_once("parts/footer/footer.php"); ?>
			</div>
		</div>
	</body>
</html>