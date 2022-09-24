<?php
	if(isset($_POST['id'])) {
		require("../config/general_db.php");
		
		$checkQuery = "SELECT COUNT(*) FROM representative WHERE id = ".$_POST['id']." AND busid = ".$_POST['userid'];
		
		$officeCheck = mysqli_query($dbcg, $checkQuery);
		$officeCheck = mysqli_fetch_assoc($officeCheck);
		
		$officeCheck = $officeCheck['COUNT(*)'];
		
		if($officeCheck == 1) {
			$sql = "DELETE FROM representative WHERE id = ".$_POST['id'];
			
			if(mysqli_query($dbcg, $sql)) {
			    echo "Office deleted successfully";
			} else {
			    echo "Error deleting record: " . mysqli_error($dbcg);
			}
		} else {
			echo "Office not recognised";
		}
	}
?>