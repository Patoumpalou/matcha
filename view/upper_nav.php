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


 