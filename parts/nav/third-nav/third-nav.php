<?php if(isset($_GET['c']) and $_GET['c'] != "all") {
	if($stmt = mysqli_prepare($dbcg, "SELECT id, subcatname FROM subcategory WHERE catid = ? ORDER BY pos")) {
		mysqli_stmt_bind_param($stmt, "i", $_GET['c']);
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
		  	$subCats[$x['subcatname']] = $x;
		}
		
		$x = "";
		$row = "";
		$parameters = "";
		
	    mysqli_stmt_fetch($stmt);
	    mysqli_stmt_close($stmt);
	} ?>
	<div id="thirdNav">
		<ul id="thirdNavButtons">
			<a href="posts.php?c=<?php echo $_GET['c'] ?>"><li>All</li></a>
		<?php foreach ($subCats as $key => $value) { ?>
			<a href="posts.php?c=<?php echo $_GET['c']."&cs=".$value['id']; ?>"><li><?php echo $value['subcatname']; ?></li></a>
		<?php } ?>
		</ul>
	</div>
<?php } ?>