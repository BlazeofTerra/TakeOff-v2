<?php 
require('../config/account_db.php');
if(isset($_POST['email'])) {
	$email = $_POST['email'];
	
	if($stmt = mysqli_prepare($dbca, "SELECT email FROM accounts WHERE email = ?")) {
		
	    mysqli_stmt_bind_param($stmt, "s", $email);
		
	    mysqli_stmt_execute($stmt);
		
	    mysqli_stmt_store_result($stmt);
		
	    $emailCheck = mysqli_stmt_num_rows($stmt);
		
	    mysqli_stmt_close($stmt);
	}
	
	if($emailCheck == 1) {
		echo 1;
	} else {
		echo 0;
	}
} else {
	echo 0;
} ?>  