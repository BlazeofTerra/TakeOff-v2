<?php session_start();

require_once("config/general_db.php");

if($stmt = mysqli_prepare($dbcg, "SELECT id, label, name FROM category ORDER BY pos")) {
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
	  	$categories[$x['label']] = $x;
	}
	
	$parameters = "";
	
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

if($stmt = mysqli_prepare($dbcg, "SELECT id, name FROM countries ORDER BY name")) {
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
	  	$countries[$x['id']] = $x;
	}
	
	$parameters = "";

    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

function subCat($dbcg, $catID, $subCatId) {
    if($stmt = mysqli_prepare($dbcg, "SELECT id, label FROM bussubcats WHERE catid = ? ORDER BY pos")) {
    	mysqli_stmt_bind_param($stmt, "s", $catID);
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
			if($subCatId == $x['id']) {
				echo '<option value="'.$x['id'].'" selected>'.$x['label'].'</option>';
			} else {
				echo '<option value="'.$x['id'].'">'.$x['label'].'</option>';
			}
		}
		
	    mysqli_stmt_fetch($stmt);
	    mysqli_stmt_close($stmt);
	}
}

if(!isset($_SESSION['busReg'])) {
	$_SESSION['busReg'] = array("image_1"=>"", "image_2"=>"", "image_3"=>"", "image_4"=>"", "image_5"=>"", "image_6"=>"", "image_7"=>"");
} else {
	foreach($_SESSION['busReg'] as $key => $value) {
		if(strpos($key, 'image') === false) {
			${$key} = $value;
		}
	}
}

$defaultImage = "img/testplane.jpg";
$defaultAspect = "imageWidth";

