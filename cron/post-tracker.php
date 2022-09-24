<?php require_once("../config/general_db.php");

if($stmt = mysqli_prepare($dbcg, "SELECT id FROM category ORDER BY id")) {
	mysqli_stmt_execute($stmt);
	$meta = $stmt->result_metadata();
	
    while ($field = $meta->fetch_field()) {
	  	$parameters[] = &$row[$field->name];
	}
	
	call_user_func_array(array($stmt, 'bind_result'), $parameters);
	
	while ($stmt->fetch()) {
	  	foreach($row as $key => $val) {
	    	$categories[$row['id']] = 0;
	  	}
	}
	
	$x = "";
	$row = "";
	$parameters = "";
	
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

$categories[0] = 0;

if($stmt = mysqli_prepare($dbcg, "SELECT category_1, category_2, category_3, category_4, category_5 FROM posts WHERE type = 0")) {
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
	  	$posts[] = $x;
	}
	
	$x = "";
	$row = "";
	$parameters = "";
	
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
} 

foreach($posts as $key => $value) {
	$usedNumbers = "";
	
	for($i = 1; $i < 6; $i++) {
		${"category_".$i} = (string)$value['category_'.$i];
		
		if(strpos($usedNumbers, ${"category_".$i}) !== false) {} else {
			$categories[${"category_".$i}]++;
			$usedNumbers .= ${"category_".$i}." ";
		}
		
	}
}

foreach($categories as $key => $value) {
	if($stmt = mysqli_prepare($dbcg,
		"UPDATE category SET number_posts = ? WHERE id = ?")) {
		
	    mysqli_stmt_bind_param($stmt,
	    	"ii", $value, $key);
		
	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_close($stmt);
	}
} ?>