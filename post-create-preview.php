<?php session_start();

if(!isset($_COOKIE['user'])) {
	header("Location:Home");
} else {
	$account_id = $_COOKIE['user'];
}

if(!isset($_POST['privateSeller'])) {
	$_SESSION['postCreate']['privateSeller'] = "off";
}

$_SESSION['postCreate'] = array_merge($_SESSION['postCreate'], $_POST);

if(isset($_SESSION['postCreate'])) {
	foreach($_SESSION['postCreate'] as $key => $value) {
		${$key} = $value;
	}
} else {
	header("Location:post-create.php");
}

for($i = 1; $i < 7; $i++) {
	if(isset($_SESSION['postCreate']['image_'.$i]) and $_SESSION['postCreate']['image_'.$i] != "") {
		${"image_".$i} = $_SESSION['postCreate']['image_'.$i];
		${"image_".$i."_aspect"} = substr(${"image_".$i}, 0);
		${"image_".$i} = "image/user/".${"image_".$i};
		
		if(${"image_".$i."_aspect"}[0] == 0) {
			${"image_".$i."_aspect"} = "imageHeight";
		} elseif(${"image_".$i."_aspect"}[0] == 1) {
			${"image_".$i."_aspect"} = "imageWidth";
		}
	}
}  ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8"/>
		<title>Post Create</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require_once("config/main-css.php"); ?>
		
		<!-- Slider js and css -->
		<link rel="stylesheet" type="text/css" href="css/post-create-preview.css" />
		<link rel="stylesheet" type="text/css" href="parts/sliders/responsiveslides/responsiveslides-alt.css" />
		<script src="js/responsiveslides.min.js"></script>
	</head>
	
	<body>
		<div id="outerWrapper">
			<div id="wrapper">
				<!--- NAV ----->
				<?php require_once("parts/nav/upper-nav/upper-nav.php");
					  require_once("parts/nav/lower-nav/lower-nav.php"); ?>
				<!--- NAV CLOSED ----->
				<div id="post-back">
					<div id="post-info">
						<div id="name-item">
							<h1><?php echo $title; ?></h1>
						</div>
						<?php require_once("parts/sliders/post-create/post-preview.php"); ?>
						<div id="item-information">
							<div id="item-info">
								<div class="post-info item-info">
									<?php if($priceType == "textPrice") { ?>
										<p><?php echo $priceText; ?></p>
									<?php } else { ?>
										<p><?php if($cur == "pound") { echo "£"; } elseif($cur == "euro") { echo "€"; } echo $price; ?></p>
									<?php } ?>
									
								</div>
								<p><?php echo $blurb; ?></p>
							</div>
							<div id="contact-info">
								<div class="post-info">
									<p><?php if(isset($privateSeller) and $privateSeller == "on") { echo "Private Seller"; } else { echo $seller; } ?></p>
								</div>
								<div class="post-info">
									<p><?php echo $phone; ?></p>
								</div>
								<div class="post-info">
									<p><?php echo $email; ?></p>
								</div>
								<div class="post-info">
									<p><?php echo $location; ?></p>
								</div>
							</div>
						</div>
						<div id="content-text">
							<?php echo $description; ?>
						</div>
						<div id="submitHolder">
							<a href="post-create.php"><button id="back">Back</button></a>
							<a href="post-create-payment.php"><button id="next">Next</button></a>
						</div>
					</div>
				</div>
				<?php require_once("parts/footer/footer.php"); ?>
			</div>
		</div>
	</body>
</html>