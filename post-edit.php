<?php session_start();

if(!isset($_COOKIE['user'])) {
	header("Location:Home");
} else {
	$user = $_COOKIE['user'];
}

$user = explode("_", $user);

$user = $user[0];

require("config/general_db.php");

$post_id = $_GET['p'];

if($stmt = mysqli_prepare($dbcg, "SELECT userid FROM posts WHERE id = ?")) {
	mysqli_stmt_bind_param($stmt, "i", $post_id);
	mysqli_stmt_execute($stmt);
	$meta = $stmt->result_metadata();
	
    while ($field = $meta->fetch_field()) {
	  	$parameters[] = &$row[$field->name];
	}
	
	call_user_func_array(array($stmt, 'bind_result'), $parameters);
	
	while ($stmt->fetch()) {
	  	foreach($row as $key => $val) {
	    	$userTest = $val;
	  	}
	}
	
	$parameters = "";
	
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

if($user != $userTest) {
	header("Location:Home");
}

if(isset($_POST['title'])) {
	foreach($_SESSION['postEdit'] as $key => $value) {
		${$key} = $value;
	}
	
	foreach($_POST as $key => $value) {
		if(strpos($key, 'image') === false) {
			${$key} = $value;
		}
	}
	
	if($privateSeller == "on") {
		$seller = "Private Seller";
	}
	//18
	if($stmt = mysqli_prepare($dbcg,
		"UPDATE posts SET 
		title = ?,
		cur = ?,
		category_1 = ?,
		category_2 = ?,
		category_3 = ?,
		category_4 = ?,
		category_5 = ?,
		subcategory_1 = ?,
		subcategory_2 = ?,
		subcategory_3 = ?,
		subcategory_4 = ?,
		subcategory_5 = ?,
		price = ?,
		priceType = ?,
		location = ?,
		pic = ?,
		country = ?,
		blurb = ?
		WHERE id = ?")) {
		
	    mysqli_stmt_bind_param($stmt,
	    	"ssssssssssssssssssi",
	   		$title,
	   		$cur,
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
	   		$price,
	   		$priceType,
	   		$location,
	   		$image_1,
	   		$country,
	   		$blurb,
			$post_id);
		
	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_close($stmt);
	}
	//11
	if($stmt = mysqli_prepare($dbcg,
		"UPDATE postinfo SET 
		content = ?,
		image_1 = ?,
		image_2 = ?,
		image_3 = ?,
		image_4 = ?,
		image_5 = ?,
		image_6 = ?,
		seller_name = ?,
		phone = ?,
		email = ?
		WHERE post_id = ?")) {
		
	    mysqli_stmt_bind_param($stmt,
	    	"ssssssssssi",
	   		$description,
	   		$image_1,
	   		$image_2,
	   		$image_3,
	   		$image_4,
	   		$image_5,
	   		$image_6,
	   		$seller,
	   		$phone,
	   		$email,
			$post_id);
		
	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_close($stmt);
	}
	
	header("Location:Home");
}

if($stmt = mysqli_prepare($dbcg, "SELECT * FROM posts WHERE id = ?")) {
	mysqli_stmt_bind_param($stmt, "i", $post_id);
	mysqli_stmt_execute($stmt);
	$meta = $stmt->result_metadata();
	
    while ($field = $meta->fetch_field()) {
	  	$parameters[] = &$row[$field->name];
	}
	
	call_user_func_array(array($stmt, 'bind_result'), $parameters);
	
	while ($stmt->fetch()) {
	  	foreach($row as $key => $val) {
	    	${$key} = $val;
	  	}
	}
	
	$parameters = "";
	
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

if($stmt = mysqli_prepare($dbcg, "SELECT * FROM postinfo WHERE id = ?")) {
	mysqli_stmt_bind_param($stmt, "i", $post_id);
	mysqli_stmt_execute($stmt);
	$meta = $stmt->result_metadata();
	
    while ($field = $meta->fetch_field()) {
	  	$parameters[] = &$row[$field->name];
	}
	
	call_user_func_array(array($stmt, 'bind_result'), $parameters);
	
	while ($stmt->fetch()) {
	  	foreach($row as $key => $val) {
	    	${$key} = $val;
	  	}
	}
	
	$parameters = "";
	
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

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
		
		if($x['id'] == 7 or $x['id'] == 16 or $x['id'] == 22 or $x['id'] == 23) {} else {
			$categories[$x['label']] = $x;
		}
		
	  	
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
    if($stmt = mysqli_prepare($dbcg, "SELECT id, subcatname FROM subcategory WHERE catid = ? ORDER BY pos")) {
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
				echo '<option value="'.$x['id'].'" selected>'.$x['subcatname'].'</option>';
			} else {
				echo '<option value="'.$x['id'].'">'.$x['subcatname'].'</option>';
			}
		}
		
	    mysqli_stmt_fetch($stmt);
	    mysqli_stmt_close($stmt);
	}
}

if(!isset($_SESSION['postEdit'])) {
	$_SESSION['postEdit'] = array("image_1"=>$image_1, "image_2"=>$image_2, "image_3"=>$image_3, "image_4"=>$image_4, "image_5"=>$image_5, "image_6"=>$image_6);
} else {
	foreach($_SESSION['postEdit'] as $key => $value) {
		if(strpos($key, 'image') === false) {
			${$key} = $value;
		}
	}
}

$defaultImage = "img/testplane.jpg";
$defaultAspect = "imageWidth";

for($i = 1; $i < 7; $i++) {
	if(${"image_".$i} == "") {
		${"image_".$i} = $defaultImage;
		${"image_".$i."_aspect"} = $defaultAspect;
	} else {
		${"image_".$i."_aspect"} = substr(${"image_".$i}, 0);
		${"image_".$i} = "image/user/".${"image_".$i};
		
		if(${"image_".$i."_aspect"}[0] == 0) {
			${"image_".$i."_aspect"} = "imageHeight";
		} elseif(${"image_".$i."_aspect"}[0] == 1) {
			${"image_".$i."_aspect"} = "imageWidth";
		}
	}
	
	if(isset($_SESSION['postEdit']['image_'.$i]) and $_SESSION['postEdit']['image_'.$i] != "") {
		${"image_".$i} = $_SESSION['postEdit']['image_'.$i];
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
		<title>Post Edit</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require_once("config/main-css.php"); ?>
		
		<!-- Slider js and css -->
		<link rel="stylesheet" type="text/css" href="css/post-edit.css" />
		<link rel="stylesheet" type="text/css" href="parts/html5uploader/css/html5fileupload.css" />
		<script src="parts/tinymce/jquery.tinymce.min.js"></script>
		<script src="parts/tinymce/tinymce.min.js"></script>
		<script src="parts/html5uploader/js/html5fileupload.min.js"></script>
		<script src="js/jquery.number.min.js"></script>
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
				$('#price').bind("cut copy paste",function(e) {
			    	e.preventDefault();
			   	});
				
				$('#price').number(true, 2);
				
				$('#privateSeller').change(function() {
					if($(this).is(':checked')) {
				        $("#sellerHolder").addClass("disabled");
				        $("#seller").prop('disabled', true);
				    } else {
				        $("#sellerHolder").removeClass("disabled");
				        $("#seller").prop('disabled', false);
				    }
				});
				
				$('input[type=radio][name=priceType]').change(function() {
					var value = this.value;
					
			        if(value == 'curPrice') {
			            $("#textPriceHolder").addClass("disabled");
			            $("#curPriceHolder").removeClass("disabled");
			            $("#priceText").prop('disabled', true);
			            $("#price").prop('disabled', false);
			            $("#cur").prop('disabled', false);
			        } else if (value == 'textPrice') {
			        	$("#curPriceHolder").addClass("disabled");
			            $("#textPriceHolder").removeClass("disabled");
			            $("#price").prop('disabled', true);
			            $("#cur").prop('disabled', true);
			            $("#priceText").prop('disabled', false);
			        }
			    });
				
				$(".categoryPicker").change(function() {
					var category = this.value,
						split = this.id.split('_'),
						dropdownId = split[1],
						subCat = "";
					
					$.post("ajax/subcategory-changer.php", {category: category})
					.done(function(data) {
						$("#subCategory_" + dropdownId).html(data);
					});
				});
				
				<?php for($i = 1; $i < 7; $i++) { ?>
					$('#uploader_<?php echo $i; ?>').html5fileupload({
						onAfterStartSuccess: function(response) {
							var id = <?php echo $i; ?>,
								typeInsert = "postEdit",
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
		   	 				typeInsert = "postEdit";
		   	 			
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
				
				<form id="formWrapper" action="Post-Edit" method="post">
					<h1>Post Edit</h1>
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
					
					<?php $priceCurRadio = "Checked";
					$priceTextRadio = "";
					$priceCurDisabled = "";
					$priceTextDisabled = "disabled";
					
					if(isset($priceType) and $priceType == "curPrice") {
						$priceCurRadio = "Checked";
						$priceTextRadio = "";
						$priceCurDisabled = "";
						$priceTextDisabled = "disabled";
					} elseif(isset($priceType) and $priceType == "textPrice") {
						$priceCurRadio = "";
						$priceTextRadio = "Checked";
						$priceCurDisabled = "disabled";
						$priceTextDisabled = "";
					} ?>
					
					<div class="inputContainer">
						<h2>Post Details</h2>
						<div class="inputRow"><label for="title">Title:</label><input id="title" type="text" name="title"<?php if(isset($title)) { echo ' value="'.$title.'"'; } ?> /></div>
						<div id="curPriceHolder" class="inputRow <?php echo $priceCurDisabled; ?>">
							<input id="priceRadio" type="radio" name="priceType" value="curPrice" <?php echo $priceCurRadio; ?> />
							<label class="smallLabel" for="cur">Currency/Price:</label>
							<select id="cur" name="cur" <?php echo $priceCurDisabled; ?>>
								<option value="pound"<?php if(isset($cur) and $cur == "pound" OR !isset($cur)) { echo " selected"; } ?>>&#163;</option>
								<option value="euro"<?php if(isset($cur) and $cur == "euro") { echo " selected"; } ?>>&#8364;</option>
							</select>
							<input id="price" type="text" name="price"<?php if(isset($price)) { echo ' value="'.$price.'"'; } ?> / <?php echo $priceCurDisabled; ?>>
						</div>
						
						<div id="textPriceHolder" class="inputRow <?php echo $priceTextDisabled; ?>">
							<input id="priceTextRadio" type="radio" name="priceType" value="textPrice" <?php echo $priceTextRadio; ?> />
							<label class="smallLabel" for="price">Price (Text):</label>
							<input id="priceText" type="text" name="priceText"<?php if(isset($priceText)) { echo ' value="'.$priceText.'"'; } ?> <?php echo $priceTextDisabled; ?> />
						</div>
						
						<div class="inputRow"><label for="countie">Country:</label>
							<select name="country">
								<?php foreach ($countries as $key => $value) { ?>
									<option value="<?php echo $value['name']; ?>"<?php if(isset($country) and $country == $value['name']) { echo " selected"; } ?>><?php echo $value['name']; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="inputRow"><label for="location">Location:</label><input id="location" type="text" name="location"<?php if(isset($location)) { echo ' value="'.$location.'"'; } ?> /></div>
						<div class="inputRow"><label for="blurb">Description (250 chars max):</label><input id="blurb" type="text" name="blurb"<?php if(isset($blurb)) { echo ' value="'.$blurb.'"'; } ?> /></div>
					</div>
					<div class="inputContainer">
						<!-- We need to have 2 types here : For Indv and For Businesses -->
						<h2>Post Contact Information</h2>
						<div id="sellerHolder" class="inputRow<?php if(isset($privateSeller) and $privateSeller == "on") { echo " disabled"; } ?>"><label for="seller">Seller:</label><input id="seller" type="text" name="seller"<?php if(isset($seller)) { echo ' value="'.$seller.'"'; } ?> <?php if(isset($privateSeller) and $privateSeller == "on") { echo "disabled"; } ?>/></div>
						<div class="inputRow"><label for="privateSeller">Private Seller:</label><input id="privateSeller" type="checkbox" name="privateSeller"<?php if(isset($privateSeller) and $privateSeller == "on") { echo "checked"; } ?> /></div>
						<div class="inputRow"><label for="businessEmail">Phone:</label><input id="phone" type="text" name="phone"<?php if(isset($phone)) { echo ' value="'.$phone.'"'; } ?> /></div>
						<div class="inputRow"><label for="website">Email Address:</label><input id="email" type="text" name="email"<?php if(isset($email)) { echo ' value="'.$email.'"'; } ?> /></div>
					</div>
					
					<div class="inputContainer">
						<h2>Post Pictures</h2>
						<div class="slideImageHolder">
							<div class="slideImageUploader">
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
									<img class="<?php echo $image_3_aspect; ?>" src="<?php echo $image_3; ?>" />
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
									<img class="<?php echo $image_4_aspect; ?>" src="<?php echo $image_4; ?>" />
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
									<img class="<?php echo $image_5_aspect; ?>" src="<?php echo $image_5; ?>" />
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
						</div>
					</div>
					
					<div class="inputContainer">
						<h2>Post Description</h2>
						<div class="descHolder">
							<textarea name="description"><?php if(isset($content) and $content != "") { echo $content; } ?></textarea>
						</div>
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