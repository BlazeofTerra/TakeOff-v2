<div class="rslides-container">
	<ul class="rslides">
	  	<li>
	  		<picture>
  			    <source media="(min-width: 1080px)" srcset="img/sliders/index/1080/slide2.png">
  			    <source media="(min-width: 1024px)" srcset="img/sliders/index/1024/slide2.png">
  			    <source media="(min-width: 768px)" srcset="img/sliders/index/768/slide2.png">
  			    <source media="(min-width: 425px)" srcset="img/sliders/index/425/slide2.png">
  			    <source media="(min-width: 375px)" srcset="img/sliders/index/375/slide2.png">
				<source media="(min-width: 320px)" srcset="img/sliders/index/320/slide2.png">
	  			<img src="img/sliders/index/1024/slide2.png" alt="">
	  		</picture>	
  		</li>
	  	<li>
	  		<picture>
  			    <source media="(min-width: 1080px)" srcset="img/sliders/index/1080/slide1.png">
  			    <source media="(min-width: 1024px)" srcset="img/sliders/index/1024/slide1.png">
  			    <source media="(min-width: 768px)" srcset="img/sliders/index/768/slide1.png">
  			    <source media="(min-width: 425px)" srcset="img/sliders/index/425/slide1.png">
  			    <source media="(min-width: 375px)" srcset="img/sliders/index/375/slide1.png">
				<source media="(min-width: 320px)" srcset="img/sliders/index/320/slide1.png">
	  			<img src="img/sliders/index/1024/slide1.png" alt="">
	  		</picture>	
  		</li>
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