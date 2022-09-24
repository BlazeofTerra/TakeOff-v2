<?php
	require("../config/general_db.php");
	$options = "";
	if($_POST['category'] != 0) {
		$category = $_POST['category'];
		
		if($stmt = mysqli_prepare($dbcg, "SELECT id, subcatname FROM subcategory WHERE catid = ? ORDER BY pos")) {
			mysqli_stmt_bind_param($stmt, "i", $category);
			
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
			  	$subCats[$x['id']] = $x;
			}
		
		    mysqli_stmt_fetch($stmt);
		    mysqli_stmt_close($stmt);
		}
		
		foreach ($subCats as $key => $value) {
			$options .= '<option value="'.$value["id"].'">'.$value['subcatname'].'</option>';
		}
		
		echo $options;
	} else {
		$options .= '<option value="0">--</option>';
		echo $options;
	}
?>