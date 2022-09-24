<?php session_start();
require_once("config/general_db.php");
require_once("config/account_db.php");

if(isset($_COOKIE['user'])) {
	$account_id = $_COOKIE['user'];
} else {
	header("Location:Home");
}


if($stmt = mysqli_prepare($dbca, "SELECT type FROM accounts WHERE id = ?")) {
    mysqli_stmt_bind_param($stmt,"i",$account_id);
    mysqli_stmt_execute($stmt);
	$meta = $stmt->result_metadata();
	
    while ($field = $meta->fetch_field()) {
	  	$parameters[] = &$row[$field->name];
	}
	
	call_user_func_array(array($stmt, 'bind_result'), $parameters);
	
	while ($stmt->fetch()) {
	  	foreach($row as $key => $val) {
	    	$key = $val;
	  	}
	}
	
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

$userType = $key;

$_SESSION['postCreate'] = array_merge($_SESSION['postCreate'], $_POST);

if(isset($_SESSION['postCreate'])) {
	foreach($_SESSION['postCreate'] as $key => $value) {
		${$key} = $value;
	}
} else {
	header("Location:post-create.php");
}

print_r($_SESSION['postCreate']);

if($priceType == "textPrice") {
	$cur = "";
	$price = $priceText;
}

$type = 0;

if(isset($_GET['test'])) {
	$date = date('Y-m-d H:i:s');
	$expired = date("Y-m-d H:i:s", strtotime("+30 days"));
	if(isset($privateSeller) and $privateSeller == "on") {
		$seller = "Private Seller";
	}
	
	do {
		$mask = rand(10000000,99999999);
	
		$maskCheckQ = "SELECT COUNT(mask) FROM posts WHERE mask = '$mask'";
		$maskCheckR = mysqli_query($dbcg, $maskCheckQ);
		$maskCheckAnswer = mysqli_fetch_assoc($maskCheckR);
		
		if($maskCheckAnswer['COUNT(mask)'] == 0) {
			break;
		}
	} while(1);
	//23
	if($stmt = mysqli_prepare($dbcg,
		"INSERT INTO posts (type, mask, date_post, date_expired, userid, user_type, title, cur, price, priceType, pic, location, blurb, country, category_1, category_2, category_3, category_4, category_5, 
							subcategory_1, subcategory_2, subcategory_3, subcategory_4, subcategory_5) 
						 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
		
	    mysqli_stmt_bind_param($stmt,
	    	"iissiisssssiiiiiiiisssss",
	   		$type,
	   		$mask, 
	   		$date, 
	   		$expired,
	   		$account_id,
	   		$userType,
	   		$title,
	   		$cur,
	   		$price,
	   		$priceType,
	   		$image_1,
	   		$location,
	   		$blurb,
	   		$country,
	   		$category_1,
	   		$category_2,
	   		$category_3,
	   		$category_4,
	   		$category_5,
	   		$subCategory_1,
	   		$subCategory_2,
	   		$subCategory_3,
	   		$subCategory_4,
	   		$subCategory_5);
		
	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_close($stmt);
	}
	
	$post_id = mysqli_insert_id($dbcg);
	
	if($stmt = mysqli_prepare($dbcg,
		"INSERT INTO postinfo (post_id, content, image_1, image_2, image_3, image_4, image_5, image_6, seller_name, phone, email) 
						 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
		
	    mysqli_stmt_bind_param($stmt,
	    	"issssssssss",
	   		$post_id,
	   		$description,
	   		$image_1,
	   		$image_2,
	   		$image_3,
	   		$image_4,
	   		$image_5,
	   		$image_6,
	   		$seller,
	   		$phone,
	   		$email);
		
	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_close($stmt);
	}
} ?>