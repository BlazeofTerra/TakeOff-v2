<?php session_start();
if(isset($_POST['image'])) {
	$id = $_POST['id'];
	$image = $_POST['image'];
	$typeInsert = $_POST['typeInsert'];
	
	$size = substr($image, 0);
	
	if($size[0] == 0) {
		$answer = "imageHeight";
	} elseif($size[0] == 1) {
		$answer = "imageWidth";
	}
	
	if(isset($_SESSION[$typeInsert]['image_'.$id]) and $_SESSION[$typeInsert]['image_'.$id] != "") {
		if(file_exists("../image/user/".$_SESSION[$typeInsert]['image_'.$id])) {
			unlink("../image/user/".$_SESSION[$typeInsert]['image_'.$id]);
		}
	}
	
	$_SESSION[''.$typeInsert]['image_'.$id] = $image;
	
	echo $answer;
} else {
	echo "false";
} ?>