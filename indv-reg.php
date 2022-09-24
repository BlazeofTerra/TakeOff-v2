<?php if(isset($_POST['name'])) {
	require_once("config/account_db.php");
	
	foreach($_POST as $key => $value) {
		${$key} = $value;
	}
	
	$type = 2;
	$password = password_hash($password, PASSWORD_DEFAULT);
	$email_code = md5($email + microtime());
	$active = 1;

	do {
		$mask = rand(10000000,99999999);
	
		$maskCheckQ = "SELECT COUNT(mask) FROM accounts WHERE mask = '$mask'";
		$maskCheckR = mysqli_query($dbca, $maskCheckQ);
		$maskCheckAnswer = mysqli_fetch_assoc($maskCheckR);
		
		if($maskCheckAnswer['COUNT(mask)'] == 0) {
			break;
		}
	} while(1);
	
	if($stmt = mysqli_prepare($dbca, "INSERT INTO accounts (mask, name, contact, email, password, type, email_code, active) 
													VALUES (?, ?, ?, ?, ?, ?, ?, ?)")) {
	
	    mysqli_stmt_bind_param($stmt, "issssisi", $mask, $name, $contactNumber, $email, $password, $type, $email_code, $active);
		
	    mysqli_stmt_execute($stmt);
		
	    mysqli_stmt_close($stmt);
	}
	
	//header("Location:Home");
} ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8"/>
		<title>Individual Registration</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require_once("config/main-css.php"); ?>
		
		<!-- Slider js and css -->
		<link rel="stylesheet" type="text/css" href="css/indv-reg.css" />
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
				
				function errorManagerAdv(errorId, setting, type) {
					if(errorId == "rePassword") {
						errorId = "password";
					}
					
					var errorId = errorId + type + "Error";
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
				
				$("#email").focusout(function() {
					var email = $("#email").val().trim(),
						errorId = this.id;
					
					if(email !== "") {
						errorManagerBasic(errorId, "hide");
						
						if(emailCheck.test(email)) {
							errorManagerAdv(errorId, "hide", "Valid");
						} else {
							errorManagerAdv(errorId, "show", "Valid");
						}
						
						$.post("ajax/email-check.php", {email: email})
						.done(function(data) {
							if(data == 1) {
								errorManagerAdv(errorId, "show", "Taken");
							} else {
								errorManagerAdv(errorId, "hide", "Taken");
							}
						});
					} else {
						errorManagerBasic(errorId, "show");
					}
				});
				
				$(".passwords").focusout(function() {
					var password = $("#password").val().trim(),
			        	rePassword = $("#rePassword").val().trim(),
			        	length = password.length,
			        	errorId = this.id;
					
					if(password !== "" && rePassword !== "") {
				        if(password == rePassword) {
				        	errorManagerAdv(errorId, "hide", "Miss");
				        } else {
				        	errorManagerAdv(errorId, "show", "Miss");
				        }
					}
					
					if(password !== "" && length < 8) {
						errorManagerAdv(errorId, "show", "Short");
					} else if(password !== "" && length >= 8) {
						errorManagerAdv(errorId, "hide", "Short");
					}
				});
				
				$("#submitButton").click(function(e) {
					var checkNumber = 0;
					var name = $("#name").val().trim();
					var email = $("#email").val().trim();
					var password = $("#password").val().trim(),
			        	rePassword = $("#rePassword").val().trim(),
			        	length = password.length;
					
					if(name == "") {
						$("#name").show();
						checkNumber++;
					}
					
					if(email !== "") {
						if(emailCheck.test(email)) {} else {
							$("#emailValidError").show();
							checkNumber++;
						}
						
						$.post("ajax/email-check.php", {email: email})
						.done(function(data) {
							if(data == 1) {
								$("#emailTakenError").show();
								checkNumber++;
							}
						});
					} else {
						$("#emailError").show();
						checkNumber++;
					}
					
					if(password !== "" && rePassword !== "") {
				        if(password != rePassword) {
				        	$("#passwordMissError").show();
							checkNumber++;
				        }
					} else {
						$("#passwordError").show();
						checkNumber++;
					}
					
					if(password !== "" && length < 8) {
						$("#passwordShortError").show();
						checkNumber++;
					}
					
					if(!$("#terms").is(":checked")) {
						$("#termsError").show();
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
				
				<form id="formWrapper" action="indv-reg.php" method="post">
					<h1>Individual Registration</h1>
					
					<div id="errorHolder">
						<p id="nameError" style="display: none;">Your name is required</p>
						<p id="emailError" style="display: none;">Your email is required</p>
						<p id="emailValidError" style="display: none;">Your email is not valid</p>
						<p id="emailTakenError" style="display: none;">This email is already taken</p>
						<p id="passwordError" style="display: none;">A password is required</p>
						<p id="passwordMissError" style="display: none;">Your passwords do not match</p>
						<p id="passwordShortError" style="display: none;">Your password needs to be more than 8 characters long</p>
						<p id="termsError" style="display: none;">You need to accept the terms and conditions to continue</p>
					</div>
					
					<div class="inputContainer">
						<h2>Basic Information</h2>
						<div class="inputRow"><label for="name">Name:</label><input id="name" type="text" name="name" /></div>
						<div class="inputRow"><label for="email">Email:</label><input id="email" type="email" name="email" /></div>
						<div class="inputRow"><label for="contactNumber">Contact Number:</label><input id="contactNumber" type="text" name="contactNumber" /></div>
						<div class="inputRow"><label for="password">Password:</label><input id="password" type="password" name="password" /></div>
						<div class="inputRow"><label for="rePassword">Retype password:</label><input id="rePassword" type="password" name="rePassword" /></div>
					</div>
					
					<div id="termsHolder">
						<input id="terms" type="checkbox" name="terms" />
						<span>I agree to the terms and conditions.</span>
					</div>
					
					<div id="submitHolder">
						<button id="submitButton">Register</button>
					</div>
				</form>
				
				<?php require_once("parts/footer/footer.php"); ?>
			</div>
		</div>
	</body>
</html>