<div id="splash">

	<div id="foreign-country" class="text-center">Sorry, CodeDay is currently not available in your country.</div>

	<div id="splash-info" class="text-center">
		<!-- Mobile nav -->
		<div class="nav-mobile hide-for-medium-up">
			<nav class="site-nav text-right">
				<ul>
					<li><a href="register.php">Register</a></li>
					<li><a href="showcase.php">Featured Projects</a></li>
					<li><a href="sponsor.php">Sponsor</a></li>
	                <li><a href="press.php">Press</a></li>
				</ul>
			</nav>
		    <a href="#" id="nav-button" class="right">Menu</a>
		</div>
	
		<!-- Regular Nav -->
		<div class="nav-large hide-for-small-only">
			<nav class="site-nav text-right row">
			    <a href="#" id="nav-button" class="right">Menu</a>
				<ul>
					<li><a href="register.php">Register</a></li>
					<li><a href="showcase.php">Featured Projects</a></li>
					<li><a href="sponsor.php">Sponsor</a></li>
		            <li><a href="press.php">Press</a></li>
				</ul>
			</nav>
		</div>

		<h1 class="site-title">CodeDay <input type="text" id="current-city" value="<?php echo $event->name ?>" ></h1>
		<div id="city-picker" class="round-corners text-left">
			<h3 class="city-panel-title">Nearest events</h3>
			<ul>
			</ul>
		</div>
		<p class="site-description">Build something awesome in 24 hours!</p>
		<h2 class="event-date">November 11-12th 2014</h2>
		<h2 class="event-host">Hosted at <?php echo $event->venue->name ?></h2>
		<a href="register.php" class="button register-button round-corners">Get your ticket!</a>
		<!-- <input type="text" > -->
		<!-- <input type="submit" value="subscribe"> -->
		<p class="show-for-large-up"><a href="#" id="video-link">Watch video</a></p>
		<p class="show-for-medium-down"><a href="http://vimeo.com/77693957" target="_blank" id="video-link">Watch video</a></p>
	</div>

	<a href="#faqAnchor" class="anchorLink"><div id="arrow"></div></a>

	<div id="overlay"></div>
</div>

<div class="hide-for-small-only">
	<a href="#" id="splash-return" class="round-corners">Return</a>
	<video id="splash-video">
	    <source src="splash.mp4" type="video/mp4" autostart="false">
	</video>
</div>

<div id="splash-bg" class="hide-for-medium-up"></div>

<div id="over">
