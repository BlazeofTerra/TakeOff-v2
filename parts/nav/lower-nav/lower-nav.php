<?php require_once("config/general_db.php");

if(isset($_COOKIE['user'])) {
	$userInfo = $_COOKIE['user'];
	$userInfo = explode('_',$userInfo,2);
	
	$user = $userInfo['0'];
	$type = $userInfo['1'];
} else {
	$userInfo = 0;
}

if($stmt = mysqli_prepare($dbcg, "SELECT id, label, number_posts, number_businesses FROM category ORDER BY pos")) {
	mysqli_stmt_execute($stmt);
	$meta = $stmt->result_metadata();
	
    while ($field = $meta->fetch_field()) {
	  	$parameters[] = &$row[$field->name];
	}
	
	call_user_func_array(array($stmt, 'bind_result'), $parameters);
	
	$counter = 0;
	$section = 1;
	
	while ($stmt->fetch()) {
		if($counter % 8 == 0) {
			$counter = 0;
			$section++;
		}
		
	  	foreach($row as $key => $val) {
	    	$x[$key] = $val;
	  	}
	  	$categorieBox[$section][$x['label']] = $x;
		
		if($x['id'] == 7 or $x['id'] == 16 or $x['id'] == 22 or $x['id'] == 23) {} else {
			$classifieds[$section][$x['label']] = $x;
		}
		$counter++;
	}
	
	$x = "";
	$row = "";
	$parameters = "";
	
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
} ?>

<script>
	$(document).ready(function() {
		$("#logOut").click(function() {
			$.cookie('user', '', { expires: -1, path: '/TakeTwo'});
			window.location.href = 'Home';
		});
	});
</script>

<div id="LowerNavContainer">
	<div id="Lower-Nav-Bar">
		<ul id="Lower-Nav-Bar-Buttons">
			<a href="Home"><li id="">Home</li></a>
			<a href="javascript:void(0);" onclick="categoriePicker()"><li id="B-Checker">Businesses</li></a>
			<a href="javascript:void(0);" onclick="classifiedsPicker()"><li id="L-Checker">Classifieds</li></a>
		</ul>
	</div>
	
	<div id="Right-Side-Lower">
		<ul id="Nav-Bar-Buttons-Lower">
		  	<?php if($userInfo == 0) { ?>
		  		<a href="login.php"><li>Log In</li></a>
		  		<a href="register.php"><li>Register</li></a>
		  	<?php } else { ?>
		  		<li id="logOut">Log Out</li>
		  		<a href="bus-edit.php"><li>Account Edit</li></a>
				<a href="office-reg.php"><li>Offices</li></a>
				<a href="rep-reg.php"><li>Representatives</li></a>
				<a href="my-posts.php?a=<?php echo $user; ?>"><li>My Posts</li></a>
		  	<?php } ?>
		</ul>
		
		<script>
			$(document).ready(function() {
				$("#myProfile").click(function() {
					var classes = $("#myProfileOptions").attr("class");
					
					if(classes == "P-Hidden") {
						$("#myProfileOptions").removeClass("P-Hidden");
					} else {
						$("#myProfileOptions").addClass("P-Hidden");
					}
				});
			});
		</script>
		
		<!-- HAMBURGER LINK -->
		<script>
			function hamburgerToggle(x) {
				x.classList.toggle("change");
				
			    var x = document.getElementById("Nav-Bar-Buttons");
			    if (x.className === "topnav") {
			        x.className += " responsive";
			    } else {
			        x.className = "topnav";
			    }
			}
		</script>
	</div>
	
	<?php if($userInfo != 0) { ?>
		<div id="myProfileOptions" class="P-Hidden">
			<ul id="profileList">
				<a href="bus-edit.php"><li class="profileOption">Account Edit</li></a>
				<a href="office-reg.php"><li class="profileOption">Offices</li></a>
				<a href="rep-reg.php"><li class="profileOption">Representatives</li></a>
				<a href="my-posts.php?a=<?php echo $user; ?>"><li class="profileOption">My Posts</li></a>
			</ul>
		</div>
	<?php } ?>
	
	<div id="Business-Categories" class="B-Hidden">
		<?php foreach($categorieBox as $key => $sections) { ?>
			<div class="Category-Holder">
				<?php foreach($sections as $key => $catgories) { ?>
					<a href="posts.php?b=<?php echo $catgories['id']; ?>"><p><?php echo $catgories['label']; ?> - <?php echo $catgories['number_businesses']; ?></p></a>
				<?php } ?>
			</div>
		<?php } ?>
	</div>
	
	<div id="Listings-Categories" class="L-Hidden">
		<?php foreach($classifieds as $key => $sections) { ?>
			<div class="Category-Holder">
				<?php foreach($sections as $key => $catgories) { ?>
					<a href="posts.php?c=<?php echo $catgories['id']; ?>"><p><?php echo $catgories['label']; ?> - <?php echo $catgories['number_posts']; ?></p></a>
				<?php } ?>
			</div>
		<?php } ?>
	</div>
	
	<script>
		function categoriePicker() {
		    var y = document.getElementById("Business-Categories");
		    if(y.className === "B-Hidden") {
		        y.className += " B-Showing";
		    } else {
		        y.className = "B-Hidden";
		    }
		    
		    var x = document.getElementById("Listings-Categories");
		    if(x.className !== "L-Hidden") {
		        x.className = "L-Hidden";
		    }
		}
		
		function classifiedsPicker() {
		    var x = document.getElementById("Listings-Categories");
		    if(x.className === "L-Hidden") {
		        x.className += " L-Showing";
		    } else {
		        x.className = "L-Hidden";
		    }
		    
		    var y = document.getElementById("Business-Categories");
		    if(y.className !== "B-Hidden") {
		        y.className = "B-Hidden";
		    }
		}
		
		document.onclick = function(e) {
		    var businessC = document.getElementById('Business-Categories');
		    var listingsC = document.getElementById('Listings-Categories');
		    
		    var BCheck = document.getElementById('B-Checker');
		    var LCheck = document.getElementById('L-Checker');
		    
		    
		    if(e.target.id != businessC.id && e.target.id != BCheck.id && e.target.parentElement.id != businessC.id && e.target.parentElement.parentElement.id != businessC.id && businessC.className === "B-Hidden B-Showing") {
		    	businessC.className = "B-Hidden";
		    }
		    
		    if(e.target.id != listingsC.id && e.target.id != LCheck.id && e.target.parentElement.id != listingsC.id && e.target.parentElement.parentElement.id != listingsC.id && listingsC.className === "L-Hidden L-Showing") {
		    	listingsC.className = "L-Hidden";
		    }
		}
	</script>
</div>