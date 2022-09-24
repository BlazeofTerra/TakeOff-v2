<?php //uses the $posts array from posts.php and gets $categories from upper nav
$link = "posts.php";
$numGets = count($_GET);
$count = 1;

if(isset($_GET['p'])) {
	$numGets = $numGets - 1;
	$curPage = $_GET['p'];
} else {
	$curPage = 1;
}

$prevPage = $curPage - 1;
$nextPage = $curPage + 1;

foreach($_GET as $key => $value) {
	if($key != "p") {
		if($count == 1) {
			$link .= "?";
		}
		
		$link .= "$key=$value";
		
		if($count != $numGets) {
			$link .= "&";
		}
		
		$count++;
	}
} ?>
<div id="paginationBar">
	<div id="sort">
		<select id="postSort">
			<option value="newFirst" <?php if($sort == "newFirst") { echo "selected"; } ?>>New first</option>
			<option value="oldFirst" <?php if($sort == "oldFirst") { echo "selected"; } ?>>Old first</option>
			<option value="priceAsc" <?php if($sort == "priceAsc") { echo "selected"; } ?>>Price (Asc)</option>
			<option value="priceDesc" <?php if($sort == "priceDesc") { echo "selected"; } ?>>Price (Desc)</option>
		</select>
		
		<?php if($stmt = mysqli_prepare($dbcg, "SELECT id, label, name FROM countries ORDER BY name")) {
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
			  	$countries[$x['name']] = $x;
			}
			
			$parameters = "";
			
		    mysqli_stmt_fetch($stmt);
		    mysqli_stmt_close($stmt);
		} ?>
		
		<select id="countrieSort">
			<?php foreach($countries as $key => $value) { ?>
				<option value="<?php echo $value['name']; ?>" <?php if($country == $value['name']) { echo "selected"; } ?>><?php echo $value['name']; ?></option>
			<?php } ?>
		</select>
		
		<select id="postLimit">
			<option value="30" <?php if($displayNumber == "30") { echo "selected"; } ?>>30</option>
			<option value="60" <?php if($displayNumber == "60") { echo "selected"; } ?>>60</option>
			<option value="90" <?php if($displayNumber == "90") { echo "selected"; } ?>>90</option>
			<option value="120" <?php if($displayNumber == "120") { echo "selected"; } ?>>120</option>
		</select>
	</div>
	
	<div id="pagination">
		<ul>
			<?php if($curPage != 1) { ?>
				<a href="<?php echo $link."&p=1" ?>"><li><<</li></a>
				<a href="<?php echo $link."&p=".$prevPage; ?>"><li><</li></a>
			<?php } ?>
			
			<?php for($i=1; $i < $numPages + 1; $i++) { ?>
				<a href="<?php echo $link."&p=".$i; ?>"><li><?php echo $i; ?></li></a>
			<?php } ?>
			
			<?php if($curPage != $numPages) { ?>
				<a href="<?php echo $link."&p=".$nextPage; ?>"><li>></li></a>
				<a href="<?php echo $link."&p=".$numPages ?>"><li>>></li></a>
			<?php } ?>
		</ul>
	</div>
</div>