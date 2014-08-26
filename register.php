<?php include 'header.php'; ?>

<div id="register-page" class="subpage">

	<!-- Mobile nav -->
	<header class="header-subpage header-mobile hide-for-medium-up"> 
		<nav class="site-nav text-right">
			<ul>
				<li><a href="register.php" class="current-page">Register</a></li>
				<li><a href="showcase.php">Featured Projects</a></li>
				<li><a href="sponsor.php">Sponsor</a></li>
                <li><a href="press.php">Press</a></li>
			</ul>
		</nav>
		<div class="row">
			<h1 class="site-title left"><a href="/codeday">CodeDay</a></h1>
			<a href="#" id="nav-button" class="right">Menu</a>
		</div>
		<h1 class="subpage-title text-center">Register</h1>
	</header>
	
	<!-- Regular Nav -->
	<header class="header-subpage header-large hide-for-small-only">
		<div class="row">
			<div class="column medium-2 small-6">
				<h1 class="site-title"><a href="/codeday">CodeDay</a></h1>
			</div>
			<div class="column medium-10 small-6"> 
				<nav class="site-nav text-right">
					<a href="#" id="nav-button" class="right">Menu</a>
					<ul>
						<li><a href="register.php" class="current-page">Register</a></li>
						<li><a href="showcase.php">Featured Projects</a></li>
						<li><a href="sponsor.php">Sponsor</a></li>
                        <li><a href="press.php">Press</a></li>
					</ul>
				</nav>
			</div>
		</div>
		<h1 class="subpage-title text-center">Register</h1>
	</header>


	<main class="row wrapper-column">

		<div class="columns medium-6">

			<div class="register-box round-corners">
				<h2>CodeDay Seattle</h2>
				<iframe class="register-map" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d5377.2521925759165!2d-122.326322!3d47.633399!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x54901521431b5d01%3A0xdfaa192e0856f09e!2s1551+Eastlake+Ave+E%2C+Seattle%2C+WA+98102!5e0!3m2!1sen!2sus!4v1405657973519" width="100%" height="250" frameborder="0" style="border:0"></iframe>
				<p class="register-host">Porch</p>
				<p class="register-address">1551 Eastlake Ave E Seattle, WA 98103</p>
				<p class="register-date">November 11-12, 2014</p>
			</div>

			<div class="register-box round-corners">
				<h2>Ticket Info</h2>
				<span class="ticket-type">Student</span><!--
				--><span class="ticket-price">$10 (Early Bird 50% Off)</span><!--
				--><span class="dropdown">
				<select id="ticket-amount">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
				</select></span>
				<div class="text-right">
					<span id="text-total" class="text-center">Total</span>
					<span id="total-cost" class="text-center">$10</span>
				</div>
				<input type="text" id="promo-code" name="promo-code" placeholder="Enter Promotional Code"><!--
				--><a href="" id="promo-code-button" class="text-center">Apply</a>
			</div>

		</div>

		<div class="columns medium-6">
			<div id="coder-profile-1" class="register-box round-corners coder-profile">
				<form action="" method="post" autocomplete="off">
					<h2 class="coder-profile-title">Coder's Info</h2>
					<input type="text" class="first-name" name="first-name" placeholder="First Name"><!--
					--><input type="text" class="last-name" name="last-name" placeholder="Last Name"><!--
					--><input type="text" class="age" name="age" placeholder="Age" maxlength="2"><!--
					--><input type="text" class="email" name="email" placeholder="Email"><!--
					--><input type="text" class="confirm-email" name="confirm-email" placeholder="Confirm Email">
					<input type="checkbox" id="subscribe1" class="register-checkbox" name="subscribe1" value="subscribe" checked><label for="subscribe1">I’d like to recieve info from sponsors</label>
				</form>
			</div>
			<div class="register-box round-corners">
				<form id="billing-info" action="" method="post" autocomplete="off">
					<h2>Billing Info</h2>
					<input type="text" id="credit-num" name="credit-num" placeholder="Credit Card Number"><!--
					--><input type="text" id="csc" name="csc" placeholder="CSC" maxlength="3"><!--
					--><span id="expir-date">Expiration Date</span><!--
					--><input type="text" id="mm" name="mm" placeholder="mm" maxlength="2"><!--
					--><span> /</span><input type="text" id="yy" name="yy" placeholder="yy" maxlength="2">					
				</form>
			</div>

			<a href="" id="pay-button" class="round-corners text-center">Pay Now</a>
			
			<p id="toa">By clicking “Pay Now”, I have read and agree with the <a href="">CodeDay terms of service</a>, <a href="">CodeDay code of conduct</a>, and possibly getting my picture taken during the event.</p>

		</div>

	</main>

</div>

<?php include 'footer.php'; ?>