for($i = 1; $i < 8; $i++) {
	${"image_".$i} = $defaultImage;
	${"image_".$i."_aspect"} = $defaultAspect;
	
	if(isset($_SESSION['busReg']['image_'.$i]) and $_SESSION['busReg']['image_'.$i] != "") {
		${"image_".$i} = $_SESSION['busReg']['image_'.$i];
		${"image_".$i."_aspect"} = substr(${"image_".$i}, 0);
		${"image_".$i} = "image/user/".${"image_".$i};
		
		if(${"image_".$i."_aspect"}[0] == 0) {
			${"image_".$i."_aspect"} = "imageHeight";
		} elseif(${"image_".$i."_aspect"}[0] == 1) {
			${"image_".$i."_aspect"} = "imageWidth";
		}
	}
} ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8"/>
		<title>Business Registration</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require_once("config/main-css.php"); ?>
		
		<!-- Slider js and css -->
		<link rel="stylesheet" type="text/css" href="css/bus-reg.css" />
		<link rel="stylesheet" type="text/css" href="parts/html5uploader/css/html5fileupload.css" />
		<script src="parts/tinymce/jquery.tinymce.min.js"></script>
		<script src="parts/tinymce/tinymce.min.js"></script>
		<script src="parts/html5uploader/js/html5fileupload.min.js"></script>
		<script>
			tinymce.init({
				menubar: false,
				statusbar: false,
				selector: "textarea",
				height : "500",
		        plugins: "paste hr",
		
		        toolbar1: "undo redo | bold italic underline strikethrough hr | alignleft aligncenter alignright alignjustify | bullist numlist | formatselect"
			});
		</script>
		<script>
			$(document).ready(function() {
				var emailCheck = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
				
				function errorManagerBasic(errorId, setting) {
					var errorId = errorId + "Error";
					
				     if(setting == "hide") {
				     	$("#" + errorId).hide();
				     } else if(setting == "show") {
				     	$("#" + errorId).show();
				     }
				}
				
				function errorManagerAdv(errorId, setting, type) {
					if(errorId == "rePassword") {
						errorId = "password";
					}
					
					var errorId = errorId + type + "Error";
				     if(setting == "hide") {
				     	$("#" + errorId).hide();
				     } else if(setting == "show") {
				     	$("#" + errorId).show();
				     }
				}
				
				$("#name").focusout(function() {
					var name = $("#name").val().trim(),
						errorId = this.id;
					
					if(name !== "") {
						errorManagerBasic(errorId, "hide");
					} else {
						errorManagerBasic(errorId, "show");
					}
				});
				
				$("#email").focusout(function() {
					var email = $("#email").val().trim(),
						errorId = this.id;
					
					if(email !== "") {
						errorManagerBasic(errorId, "hide");
						
						if(emailCheck.test(email)) {
							errorManagerAdv(errorId, "hide", "Valid");
						} else {
							errorManagerAdv(errorId, "show", "Valid");
						}
						
						$.post("ajax/email-check.php", {email: email})
						.done(function(data) {
							if(data == 1) {
								errorManagerAdv(errorId, "show", "Taken");
							} else {
								errorManagerAdv(errorId, "hide", "Taken");
							}
						});
					} else {
						errorManagerBasic(errorId, "show");
					}
				});
				
				$(".passwords").focusout(function() {
					var password = $("#password").val().trim(),
			        	rePassword = $("#rePassword").val().trim(),
			        	length = password.length,
			        	errorId = this.id;
					
					if(password !== "" && rePassword !== "") {
				        if(password == rePassword) {
				        	errorManagerAdv(errorId, "hide", "Miss");
				        } else {
				        	errorManagerAdv(errorId, "show", "Miss");
				        }
					}
					
					if(password !== "" && length < 8) {
						errorManagerAdv(errorId, "show", "Short");
					} else if(password !== "" && length >= 8) {
						errorManagerAdv(errorId, "hide", "Short");
					}
				});
				
				$("#businessName").focusout(function() {
					var name = $("#businessName").val().trim(),
						errorId = this.id;
					
					if(name !== "") {
						errorManagerBasic(errorId, "hide");
					} else {
						errorManagerBasic(errorId, "show");
					}
				});
				
				$("#businessEmail").focusout(function() {
					var email = $("#businessEmail").val().trim(),
						errorId = this.id;
					
					if(email !== "") {
						errorManagerBasic(errorId, "hide");
						
						if(emailCheck.test(email)) {
							errorManagerAdv(errorId, "hide", "Valid");
						} else {
							errorManagerAdv(errorId, "show", "Valid");
						}
					} else {
						errorManagerBasic(errorId, "show");
					}
				});
				
				
				$("#submitButton").click(function(e) {
					var checkNumber = 0;
					var name = $("#name").val().trim();
					var email = $("#email").val().trim();
					var password = $("#password").val().trim(),
			        	rePassword = $("#rePassword").val().trim(),
			        	length = password.length;
			        var name = $("#businessName").val().trim();
			        var email = $("#businessEmail").val().trim();
					
					if(name == "") {
						$("#name").show();
						checkNumber++;
					}
					
					if(email !== "") {
						if(emailCheck.test(email)) {} else {
							$("#emailValidError").show();
							checkNumber++;
						}
						
						$.post("ajax/email-check.php", {email: email})
						.done(function(data) {
							if(data == 1) {
								$("#emailTakenError").show();
								checkNumber++;
							}
						});
					} else {
						$("#emailError").show();
						checkNumber++;
					}
					
					if(password !== "" && rePassword !== "") {
				        if(password != rePassword) {
				        	$("#passwordMissError").show();
							checkNumber++;
				        }
					} else {
						$("#passwordError").show();
						checkNumber++;
					}
					
					if(password !== "" && length < 8) {
						$("#passwordShortError").show();
						checkNumber++;
					}
					
					if(name == "") {
						$("#businessNameError").show();
						checkNumber++;
					}
					
					if(email !== "") {
						if(emailCheck.test(email)) {} else {
							$("#businessValidError").show();
							checkNumber++;
						}
					} else {
						$("#businessError").show();
						checkNumber++;
					}
					
					if(!$("#terms").is(":checked")) {
						$("#termsError").show();
						checkNumber++;
					}
					
					if(checkNumber != 0) {
						e.preventDefault();
						$('html, body').animate({scrollTop: '0px'}, 300);
					}
				});
				
				$(".categoryPicker").change(function() {
					var category = this.value,
						split = this.id.split('_'),
						dropdownId = split[1],
						subCat = "";
					
					$.post("ajax/busubcategory-changer.php", {category: category})
					.done(function(data) {
						$("#subCategory_" + dropdownId).html(data);
					});
				});
				
				<?php for($i = 1; $i < 8; $i++) { ?>
					$('#uploader_<?php echo $i; ?>').html5fileupload({
						onAfterStartSuccess: function(response) {
							var id = <?php echo $i; ?>,
								typeInsert = "busReg",
								image = response['filename'];
							
							$.post("ajax/image-manager.php", {id: id, image: image, typeInsert: typeInsert})
							.done(function(data) {
								$("#image_<?php echo $i; ?> > img").attr("src", "image/user/" + image);
								$("#image_<?php echo $i; ?> > img").removeClass();
								$("#image_<?php echo $i; ?> > img").addClass(data);
							});
							
							$("#uploader_<?php echo $i; ?>").hide();
							$("#image_<?php echo $i; ?>").show();
							$("#uploader_<?php echo $i; ?>Button").removeClass("current");
							$("#image_<?php echo $i; ?>Button").addClass("current");
							$("#delete_<?php echo $i; ?>Button").prop("disabled", false);
						}
					});
				<?php } ?>
				
				$('.dropButtonHolder a').click(function(e) {
					e.preventDefault();
					
					var href = $(this).attr('href'),
		   	 			split = href.split('_'),
		   	 			button = split[0].replace('#',''),
		   	 			id = split[1];
		   	 		
		 			if(button == "uploader") {
		   	 			$("#image_" + id).hide();
		   	 			$("#image_" + id + "Button").removeClass("current");
		   	 			$("#uploader_" + id).show();
		   	 			$("#uploader_" + id + "Button").addClass("current");
		   	 		} else if(button == "image") {
		   	 			$("#uploader_" + id).hide();
		   	 			$("#uploader_" + id + "Button").removeClass("current");
		   	 			$("#image_" + id).show();
		   	 			$("#image_" + id + "Button").addClass("current");
		   	 		} else if(button == "delete") {
		   	 			var image = $("#image_" + id + " img").attr("src"),
		   	 				typeInsert = "busReg";
		   	 			
		   	 			if(window.confirm("Are you sure you want to delete this image?")) {
				            $.post("ajax/image-delete.php", {id: id, image: image, typeInsert: typeInsert})
				            .done(function(data) {
								$("#image_" + id).hide();
				   	 			$("#image_" + id + "Button").removeClass("current");
				   	 			$("#uploader_" + id).show();
				   	 			$("#uploader_" + id + "Button").addClass("current");
				   	 			
				   	 			$("#image_" + id + " img").attr("src", "<?php echo $defaultImage; ?>");
				   	 			$("#image_" + id + " img").removeClass();
				   	 			$("#image_" + id + " img").addClass("<?php echo $defaultAspect; ?>");
				   	 			
				   	 			$("#delete_" + id + "Button").prop("disabled", true);
							});
				        }
		   	 		}
				});
			});
		</script>
	</head>
		
	<body>
		<div id="outerWrapper">
			<div id="wrapper">
				<!--- NAV ----->
				<?php require_once("parts/nav/upper-nav/upper-nav.php");
					  require_once("parts/nav/lower-nav/lower-nav.php"); ?>
				<!--- NAV CLOSED ----->
				
				<form id="formWrapper" action="bus-reg-preview.php" method="post">
					<h1>Business Registration</h1>
					
					<div id="errorHolder">
						<p id="nameError" style="display: none;">Your name is required</p>
						<p id="emailError" style="display: none;">Your email is required</p>
						<p id="emailValidError" style="display: none;">Your email is not valid</p>
						<p id="emailTakenError" style="display: none;">This email is already taken</p>
						<p id="passwordError" style="display: none;">A password is required</p>
						<p id="passwordMissError" style="display: none;">Your passwords do not match</p>
						<p id="passwordShortError" style="display: none;">Your password needs to be more than 8 characters long</p>
						<p id="businessNameError" style="display: none;">Your business's name is required</p>
						<p id="businessEmailError" style="display: none;">Your email name is required</p>
						<p id="businessEmailValidError" style="display: none;">Your email is not valid</p>
						<p id="termsError" style="display: none;">You need to accept the terms and conditions to continue</p>
					</div>
					
					<div class="inputContainer">
						<h2>Basic Information</h2>
						<div class="inputRow"><label for="firstName">Your name:</label><input id="name" type="text" name="name"<?php if(isset($name)) { echo ' value="'.$name.'"'; } ?> /></div>
						<div class="inputRow"><label for="contactNumber">Contact Number:</label><input id="contactNumber" type="text" name="contactNumber"<?php if(isset($contactNumber)) { echo ' value="'.$contactNumber.'"'; } ?> /></div>
						<div class="inputRow"><label for="email">Email:</label><input id="email" type="email" name="email"<?php if(isset($email)) { echo ' value="'.$email.'"'; } ?> /></div>
						<div class="inputRow"><label for="password">Password:</label><input id="password" class="passwords" type="password" name="password" /></div>
						<div class="inputRow"><label for="rePassword">Retype password:</label><input id="rePassword" class="passwords" type="password" name="rePassword" /></div>
					</div>
					
					<div class="inputContainer">
						<h2>Your Business Information</h2>
						<div class="inputRow"><label for="businessName">Business Name:</label><input id="businessName" type="text" name="businessName"<?php if(isset($businessName)) { echo ' value="'.$businessName.'"'; } ?> /></div>
						<div class="inputRow"><label for="businessContactNumber">Business Contact Number:</label><input id="businessContactNumber" type="text" name="businessContactNumber"<?php if(isset($businessContactNumber)) { echo ' value="'.$businessContactNumber.'"'; } ?> /></div>
						<div class="inputRow"><label for="businessEmail">Business Email:</label><input id="businessEmail" type="email" name="businessEmail"<?php if(isset($businessEmail)) { echo ' value="'.$businessEmail.'"'; } ?> /></div>
						<div class="inputRow"><label for="businessEmail">Business address:</label><input id="businessAddress" type="text" name="businessAddress"<?php if(isset($businessAddress)) { echo ' value="'.$businessAddress.'"'; } ?> /></div>
						<div class="inputRow"><label for="businessEmail">Business postcode:</label><input id="businessPostcode" type="text" name="businessPostcode"<?php if(isset($businessPostcode)) { echo ' value="'.$businessPostcode.'"'; } ?> /></div>
						<div class="inputRow"><label for="website">Website:</label><input id="website" type="text" name="website"<?php if(isset($website)) { echo ' value="'.$website.'"'; } ?> /></div>
						<div class="inputRow"><label for="countie">Country:</label>
							<select name="country">
								<?php foreach ($countries as $key => $value) { ?>
									<option value="<?php echo $value['name']; ?>"<?php if(isset($country) and $country == $value['name']) { echo " selected"; } ?>><?php echo $value['name']; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					
					<div class="inputContainer">
						<h2>Your Logo</h2>
						<div class="logoHolderOuter">
							<div class="logoHolder">
								<div class="dropButtonHolder">
									<a href="#image_1"><button id="image_1Button" class="uploaderButton">Picture</button></a>
									<a href="#uploader_1"><button id="uploader_1Button" class="uploaderButton current">Change</button></a>
									<a href="#delete_1"><button id="delete_1Button" class="uploaderButton" <?php if($image_1 == $defaultImage) { echo "disabled"; } ?>>Delete</button></a>
								</div>
								<div id="image_1" class="imageHolder" <?php if($image_1 == $defaultImage) { echo 'style="display: none;"'; } ?>>
									<img class="<?php echo $image_1_aspect; ?>" src="<?php echo $image_1; ?>" />
								</div>
								<div id="uploader_1" class="html5fileupload" <?php if($image_1 != $defaultImage) { echo 'style="display: none;"'; } ?> data-url="parts/html5uploader/html5fileupload.php" data-remove-done="true" data-autostart="true" data-random-name="true" data-random-name-length="14" data-valid-extensions="png,jpeg,JPG,jpg,gif">
								  	<input type="file" name="logo" />
								</div>
							</div>
						</div>
					</div>
					<div class="inputContainer">
						<h2>Categories</h2>
						<div id="categoryWrapper">
							<div class="categoryHeadersHolder"><h3 class="categoryHeaders">Category</h3><h3 class="categoryHeaders">Sub-Category</h3></div>
							<div class="categoryHolder">
								<div class="categories">
									<select id="category_1" class="categoryPicker" name="category_1">
										<option value="0">--</option>
										<?php if(isset($category_1) and $category_1 != 0) {
											foreach ($categories as $key => $value) { ?>
												<option value="<?php echo $value['id']; ?>"<?php if($category_1 == $value['id']) { echo " selected"; } ?>><?php echo $value['label']; ?></option>
											<?php }
										} else { ?>
											<?php foreach ($categories as $key => $value) { ?>
												<option value="<?php echo $value['id']; ?>"><?php echo $value['label']; ?></option>
											<?php }
										} ?>
									</select>
								</div>
								<div class="subCategories">
									<select id="subCategory_1" name="subCategory_1">
										<?php if(isset($subCategory_1) and $subCategory_1 != 0) {
											subCat($dbcg, $category_1, $subCategory_1);
										} else { ?>
											<option value="0">--</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="categoryHolder">
								<div class="categories">
									<select id="category_2" class="categoryPicker" name="category_2">
										<option value="0">--</option>
										<?php if(isset($category_2) and $category_2 != 0) {
											foreach ($categories as $key => $value) { ?>
												<option value="<?php echo $value['id']; ?>"<?php if($category_2 == $value['id']) { echo " selected"; } ?>><?php echo $value['label']; ?></option>
											<?php }
										} else { ?>
											<?php foreach ($categories as $key => $value) { ?>
												<option value="<?php echo $value['id']; ?>"><?php echo $value['label']; ?></option>
											<?php }
										} ?>
									</select>
								</div>
								<div class="subCategories">
									<select id="subCategory_2" name="subCategory_2">
										<?php if(isset($subCategory_2) and $subCategory_2 != 0) {
											subCat($dbcg, $category_2, $subCategory_2);
										} else { ?>
											<option value="0">--</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="categoryHolder">
								<div class="categories">
									<select id="category_3" class="categoryPicker" name="category_3">
										<option value="0">--</option>
										<?php if(isset($category_3) and $category_3 != 0) {
											foreach ($categories as $key => $value) { ?>
												<option value="<?php echo $value['id']; ?>"<?php if($category_3 == $value['id']) { echo " selected"; } ?>><?php echo $value['label']; ?></option>
											<?php }
										} else { ?>
											<?php foreach ($categories as $key => $value) { ?>
												<option value="<?php echo $value['id']; ?>"><?php echo $value['label']; ?></option>
											<?php }
										} ?>
									</select>
								</div>
								<div class="subCategories">
									<select id="subCategory_3" name="subCategory_3">
										<?php if(isset($subCategory_3) and $subCategory_3 != 0) {
											subCat($dbcg, $category_3, $subCategory_3);
										} else { ?>
											<option value="0">--</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="categoryHolder">
								<div class="categories">
									<select id="category_4" class="categoryPicker" name="category_4">
										<option value="0">--</option>
										<?php if(isset($category_4) and $category_4 != 0) {
											foreach ($categories as $key => $value) { ?>
												<option value="<?php echo $value['id']; ?>"<?php if($category_4 == $value['id']) { echo " selected"; } ?>><?php echo $value['label']; ?></option>
											<?php }
										} else { ?>
											<?php foreach ($categories as $key => $value) { ?>
												<option value="<?php echo $value['id']; ?>"><?php echo $value['label']; ?></option>
											<?php }
										} ?>
									</select>
								</div>
								<div class="subCategories">
									<select id="subCategory_4" name="subCategory_4">
										<?php if(isset($subCategory_4) and $subCategory_4 != 0) {
											subCat($dbcg, $category_4, $subCategory_4);
										} else { ?>
											<option value="0">--</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="categoryHolder">
								<div class="categories">
									<select id="category_5" class="categoryPicker" name="category_5">
										<option value="0">--</option>
										<?php if(isset($category_5) and $category_5 != 0) {
											foreach ($categories as $key => $value) { ?>
												<option value="<?php echo $value['id']; ?>"<?php if($category_5 == $value['id']) { echo " selected"; } ?>><?php echo $value['label']; ?></option>
											<?php }
										} else { ?>
											<?php foreach ($categories as $key => $value) { ?>
												<option value="<?php echo $value['id']; ?>"><?php echo $value['label']; ?></option>
											<?php }
										} ?>
									</select>
								</div>
								<div class="subCategories">
									<select id="subCategory_5" name="subCategory_5">
										<?php if(isset($subCategory_5) and $subCategory_5 != 0) {
											subCat($dbcg, $category_5, $subCategory_5);
										} else { ?>
											<option value="0">--</option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
					</div>
					
					<div class="inputContainer">
						<h2>Business pics</h2>
						<div class="slideImageHolder">
							<div class="slideImageUploader">
								<div class="dropButtonHolder">
									<a href="#image_2"><button id="image_2Button" class="uploaderButton">Picture</button></a>
									<a href="#uploader_2"><button id="uploader_2Button" class="uploaderButton current">Change</button></a>
									<a href="#delete_2"><button id="delete_2Button" class="uploaderButton" <?php if($image_2 == $defaultImage) { echo "disabled"; } ?>>Delete</button></a>
								</div>
								<div id="image_2" class="imageHolder" <?php if($image_2 == $defaultImage) { echo 'style="display: none;"'; } ?>>
									<img class="<?php echo $image_2_aspect; ?>" src="<?php echo $image_2; ?>" />
								</div>
								<div id="uploader_2" class="html5fileupload" <?php if($image_2 != $defaultImage) { echo 'style="display: none;"'; } ?> data-url="parts/html5uploader/html5fileupload.php" data-remove-done="true" data-autostart="true" data-random-name="true" data-random-name-length="14" data-valid-extensions="png,jpeg,JPG,jpg,gif">
								  	<input type="file" name="logo" />
								</div>
							</div>
							
							<div class="slideImageUploader">
								<div class="dropButtonHolder">
									<a href="#image_3"><button id="image_3Button" class="uploaderButton">Picture</button></a>
									<a href="#uploader_3"><button id="uploader_3Button" class="uploaderButton current">Change</button></a>
									<a href="#delete_3"><button id="delete_3Button" class="uploaderButton" <?php if($image_3 == $defaultImage) { echo "disabled"; } ?>>Delete</button></a>
								</div>
								<div id="image_3" class="imageHolder" <?php if($image_3 == $defaultImage) { echo 'style="display: none;"'; } ?>>
									<img class="<?php echo $image_1_aspect; ?>" src="<?php echo $image_3; ?>" />
								</div>
								<div id="uploader_3" class="html5fileupload" <?php if($image_3 != $defaultImage) { echo 'style="display: none;"'; } ?> data-url="parts/html5uploader/html5fileupload.php" data-remove-done="true" data-autostart="true" data-random-name="true" data-random-name-length="14" data-valid-extensions="png,jpeg,JPG,jpg,gif">
								  	<input type="file" name="logo" />
								</div>
							</div>
							
							<div class="slideImageUploader">
								<div class="dropButtonHolder">
									<a href="#image_4"><button id="image_4Button" class="uploaderButton">Picture</button></a>
									<a href="#uploader_4"><button id="uploader_4Button" class="uploaderButton current">Change</button></a>
									<a href="#delete_4"><button id="delete_4Button" class="uploaderButton" <?php if($image_4 == $defaultImage) { echo "disabled"; } ?>>Delete</button></a>
								</div>
								<div id="image_4" class="imageHolder" <?php if($image_4 == $defaultImage) { echo 'style="display: none;"'; } ?>>
									<img class="<?php echo $image_1_aspect; ?>" src="<?php echo $image_4; ?>" />
								</div>
								<div id="uploader_4" class="html5fileupload" <?php if($image_4 != $defaultImage) { echo 'style="display: none;"'; } ?> data-url="parts/html5uploader/html5fileupload.php" data-remove-done="true" data-autostart="true" data-random-name="true" data-random-name-length="14" data-valid-extensions="png,jpeg,JPG,jpg,gif">
								  	<input type="file" name="logo" />
								</div>
							</div>
							
							<div class="slideImageUploader">
								<div class="dropButtonHolder">
									<a href="#image_5"><button id="image_5Button" class="uploaderButton">Picture</button></a>
									<a href="#uploader_5"><button id="uploader_5Button" class="uploaderButton current">Change</button></a>
									<a href="#delete_5"><button id="delete_5Button" class="uploaderButton" <?php if($image_5 == $defaultImage) { echo "disabled"; } ?>>Delete</button></a>
								</div>
								<div id="image_5" class="imageHolder" <?php if($image_5 == $defaultImage) { echo 'style="display: none;"'; } ?>>
									<img class="<?php echo $image_1_aspect; ?>" src="<?php echo $image_5; ?>" />
								</div>
								<div id="uploader_5" class="html5fileupload" <?php if($image_5 != $defaultImage) { echo 'style="display: none;"'; } ?> data-url="parts/html5uploader/html5fileupload.php" data-remove-done="true" data-autostart="true" data-random-name="true" data-random-name-length="14" data-valid-extensions="png,jpeg,JPG,jpg,gif">
								  	<input type="file" name="logo" />
								</div>
							</div>
							
							<div class="slideImageUploader">
								<div class="dropButtonHolder">
									<a href="#image_6"><button id="image_6Button" class="uploaderButton">Picture</button></a>
									<a href="#uploader_6"><button id="uploader_6Button" class="uploaderButton current">Change</button></a>
									<a href="#delete_6"><button id="delete_6Button" class="uploaderButton" <?php if($image_6 == $defaultImage) { echo "disabled"; } ?>>Delete</button></a>
								</div>
								<div id="image_6" class="imageHolder" <?php if($image_6 == $defaultImage) { echo 'style="display: none;"'; } ?>>
									<img class="<?php echo $image_6_aspect; ?>" src="<?php echo $image_6; ?>" />
								</div>
								<div id="uploader_6" class="html5fileupload" <?php if($image_6 != $defaultImage) { echo 'style="display: none;"'; } ?> data-url="parts/html5uploader/html5fileupload.php" data-remove-done="true" data-autostart="true" data-random-name="true" data-random-name-length="14" data-valid-extensions="png,jpeg,JPG,jpg,gif">
								  	<input type="file" name="logo" />
								</div>
							</div>
							
							<div class="slideImageUploader">
								<div class="dropButtonHolder">
									<a href="#image_7"><button id="image_7Button" class="uploaderButton">Picture</button></a>
									<a href="#uploader_7"><button id="uploader_7Button" class="uploaderButton current">Change</button></a>
									<a href="#delete_7"><button id="delete_7Button" class="uploaderButton" <?php if($image_7 == $defaultImage) { echo "disabled"; } ?>>Delete</button></a>
								</div>
								<div id="image_7" class="imageHolder" <?php if($image_7 == $defaultImage) { echo 'style="display: none;"'; } ?>>
									<img class="<?php echo $image_7_aspect; ?>" src="<?php echo $image_7; ?>" />
								</div>
								<div id="uploader_7" class="html5fileupload" <?php if($image_7 != $defaultImage) { echo 'style="display: none;"'; } ?> data-url="parts/html5uploader/html5fileupload.php" data-remove-done="true" data-autostart="true" data-random-name="true" data-random-name-length="14" data-valid-extensions="png,jpeg,JPG,jpg,gif">
								  	<input type="file" name="logo" />
								</div>
							</div>
						</div>
					</div>
					
					<div class="inputContainer">
						<h2>Business Description</h2>
						<div class="descHolder">
							<textarea name="description"><?php if(isset($description) and $description != "") { echo $description; } ?></textarea>
						</div>
					</div>
					
					<div id="termsHolder">
						<?php if(isset($terms) and $terms == "on") { ?>
							<input id="terms" type="checkbox" name="terms" checked/>
						<?php } else { ?>
							<input id="terms" type="checkbox" name="terms" />
						<?php } ?>
						
						<span>I agree to the terms and conditions.</span>
					</div>
					
					<div id="submitHolder">
						<button id="submitButton">Next</button>
					</div>
				</form>
				
				<?php require_once("parts/footer/footer.php"); ?>
			</div>
		</div>
	</body>
</html>