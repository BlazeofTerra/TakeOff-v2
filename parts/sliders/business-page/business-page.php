<?php if($busInfo['image_2'] == "" and $busInfo['image_3'] == "" and $busInfo['image_4'] == "" and $busInfo['image_5'] == "" and $busInfo['image_6'] == "" and $busInfo['image_7'] == "") {
	
} else { ?>
	<div class="rslides-container">
		<ul class="rslides">
			<?php for($i=2; $i < 8; $i++) { 
				if($busInfo['image_'.$i] != "") {
					$aspect = substr($busInfo['image_'.$i], 0);
		
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
				  			<img class="<?php echo $aspect; ?>" src="image/user/<?php echo $busInfo['image_'.$i] ?>" alt="">
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