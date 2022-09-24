<?php require_once("config/account_db.php");
require_once("config/general_db.php");
require_once("parts/posts/post-card.php");

if(isset($_GET['limit'])) {
	setcookie(
	  "display_number",
	  $_GET['limit'],
	  time() + (10 * 365 * 24 * 60 * 60)
	);
	
	$displayNumber = $_GET['limit'];
} elseif(isset($_COOKIE['display_number']) and !isset($_GET['limit'])) {
	$displayNumber = $_COOKIE['display_number'];
} elseif(!isset($_COOKIE['display_number']) and !isset($_GET['limit'])) {
	$displayNumber = 30;
}

if(isset($_GET['p'])) {
	$page = $_GET['p'];
} else {
	$page = 1;
}

/*-------------------------------*/

$counter = 1;

if(isset($_GET['c'])) {
	$cat = $_GET['c'];
} else {
	$cat = 0;
}

if(isset($_GET['cs'])) {
	$sub = $_GET['cs'];
} else {
	$sub = 0;
}

if(isset($_GET['b'])) {
	$bus = $_GET['b'];
} else {
	$bus = 0;
}

if(isset($_GET['bs'])) {
	$bsub = $_GET['bs'];
} else {
	$bsub = 0;
}

if(isset($_GET['s'])) {
	$search = "%".str_replace('%', ' ', $_GET['s'])."%";
} else {
	$search = 0;
}

if(isset($_GET['sort'])) {
	$sort = $_GET['sort'];
	
	if($sort == "newFirst") {
		$sortStatement = "ORDER BY date_post DESC";
	} elseif($sort == "oldFirst") {
		$sortStatement = "ORDER BY date_post ASC";
	} elseif($sort == "priceAsc") {
		$sortStatement = "ORDER BY price ASC";
	} elseif($sort == "priceDesc") {
		$sortStatement = "ORDER BY price DESC";
	} 
} else {
	$sort = "newFirst";
	$sortStatement = "ORDER BY date_post DESC";
}

if(isset($_GET['country'])) {
	$country = $_GET['country'];
	$countryStatment = "country = '".$country."' AND";
} else {
	$country = "";
	$countryStatment = "";
}

$date = date("Ymdhms");

if(isset($_GET['s'])) {
	$catLimit = "";
	
	if($cat != 0) {
		$catLimit = "? IN(category_1, category_2, category_3, category_4, category_5) AND";
		$catLimitCount = "$cat IN(category_1, category_2, category_3, category_4, category_5) AND";
	} else {
		$catLimitCount = "";
	}
	
	$countCheck = "SELECT COUNT(*) FROM posts WHERE date_expired > $date AND deleted = 0 AND $catLimitCount
	(mask LIKE '$search' OR title LIKE '$search' OR price LIKE '$search' OR location LIKE '$search' OR country LIKE '$search' OR provence LIKE '$search' OR blurb LIKE '$search' OR business_name LIKE '$search'
	OR address LIKE '$search' OR postcode LIKE '$search' OR country LIKE '$search' OR provence LIKE '$search' OR content LIKE '$search')";
	
	$total = mysqli_query($dbcg, $countCheck);
	$total = mysqli_fetch_assoc($total);
	
	$total = $total['COUNT(*)'];
	
	$numPages = ceil($total / $displayNumber);
	$offset = ($page - 1)  * $displayNumber;
	
	$postStatment = "SELECT * FROM posts WHERE date_expired > ? AND deleted = 0 AND $catLimit $countryStatment
	(mask LIKE ? OR title LIKE ? OR price LIKE ? OR location LIKE ? OR country LIKE ? OR provence LIKE ? OR blurb LIKE ? OR business_name LIKE ? OR address LIKE ?
	OR postcode LIKE ? OR country LIKE ? OR provence LIKE ? OR content LIKE ?) $sortStatement LIMIT $displayNumber OFFSET $offset";
	
	if($stmt = mysqli_prepare($dbcg, $postStatment)) {
		if($cat != 0) {
			mysqli_stmt_bind_param($stmt, "sssssssssssssss", $date, $cat, $search, $search, $search, $search, $search, $search, $search, $search, $search, $search, $search, $search, $search);
		} else {
			mysqli_stmt_bind_param($stmt, "ssssssssssssss", $date, $search, $search, $search, $search, $search, $search, $search, $search, $search, $search, $search, $search, $search);
		}
		
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
		  	$posts[] = $x;
		}
		
		$parameters = "";
		$row = "";
		$x = "";
	    mysqli_stmt_fetch($stmt);
	    mysqli_stmt_close($stmt);
	}
}

