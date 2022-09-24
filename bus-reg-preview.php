<?php session_start();

if(!isset($_SESSION['busReg'])) {
	header("Location:bus-reg.php");
}

$_SESSION['busReg'] = array_merge($_SESSION['busReg'], $_POST);

foreach($_SESSION['busReg'] as $key => $value) {
	${$key} = $value;
} ?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8"/>
		<title>Business Registration</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require_once("config/main-css.php"); ?>
		
		<!-- Slider js and css -->
		<link rel="stylesheet" type="text/css" href="css/bus-reg-preview.css" />
		<link rel="stylesheet" type="text/css" href="parts/sliders/responsiveslides/responsiveslides.css" />
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
							<?php if($image_1 != "")  { ?>
								<img src="image/user/<?php echo $image_1; ?>" alt="">
							<?php } ?>
							<h1><?php echo $businessName; ?></h1>
						</div>
						<?php require_once("parts/sliders/bus-reg/preview.php"); ?>
						<div id="item-information">
							<h2 id="basic-info">Basic contact information</h2>
							<p class="basic-info-contact"><?php if(isset($businessContactNumber) and $businessContactNumber != "") { echo $businessContactNumber; } ?></p>
							<p class="basic-info-contact"><?php echo $businessEmail; ?></p>
							<p class="basic-info-contact"><?php echo $country; ?></p>
							<a target="_blank" href="<?php echo $website; ?>"><p class="basic-info-contact">Our website</p></a>
						</div>
						<div id="content-text">
							<?php echo $description; ?>
						</div>
						<div id="submitHolder">
							<a href="bus-reg.php"><button id="back">Back</button></a>
							<a href="bus-reg-payment.php"><button id="next">Next</button></a>
						</div>
					</div>
				</div>
				<?php require_once("parts/footer/footer.php"); ?>
			</div>
		</div>
	</body>
</html>