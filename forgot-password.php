<?php require_once("config/account_db.php");
	if(isset($_POST['email'])) {
		$email = $_POST['email'];
		
		if($stmt = mysqli_prepare($dbca, "SELECT email FROM accounts WHERE email = ?")) {
		    mysqli_stmt_bind_param($stmt, "s", $email);
		    mysqli_stmt_execute($stmt);
		    mysqli_stmt_store_result($stmt);
		    $emailCheck = mysqli_stmt_num_rows($stmt);
		    mysqli_stmt_close($stmt);
		}
		
		echo $emailCheck;
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8"/>
		<title>Home</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require_once("config/main-css.php"); ?>
	</head>
		
	<body>
		<div id="outerWrapper">
			<div id="wrapper">
				<!--- NAV ----->
				<?php require_once("parts/nav/upper-nav/upper-nav.php");
					  require_once("parts/nav/lower-nav/lower-nav.php"); ?>
				<!--- NAV CLOSED ----->
				
				<form id="loginForm" action="forgot-password.php" method="post" >
					<div class="inputHolder"><input name="email" type="email" /></div>
					<div class="inputHolder"><input name="submit" type="submit" value="Submit" /></div>
				</form>
				
				<?php require_once("parts/footer/footer.php"); ?>
			</div>
		</div>
	</body>
</html>