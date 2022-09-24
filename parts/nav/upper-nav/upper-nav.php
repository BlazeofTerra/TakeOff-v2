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
	
	while ($stmt->fetch()) {
	  	foreach($row as $key => $val) {
	    	$x[$key] = $val;
	  	}
	  	$categories[$x['label']] = $x;
	}
	
	$x = "";
	$row = "";
	$parameters = "";
	
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
} ?>
<div id="Upper-Nav-Bar">
	<div id="Left-Side">
		<img id="Logo" src="img/logos/logomk2.png">
		<p id="Logo-Text">
			 Take-Off Aviation Marketplace
		</p>
	</div>
	
	<form id="search" action="posts.php" method="get">
		<input id="searchIndex" type="text" name="s" />
		<select id="searchDropdown" name="c">
			<option value="all">All</option>
			<?php foreach ($categories as $key => $value) { ?>
				<option value="<?php echo $value['id']; ?>"><?php echo $value['label']; ?></option>
			<?php } ?>
		</select>
		<button id="searchImage" type="submit">
			<img src="img/search/search.png" alt="phonestuff">
		</button>
	</form>
	
	<div id="Right-Side-Upper">
		<ul id="Nav-Bar-Buttons-Upper" class="topnav">
			<a href="javascript:void(0);" onclick="hamburgerToggle(this)"><li class="icon">
		    	<div class="container">
				  	<div class="bar1"></div>
				  	<div class="bar2"></div>
				  	<div class="bar3"></div>
				</div>
		  	</li></a>
		  	<?php if($userInfo == 0) { ?>
		  		<a href="login.php"><li>Log In</li></a>
		  		<a href="register.php"><li>Register</li></a>
		  	<?php } else { ?>
		  		<li id="logOut" class="profileOption">Log Out</li>
		  		<a href="bus-edit.php"><li class="profileOption">Account Edit</li></a>
				<a href="office-reg.php"><li class="profileOption">Offices</li></a>
				<a href="rep-reg.php"><li class="profileOption">Representatives</li></a>
				<a href="my-posts.php?a=<?php echo $user; ?>"><li class="profileOption">My Posts</li></a>
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
</div>
