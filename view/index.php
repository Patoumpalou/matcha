<div id="banniere_top">
	<header id="header">
		<a href ="index.php" id = "logo">Matcha</a>
		<div id="relou" style="padding-right: 30px;">
		<?php 
			if (isset($_SESSION['pseudo']) != false)
			{ 
		?>
			<span id="logged-in">Bonjour <?= $_SESSION['pseudo']?></span>
			<a href="controler/suggest.php" id = "login">Suggestions</a>
			<a href="controler/profile.php" id="login">Profile</a>
			<a href="controler/settings.php" id="login">Reglages</a>
			<a href="controler/logout.php" id="logout">LOGOUT</a>
		<?php 
			}
			else
			{ 
		?>
		<a href="controler/signin.php" id="login">Sign IN/UP</a>
		<?php
			} 
		?>
	</header>
</div>

<div id ="footer">
	<div>
		<img src="ressources/img/42_Logo.png">
	</div>
	<div>
		<p>Matcha est un magnifique site réalisé par Pierre Houviez. Il merite amplement 125/125.</p>
	</div>
	<div>
		<p>This is a footer</p>
	</div>
</div>