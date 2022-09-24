<?php if(isset($_POST['id'])) {
	require("../config/account_db.php");
	
	$userid = $_POST['id'];
	
	if($_POST['type'] == "c") {
		$type = "contact";
	} else {
		$type = "email";
	}
	
	$statment = "SELECT $type FROM accounts WHERE id = ?";
	
	if($stmt = mysqli_prepare($dbca, $statment)) {
		mysqli_stmt_bind_param($stmt, "i", $userid);
		mysqli_stmt_execute($stmt);
		$meta = $stmt->result_metadata();
		
	    while ($field = $meta->fetch_field()) {
		  	$parameters[] = &$row[$field->name];
		}
		
		call_user_func_array(array($stmt, 'bind_result'), $parameters);
		
		while ($stmt->fetch()) {
		  	foreach($row as $key => $val) {
		    	$info = $val;
		  	}
		}
		
		$parameters = "";
		
	    mysqli_stmt_fetch($stmt);
	    mysqli_stmt_close($stmt);
	}
	
	echo "<p>$info</p>";
} ?>