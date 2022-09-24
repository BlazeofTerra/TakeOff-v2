<?php session_start();

if(!isset($_SESSION['busReg'])) {
	header("Location:bus-reg.php");
}

if(!isset($_SESSION['busReg']['name']) and $_SESSION['busReg']['name'] != "") {
	header("Location:bus-reg.php");
}

require_once("config/account_db.php");
require_once("config/general_db.php");

$_SESSION['busReg'] = array_merge($_SESSION['busReg'], $_POST);

foreach($_SESSION['busReg'] as $key => $value) {
	${$key} = trim($value);
}

$password = password_hash($password, PASSWORD_DEFAULT);
$type = 1;
$email_code = md5($email + microtime());
$expired = date('Y-m-d',strtotime(date("Y-m-d", time()) . " + 365 day"));
$active = 1;
$fax = "";
$provence = "";
$date = date('Y-m-d H:i:s');

do {
	$mask = rand(10000000,99999999);

	$maskCheckQ = "SELECT COUNT(mask) FROM accounts WHERE mask = '$mask'";
	$maskCheckR = mysqli_query($dbca, $maskCheckQ);
	$maskCheckAnswer = mysqli_fetch_assoc($maskCheckR);
	
	if($maskCheckAnswer['COUNT(mask)'] == 0) {
		break;
	}
} while(1);

if(isset($_COOKIE['r'])) {
	$r = $_COOKIE['r'];
	
	setcookie("r", 0, 1);
} else {
	$r = 0;
}

if($stmt = mysqli_prepare($dbca, "INSERT INTO accounts (mask, name, contact, email, password, type, email_code, date_expired, active, recruiter) 
												VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {

    mysqli_stmt_bind_param($stmt, "issssissii", $mask, $name, $contactNumber, $email, $password, $type, $email_code, $expired, $active, $r);
	
    mysqli_stmt_execute($stmt);
	
    mysqli_stmt_close($stmt);
}

$account_id = mysqli_insert_id($dbca);
$date2 = date('Y-m-d H:i:s');
$expired = date("Y-m-d H:i:s", strtotime("+365 days"));
$type = 1;

if($stmt = mysqli_prepare($dbcg,
	"INSERT INTO posts (type, date_post, date_expired, userid, country, provence, category_1, category_2, category_3, category_4, category_5, subCategory_1, subCategory_2, subCategory_3, subCategory_4, 
					subCategory_5,business_name, website, contact, email, fax, address, postcode, content, image_1, image_2, image_3, 
					image_4, image_5, image_6, image_7, latest_post_date) 
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
	
    mysqli_stmt_bind_param($stmt,
    	"sssissssssssssssssssssssssssssss",
    	$type,
    	$date2,
    	$expired,
   		$account_id,
   		$country,
   		$provence,
   		$category_1,
   		$category_2,
   		$category_3,
   		$category_4,
   		$category_5,
   		$subCategory_1,
   		$subCategory_2,
   		$subCategory_3,
   		$subCategory_4,
   		$subCategory_5,
   		$businessName, 
   		$website,
   		$businessContactNumber,
   		$businessEmail,
   		$fax,
   		$businessAddress,
   		$businessPostcode,
   		$description,
   		$image_1,
   		$image_2,
   		$image_3,
   		$image_4,
   		$image_5,
   		$image_6,
   		$image_7,
   		$date);
	
    mysqli_stmt_execute($stmt);
	
    mysqli_stmt_close($stmt);
} ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8"/>
		<title>Business Registration</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require_once("config/main-css.php"); ?>
		
		<!-- Slider js and css -->
		<link rel="stylesheet" type="text/css" href="css/bus-reg-payment.css" />
	</head>
	<body>
		<div id="outerWrapper">
			<div id="wrapper">
				<!--- NAV ----->
				<?php require_once("parts/nav/upper-nav/upper-nav.php");
					  require_once("parts/nav/lower-nav/lower-nav.php"); ?>
				<!--- NAV CLOSED ----->
				
				<?php require_once("parts/footer/footer.php"); ?>
			</div>
		</div>
	</body>
</html>