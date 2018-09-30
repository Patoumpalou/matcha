<div id="banniere_top">
	<header id="header">
		<a href ="../index.php" id = "logo">Matcha</a>
		<div id="relou" style="padding-right: 30px;">
		<?php 
			if (isset($_SESSION['pseudo']) != false)
			{ 
		?>
			<span id="logged-in">Bonjour <?= $_SESSION['pseudo']?></span>
			<a href="suggest.php" id = "login">Suggestions</a>
			<a href="profile.php" id="login">Profile</a>
			<a href="settings.php" id="login">Reglages</a>
			<a href="logout.php" id="logout">LOGOUT</a>
		<?php 
			}
			else
			{ 
		?>
		<a href="signin.php" id="login">Sign IN/UP</a>
		<?php
			} 
		?>
	</header>
</div>

<!-- 
<nav class="navbar navbar-expand-md navbar-light bg-light sticky-top">
	<div class="container-fluid">
		<a class="navar-brand" href="#"><img src=""></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-toggler-icon">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarResponsive">
			<ul class="navbar-nav ml-auto">
				<li class="nav-item active">
					<a class="nav-link" href="#">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">About</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">Suce</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">Bite</a>
				</li>
			</ul>
		</div>
	</div>
</nav>
-->
