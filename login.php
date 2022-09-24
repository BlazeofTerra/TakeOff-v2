<?php 
$passwordError = 0;
$invalidError = 0;

if(isset($_POST['submit'])) {
	require_once("config/account_db.php");
	
	$email = $_POST['email'];
	$password = $_POST['password'];
	
	if($stmt = mysqli_prepare($dbca, "SELECT email FROM accounts WHERE email = ?")) {
	    mysqli_stmt_bind_param($stmt, "s", $email);
	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_store_result($stmt);
	    $emailCheck = mysqli_stmt_num_rows($stmt);
	    mysqli_stmt_close($stmt);
	}
	
	if($emailCheck == 1) {
		if($stmt = mysqli_prepare($dbca, "SELECT id, type, password FROM accounts WHERE email = ?")) {
		    mysqli_stmt_bind_param($stmt, "s", $email);
		    mysqli_stmt_execute($stmt);
			$meta = $stmt->result_metadata();
			
		    while ($field = $meta->fetch_field()) {
			  	$parameters[] = &$row[$field->name];
			}
			
			call_user_func_array(array($stmt, 'bind_result'), $parameters);
			
			while ($stmt->fetch()) {
			  	foreach($row as $key => $val) {
					$accountInfo[$key] = $val;
			  	}
			}
			
		    mysqli_stmt_fetch($stmt);
		    mysqli_stmt_close($stmt);
		}
		
		$parameters = "";
		
		if(password_verify($password, $accountInfo['password'])) {
		    setcookie("user",$accountInfo['id']."_".$accountInfo['type'],time() + (10 * 365 * 24 * 60 * 60));
		    header("Location:Home");
		} else {
			$passwordError = 1;
		}
	} else {
		$invalidError = 1;
	}
} ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8"/>
		<title>Login</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require_once("config/main-css.php"); ?>
		
		<!-- Slider js and css -->
		<link rel="stylesheet" type="text/css" href="css/login.css" />
		<?php if($passwordError == 1) { ?>
			<script>
				alert("Invalid password.");
			</script>
		<?php }
		
		if($invalidError == 1) { ?>
			<script>
				alert("Invalid email or password.");
			</script>
		<?php } ?>
	</head>
		
	<body>
		<div id="outerWrapper">
			<div id="wrapper">
				<!--- NAV ----->
				<?php require_once("parts/nav/upper-nav/upper-nav.php");
					  require_once("parts/nav/lower-nav/lower-nav.php"); ?>
				<!--- NAV CLOSED ----->
				
				
			<!--	<form id="loginForm" action="login.php" method="post" >
					<div class="inputHolder"><input name="email" type="text" /></div>
					<div class="inputHolder"><input name="password" type="password" /></div>
					<div class="inputHolder"><input name="submit" type="submit" value="Log in" /></div>
					<div class="inputHolder"><a href="forgot-password.php">Forgot your password?</a></div>
			</form> -->
				<form class="content-login" action="login.php" method="post" >
					<div class="heading-login">
						<h1 class="heading-login-heading">
							Login
						</h1>
					</div>
					
					<div class="login-content">
						<div class="Email-Label">
							<p class="Email-Label-para">
								Email:
							</p>
						</div>
						<div class="inputLabel">
							<input name="email" type="email" />
						</div>	
						<div class="Email-Label">
							<p class="Email-Label-para">
								Password:
							</p>
						</div>
						<div class="inputLabel">
							<input name="password" type="password" />
						</div>
						
						<div class="button-container">
							<input class="inputHolder" name="forgot" type="button" value="Forgot Your Password?" />
							<input class="inputHolder" name="submit" type="submit" value="Log in" />
							<a href="register.php"><input class="inputHolder" name="create" type="button" value="Create Account" /></a>
						</div>
					</div>
				</form>
			
				
				<?php require_once("parts/footer/footer.php"); ?>
			</div>
		</div>
	</body>
</html>