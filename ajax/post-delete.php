<?php if(isset($_POST['id'])) {
	require("../config/general_db.php");
	
	$id = $_POST['id'];
	$date = date('Y-m-d H:i:s');
	$deleted = 1;
	
	if($stmt = mysqli_prepare($dbcg,
		"UPDATE posts SET 
		deleted = ?,
		delete_time = ?
		WHERE id = ?")) {
		
	    mysqli_stmt_bind_param($stmt,
	    	"sii",
	   		$deleted,
	   		$date,
			$id);
		
	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_close($stmt);
	}
} ?>