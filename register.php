<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8"/>
		<title>Register</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require_once("config/main-css.php"); ?>
		<link rel="stylesheet" type="text/css" href="css/register.css" />
	</head>
		
	<body>
		<div id="outerWrapper">
			<div id="wrapper">
				<!--- NAV ----->
				<?php require_once("parts/nav/upper-nav/upper-nav.php");
					  require_once("parts/nav/lower-nav/lower-nav.php"); ?>
				<!--- NAV CLOSED ----->
				
				<div class="content-register">
					<div class="heading-register">
						<h1 class="heading-reg-heading">
							Select an Account Type
						</h1>
					</div>
					
					<div class="reg-type-content">
						<a href="indv-reg.php"><div class="reg-type-item">
							<h1 class="reg-type">
								Individual
							</h1>
							<h2 class="reg-cost">
								Free Registration
							</h2>
							<div class="reg-info">
								<p class="reg-info-item">
									Unlimited edits of your individual profile and posts during the period of validity - 30,60 or 90 days.
								</p>
								<p class="reg-info-item">
									Anonymous posting if wanted.
								</p>
								<p class="reg-info-item">
									An individual profile page displaying your contact information during the period of validity of a post.
								</p>
							</div>
						</div></a>
						
						<a href="bus-reg.php"><div class="reg-type-item">
							<h1 class="reg-type">
								Business
							</h1>
							<h2 class="reg-cost">
								R2,400.00 per year.
							</h2>
							<div class="reg-info">
								<p class="reg-info-item">
									Unlimited edits of your individual profile and posts during the period of validity - 30,60 or 90 days.
								</p>
								<p class="reg-info-item">
									A business profile page displaying your contact information, text area allowing up to 30,000 characters and the ability to create offices and representatives.
								</p>
								<p class="reg-info-item">
									A logo and up to 6 photographs may be included.
								</p>
								<p class="reg-info-item">
									Business profile is valid for a full year from date of creation
								</p>
								<p class="reg-info-item">
									Credit facilities available. (See Terms and conditions' under 'Pricing'.)
								</p>
							</div>
						</div></a>
					</div>
				</div>
				<?php require_once("parts/footer/footer.php"); ?>
			</div>
		</div>
	</body>
</html>