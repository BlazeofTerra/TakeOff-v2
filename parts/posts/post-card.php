<?php function itemPost($counter, $title, $pic, $blurb, $cur, $price, $mask, $location) {
	$x2 = $x3 = "";
	if($counter % 2 == 0) { $x2 = " Post-Right-2"; } 
	if($counter % 3 == 0) { $x3 = " Post-Right-3"; }
	
	$aspect = substr($pic, 0);
		
	if($aspect[0] == 0) {
		$aspect = "imageHeight";
	} elseif($aspect[0] == 1) {
		$aspect = "imageWidth";
	}
	
	$maskBreak = explode('_',$mask,2); ?>
    <a href="post-page.php?a=<?php echo $maskBreak[0]; ?>"><div class="Post<?php echo $x2.$x3; ?>">
		<h3 class="Post-Header"><?php echo $title; ?></h3>
		<div class="Post-Top">
			<div class="Post-Img-Holder">
				<img class="<?php echo $aspect; ?>" src="<?php echo "image/user/".$pic; ?>" />
			</div>
			<div class="Post-Desc-Background"></div>
			<div class="Post-Desc">
				<p><?php echo $blurb; ?></p>
			</div>
		</div>
		<div class="Post-Desc-Mobile">
			<p><?php echo $blurb; ?></p>
		</div>
		<div class="Post-Bottom">
			<p><?php $cur.$price; ?></p>
			<a id="<?php echo $maskBreak[1]."_".$maskBreak[2]; ?>" class="postContact" href=""><p>Click to view our phone number</p></a>
			<p><?php echo $location; ?></p>
			<div class="Post-Seller">
				<p>Comair Aircraft Sales</p>
			</div>
		</div>
	</div></a>
<?php } ?>

<?php function businessPost($counter, $title, $pic, $mask, $location, $website) {
    $x2 = $x3 = "";
	if($counter % 2 == 0) { $x2 = " Post-Right-2"; } 
	if($counter % 3 == 0) { $x3 = " Post-Right-3"; }
	
	$aspect = substr($pic, 0);
		
	if($aspect[0] == 0) {
		$aspect = "imageHeight";
	} elseif($aspect[0] == 1) {
		$aspect = "imageWidth";
	}
	
	$maskBreak = explode('_',$mask,0);  ?>
    <a href="business-page.php?a=<?php echo $maskBreak[0]; ?>"><div class="Post<?php echo $x2.$x3; ?>">
		<h3 class="Post-Header"><?php echo $title; ?></h3>
		<div class="Post-Top">
			<div class="Post-Img-Holder">
				<img class="<?php echo $aspect; ?>" src="<?php echo "image/user/".$pic; ?>" />
			</div>
			<div class="Post-Desc-Background"></div>
			<div class="Post-Desc">
				<p><?php //echo $blurb; ?></p>
			</div>
		</div>
		<div class="Post-Desc-Mobile">
			<p><?php //echo $blurb; ?></p>
		</div>
		<div class="Post-Bottom">
			<a id="<?php echo $maskBreak[1]."_".$maskBreak[2]; ?>" class="postContact" href=""><p>Click to view our phone number</p></a>
			<p><?php echo $location; ?></p>
			<p><?php echo $website; ?></p>
		</div>
	</div></a>
<?php } ?>

<?php // Post edit page
function editPost($counter, $title, $pic, $blurb, $cur, $price, $mask, $location) {
	$x2 = $x3 = "";
	if($counter % 2 == 0) { $x2 = " Post-Right-2"; } 
	if($counter % 3 == 0) { $x3 = " Post-Right-3"; }
	
	$aspect = substr($pic, 0);
		
	if($aspect[0] == 0) {
		$aspect = "imageHeight";
	} elseif($aspect[0] == 1) {
		$aspect = "imageWidth";
	}
	
	$maskBreak = explode('_',$mask,2); ?>
    <a href="post-page.php?a=<?php echo $maskBreak[0]; ?>"><div class="Post<?php echo $x2.$x3; ?>">
		<h3 class="Post-Header"><?php echo $title; ?></h3>
		<div class="Post-Top">
			<div class="Post-Img-Holder">
				<img class="<?php echo $aspect; ?>" src="<?php echo "image/user/".$pic; ?>" />
			</div>
			<div class="Post-Desc-Background"></div>
			<div class="Post-Desc">
				<p><?php echo $blurb; ?></p>
			</div>
		</div>
		<div class="Post-Desc-Mobile">
			<p><?php echo $blurb; ?></p>
		</div>
		<div class="Post-Bottom">
			<p><?php $cur.$price; ?></p>
			<a id="<?php echo $maskBreak[1]."_".$maskBreak[2]; ?>" class="postContact" href=""><p>Click to view our phone number</p></a>
			<p><?php echo $location; ?></p>
			<div class="editButtons">
				<button>Renew</button>
				<a href="post-edit.php?p=<?php echo $maskBreak[0]; ?>"><button>Edit</button></a>
				<button id="<?php echo $maskBreak[0]; ?>" class="postDelete">Delete</button>
			</div>
		</div>
	</div></a>
<?php } ?>