<body>
	<?php include("upper_nav.php");?>

	<div id="banniere_mid">
		<div id="div_article">
			<span> Ne remplir que les champs a modifier </span>
			<form method="POST" action="settings.php">
				Pseudo:<input type="text" name="pseudo" value="">
				<br/>
				Email :<input type="text" name="email" value="" readonly onfocus="this.removeAttribute('readonly');">
				<br/>
				Nouveau mot de passe:<input type="password" name="passwd" value="" readonly onfocus="this.removeAttribute('readonly');">
				<br/>
				Ancien mot de passe:<input type="password" name="oldpasswd" value="" readonly onfocus="this.removeAttribute('readonly');">
				<br/>
				<input type="submit" name="submit" value="OK">
			</form>
			<?php
			if ($errors != NULL)
			{ ?>
				<div class="errors"> <?php
					foreach ($errors as $msg){ ?>
							<p classs="error"> <?=$msg?> </p>
					<?php } ?>
				</div>
				<?php } ?>
		</div>
	</div>
	<?php include("footer.php");?>
</body>
<script type="text/javascript" src="../js/script.js"></script>
</html>
