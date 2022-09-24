<?php session_start();

if(!isset($_SESSION['postCreate'])) {
	header("Location:post-create.php");
} ?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8"/>
		<title>Post Create</title>
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
				
				<form id="paymentHolder" action="post-create-end.php" method="post">
					<div id="inputs">
						<label for="vatNumber">Vat number:</label><input id="vatNumber" type="text" name="vatNumber"<?php if(isset($_SESSION['postCreate']['vatNumber'])) { echo ' value="'.$_SESSION['postCreate']['vatNumber'].'"'; } ?> /><br />
						<label for="reference">Reference</label><input id="reference" type="text" name="reference"<?php if(isset($_SESSION['postCreate']['reference'])) { echo ' value="'.$_SESSION['postCreate']['reference'].'"'; } ?> />
					</div>
					<div id="paymentOptions">
						<h2>Immediate Payment</h2>
						<h2>Credit Requested</h2>
						<div class="payment">
							<input type="submit" name="submit" value="instant" />
						</div>
						<div class="payment">
							<input type="submit" name="submit" value="invoice" />
						</div>
					</div>
				</form>
				<?php require_once("parts/footer/footer.php"); ?>
			</div>
		</div>
	</body>
</html>