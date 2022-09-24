<?php if($postInfo['image_1'] == "" and $postInfo['image_2'] == "" and $postInfo['image_3'] == "" and $postInfo['image_4'] == "" and $postInfo['image_5'] == "" and $postInfo['image_6'] == "") {
	
} else { ?>
	<div class="rslides-container">
		<ul class="rslides">
			<?php for($i=1; $i < 7; $i++) { 
				if($postInfo['image_'.$i] != "") {
					$aspect = substr($postInfo['image_'.$i], 0);
		
					if($aspect[0] == 0) {
						$aspect = "imageHeight";
					} elseif($aspect[0] == 1) {
						$aspect = "imageWidth";
					} ?>
					<li>
						<picture>
			  			    <!--<source media="(min-width: 1080px)" srcset="img/sliders/index/1080/slide2.png">
			  			    <source media="(min-width: 1024px)" srcset="img/sliders/index/1024/slide2.png">
			  			    <source media="(min-width: 768px)" srcset="img/sliders/index/768/slide2.png">
			  			    <source media="(min-width: 425px)" srcset="img/sliders/index/425/slide2.png">
			  			    <source media="(min-width: 375px)" srcset="img/sliders/index/375/slide2.png">
							<source media="(min-width: 320px)" srcset="img/sliders/index/320/slide2.png">-->
				  			<img class="<?php echo $aspect; ?>" src="image/user/<?php echo $postInfo['image_'.$i] ?>" alt="">
				  		</picture>
			  		</li>
				<?php }
			} ?>
		</ul>
		
		<script>
			$(".rslides").responsiveSlides({
			  	speed: 700,            // Integer: Speed of the transition, in milliseconds
			  	timeout: 6000,          // Integer: Time between slide transitions, in milliseconds
			  	auto: true,
		        pager: true,
		        nav: true,
	        	namespace: "centered-btns"
			});
		</script>
	</div>
<?php } ?>