if($bus != 0 OR $cat != 0 AND !isset($_GET['s'])) {
	if($bus != 0) {
		$type = "AND type = 1";
		$mainParam = $bus;
	} else {
		$type = "AND type = 0";
		$mainParam = $cat;
	}
	
	$extraQueries = "date_expired > ? AND deleted = 0 AND ";
	$countExtraQueries = "date_expired > $date AND deleted = 0 AND ";
	
	if($sub != 0) {
		$subParam = $sub;
	}
	
	if(isset($subParam)) {
		$subQueries = "AND ? IN(subCategory_1, subCategory_2, subCategory_3, subCategory_4, subCategory_5)";
		$countSubQueries = "AND $subParam IN(subCategory_1, subCategory_2, subCategory_3, subCategory_4, subCategory_5)";
	} else {
		$subQueries = "";
		$countSubQueries = "";
	}
	
	$countCheck = "SELECT COUNT(*) FROM posts WHERE $countExtraQueries
	$mainParam IN(category_1, category_2, category_3, category_4, category_5) $type $countSubQueries";
	
	$total = mysqli_query($dbcg, $countCheck);
	$total = mysqli_fetch_assoc($total);
	
	$total = $total['COUNT(*)'];
	
	$numPages = ceil($total / $displayNumber);
	$offset = ($page - 1)  * $displayNumber;
	
	$statement = "SELECT * FROM posts WHERE $extraQueries $countryStatment
	? IN(category_1, category_2, category_3, category_4, category_5) $subQueries $type $sortStatement LIMIT $displayNumber OFFSET $offset";
	
	if($stmt = mysqli_prepare($dbcg, $statement)) {
		if($sub != 0) {
			mysqli_stmt_bind_param($stmt, "sii", $date,$mainParam,$subParam);
		} else {
			mysqli_stmt_bind_param($stmt, "si", $date,$mainParam);
		}
	 	
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
		  	$posts[] = $x;
		}
		
		$parameters = "";
		$row = "";
		$x = "";
		
	    mysqli_stmt_fetch($stmt);
	    mysqli_stmt_close($stmt);
	}
} ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8"/>
		<title>Posts</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php require_once("config/main-css.php"); ?>
		
		<!-- Slider js and css -->
		<link rel="stylesheet" type="text/css" href="css/posts.css" />
		<link rel="stylesheet" type="text/css" href="parts/posts/post-card.css" />
		<link rel="stylesheet" type="text/css" href="parts/nav/third-nav/third-nav.css" />
		<link rel="stylesheet" type="text/css" href="parts/nav/pagination/pagination.css" />
		<script>
			$(document).ready(function() {
				$(".postContact").click(function(e) {
					e.preventDefault();
					
					var mask = this.id
					
					$.post("ajax/post-contact.php", {mask: mask})
					.done(function(data) {
						$("#" + mask).replaceWith("<p>" + data + "</p>");
					});
				});
				
				$("#postSort").change(function() {
					var value = this.value,
						url = String(window.location),
						tempArray = url.split("?"),
				    	baseUrl = tempArray[0],
				    	getArray = tempArray[1];
				    
				    if(getArray) {
				    	var sortPresent = 0;
				    		tempArray = getArray.split("&"),
				    		finalGet = "";
				    	
				    	for(var i=0; i<tempArray.length; i++) {
				    		if(tempArray[i].split('=')[0] == "sort") {
				                tempArray[i] = "sort=" + value;
				                sortPresent = 1;
				           	}
				           	
				           	if(i == 0) {
			                	finalGet += "?" + tempArray[i];
			                } else {
			                	finalGet += "&" + tempArray[i];
			                }
				    	}
				    	
				    	if(sortPresent == 0) {
				    		finalGet += "&sort=" + value;
				    	}
				    }
				    
				    var url = baseUrl + finalGet;
				    
					window.location.href = url;
				});
				
				$("#countrieSort").change(function() {
					var value = this.value,
						url = String(window.location),
						tempArray = url.split("?"),
				    	baseUrl = tempArray[0],
				    	getArray = tempArray[1];
				    
				    if(getArray) {
				    	var sortPresent = 0;
				    		tempArray = getArray.split("&"),
				    		finalGet = "";
				    	
				    	for(var i=0; i<tempArray.length; i++) {
				    		if(tempArray[i].split('=')[0] == "country") {
				                tempArray[i] = "country=" + value;
				                sortPresent = 1;
				           	}
				           	
				           	if(i == 0) {
			                	finalGet += "?" + tempArray[i];
			                } else {
			                	finalGet += "&" + tempArray[i];
			                }
				    	}
				    	
				    	if(sortPresent == 0) {
				    		finalGet += "&country=" + value;
				    	}
				    }
				    
				    var url = baseUrl + finalGet;
				    
					window.location.href = url;
				});
				
				$("#postLimit").change(function() {
					var value = this.value,
						url = String(window.location),
						tempArray = url.split("?"),
				    	baseUrl = tempArray[0],
				    	getArray = tempArray[1];
				    
				    if(getArray) {
				    	var sortPresent = 0;
				    		tempArray = getArray.split("&"),
				    		finalGet = "";
				    	
				    	for(var i=0; i<tempArray.length; i++) {
				    		if(tempArray[i].split('=')[0] == "limit") {
				                tempArray[i] = "limit=" + value;
				                sortPresent = 1;
				           	}
				           	
				           	if(i == 0) {
			                	finalGet += "?" + tempArray[i];
			                } else {
			                	finalGet += "&" + tempArray[i];
			                }
				    	}
				    	
				    	if(sortPresent == 0) {
				    		finalGet += "&limit=" + value;
				    	}
				    }
				    
				    var url = baseUrl + finalGet;
				    
					window.location.href = url;
				});
			});
		</script>
	</head>
		
	<body>
		<div id="outerWrapper">
			<div id="wrapper">
				<!--- NAV ----->
				<?php require_once("parts/nav/upper-nav/upper-nav.php");
					  require_once("parts/nav/lower-nav/lower-nav.php");
					  require_once("parts/nav/third-nav/third-nav.php");
					  if($total != 0) {
					  	require_once("parts/nav/pagination/pagination.php");
					  } ?>
				
				<div class="Post-Container">
					<div class="Post-Wrapper">
						<?php if(isset($posts)) {
							foreach($posts as $key => $value) {
								if(isset($value['title'])) {
									itemPost($counter, $value['title'], $value['pic'], $value['blurb'], $value['cur'], $value['price'], $value['id']."_".$value['userid']."_".$value['user_type'], $value['location']);
								} else {
									businessPost($counter, $value['business_name'], $value['image_1'], $value['userid']."_1", $value['address'], $value['website']);
								}
								
								$counter++;
								global $counter;
							}
						} ?>
					</div>	
				</div>
				
				<?php require_once("parts/footer/footer.php"); ?>
			</div>
		</div>
	</body>
</html>