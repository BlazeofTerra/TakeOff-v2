<?php session_start();

if(isset($_POST['image'])) {
	$id = $_POST['id'];
	$image = $_POST['image'];
	$typeInsert = $_POST['typeInsert'];
	
	unlink("../".$image);
	$_SESSION[$typeInsert]['image_'.$id] = "";
	echo "true";
} else {
	echo "false";
} ?>