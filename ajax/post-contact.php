<?php require_once("../config/account_db.php");

if(isset($_POST['mask'])) {
	$accInfo = explode("_", $_POST['mask'], 2);
	if($accInfo[1] == 1) {
		if($stmt = mysqli_prepare($dbca, "SELECT contact FROM businesses WHERE account_id = ?")) {
		 	mysqli_stmt_bind_param($stmt, "i", $accInfo[0]);
			mysqli_stmt_execute($stmt);
			$meta = $stmt->result_metadata();
			
		    while ($field = $meta->fetch_field()) {
			  	$parameters[] = &$row[$field->name];
			}
			
			call_user_func_array(array($stmt, 'bind_result'), $parameters);
			
			while ($stmt->fetch()) {
			  	foreach($row as $key => $val) {
			    	$contact = $val;
			  	}
			}
			
			$parameters = "";
			$row = "";
			$x = "";
		    mysqli_stmt_fetch($stmt);
		    mysqli_stmt_close($stmt);
		}
	} else {
		if($stmt = mysqli_prepare($dbca, "SELECT contact FROM accounts WHERE id = ?")) {
		 	mysqli_stmt_bind_param($stmt, "i", $accInfo[0]);
			mysqli_stmt_execute($stmt);
			$meta = $stmt->result_metadata();
			
		    while ($field = $meta->fetch_field()) {
			  	$parameters[] = &$row[$field->name];
			}
			
			call_user_func_array(array($stmt, 'bind_result'), $parameters);
			
			while ($stmt->fetch()) {
			  	foreach($row as $key => $val) {
			    	$contact = $val;
			  	}
			}
			
			$parameters = "";
			$row = "";
			$x = "";
		    mysqli_stmt_fetch($stmt);
		    mysqli_stmt_close($stmt);
		}
	}
	
	echo $contact;
} ?>