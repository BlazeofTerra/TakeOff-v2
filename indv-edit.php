<?php require_once("config/account_db.php");
$userid = 3;

if(isset($_POST['name'])) {
	$name = $_POST['name'];
	$contact = $_POST['contactNumber'];
	
	if($stmt = mysqli_prepare($dbca, "UPDATE accounts SET name = ?, contact = ? WHERE id = ?")) {
	
	    mysqli_stmt_bind_param($stmt, "ssi", $name, $contact, $userid);
		
	    mysqli_stmt_execute($stmt);
		
	    mysqli_stmt_close($stmt);
	}
	
	header("Location:Home");
}

if($stmt = mysqli_prepare($dbca, "SELECT id, name, contact FROM accounts WHERE id = ?")) {
    mysqli_stmt_bind_param($stmt, "i", $userid);
	mysqli_stmt_execute($stmt);
	$meta = $stmt->result_metadata();
	
    while ($field = $meta->fetch_field()) {
	  	$parameters[] = &$row[$field->name];
	}
	
	call_user_func_array(array($stmt, 'bind_result'), $parameters);
	
	while ($stmt->fetch()) {
	  	foreach($row as $key => $val) {
	    	$account_info[$key] = $val;
	  	}
	}
	
	$parameters = "";
	
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
} ?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8"/>
		<title>Individual Edit</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require_once("config/main-css.php"); ?>
		
		<!-- Slider js and css -->
		<link rel="stylesheet" type="text/css" href="css/indv-edit.css" />
		<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
		<script>
			$(document).ready(function() {
				var emailCheck = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
				
				function errorManagerBasic(errorId, setting) {
					var errorId = errorId + "Error";
					
				     if(setting == "hide") {
				     	$("#" + errorId).hide();
				     } else if(setting == "show") {
				     	$("#" + errorId).show();
				     }
				}
				
				$("#name").focusout(function() {
					var name = $("#name").val().trim(),
						errorId = this.id;
					
					if(name !== "") {
						errorManagerBasic(errorId, "hide");
					} else {
						errorManagerBasic(errorId, "show");
					}
				});
				
				$("#submitButton").click(function(e) {
					var checkNumber = 0;
					var name = $("#name").val().trim();
					
					if(name == "") {
						$("#name").show();
						checkNumber++;
					}
					if(checkNumber != 0) {
						e.preventDefault();
						$('html, body').animate({scrollTop: '0px'}, 300);
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
				
				<form id="formWrapper" action="indv-edit.php" method="post">
					<h1>Business Registration</h1>
					
					<div id="errorHolder">
						<p id="nameError" style="display: none;">Your name is required</p>
					</div>
					
					<div class="inputContainer">
						<h2>Basic Information</h2>
						<div class="inputRow"><label for="name">Name:</label><input id="name" type="text" name="name" value="<?php echo $account_info['name']; ?>" /></div>
						<div class="inputRow"><label for="contactNumber">Contact Number:</label><input id="contactNumber" type="text" name="contactNumber" value="<?php echo $account_info['contact']; ?>" /></div>
					</div>
					
					<div id="submitHolder">
						<button id="submitButton">Change</button>
					</div>
				</form>
				
				<?php require_once("parts/footer/footer.php"); ?>
			</div>
		</div>
	</body>
</html>