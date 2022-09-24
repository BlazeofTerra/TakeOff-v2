<?php if(!empty($_POST)) {
	$error					= false;
	$baseNumber 			= 1.7763440860215;
	list($width, $height) 	= getimagesize($_POST['file']);
	
	$extension				= strtolower(end(explode('.',$_POST['filename'])));
	$filename				= $_POST['name'].'.'.$extension;
	
	$ratio 					= $width / $height;
	
	if($ratio < $baseNumber) {
		$filename 			= "0".$filename;
		$new_height 		= 465;
		$new_width 			= $new_height * $ratio;
	} elseif($ratio > $baseNumber) {
		$filename 			= "1".$filename;
		$new_width 			= 826;
		$new_height 		= $new_width / $ratio;
	} elseif($ratio == $baseNumber) {
		$filename 			= "1".$filename;
		$new_width 			= 826;
		$new_height 		= $new_width / $ratio;
	}
	
	$new_width 				= round($new_width);
	$new_height 			= round($new_height);
	
	$absolutedir			= dirname(__FILE__);
	$absolutedir 			= explode("parts",$absolutedir);
	$dir					= '/image/user/';
	$serverdir				= $absolutedir[0].$dir;
	
	
	if(strtolower($extension) == 'jpg' or strtolower($extension) == 'jpeg') {
		$image 				= imagecreatefromjpeg($_POST['file']);
		
		$bg 				= imagecreatetruecolor($new_width, $new_height);
		
		imagecopyresampled($bg, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		imagedestroy($image);
		
		$filename = preg_replace('/\\.[^.\\s]{3,4}$/', '.jpg', $filename);
		
		imagejpeg($bg, $serverdir.$filename, 90);
		imagedestroy($bg);
	} elseif(strtolower($extension) == 'png') {
		$image 				= imagecreatefrompng($_POST['file']);
		
		$bg 				= imagecreatetruecolor($new_width, $new_height);
		
		imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
		imagealphablending($bg, TRUE);
		imagecopyresampled($bg, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		imagedestroy($image);
		
		$filename 			= preg_replace('/\\.[^.\\s]{3,4}$/', '.jpg', $filename);
		
		imagejpeg($bg, $serverdir.$filename, 90);
		imagedestroy($bg);
	} elseif(strtolower($extension) == 'gif') {
		$image 				= imagecreatefromgif($_POST['file']);
		
		$bg 				= imagecreatetruecolor($new_width, $new_height);
		
		imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
		imagealphablending($bg, TRUE);
		imagecopyresampled($bg, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		imagedestroy($image);
		
		$filename 			= preg_replace('/\\.[^.\\s]{3,4}$/', '.jpg', $filename);
		
		imagejpeg($bg, $serverdir.$filename, 90);
		imagedestroy($bg);
	}
	
	
	$response = array(
			"result" 		=> true,
			"url" 			=> $dir.$filename.'?'.time(), //added the time to force update when editting multiple times
			"filename" 		=> $filename,
			"absolutedir"   => $absolutedir
	);
	
	echo json_encode($response);
} else {
	$filename			= basename($_SERVER['QUERY_STRING']);
	$file_url 			= dirname(__FILE__).'/image/user/'.$filename;
	header('Content-Type: 				application/octet-stream');
	header("Content-Transfer-Encoding: 	Binary");
	header("Content-disposition: 		attachment; filename=\"" . basename($file_url) . "\"");
	readfile($file_url); 
	exit();
} ?>