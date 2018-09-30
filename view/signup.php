<body>
	<?php include("upper_nav.php")?>
	<?php if ($auth == true){ ?>
					<img src="../ressources/img/thanks.png" alt="thanks for subscribe" class="thanks">
					<?php } 
				else {?>
	<div id="banniere_menu">
		<div id="menu">
			<a href="signin.php" class="button_menu" id="PACK">Connexion</a>
			<div class="button_menu" id="ALCOOL">Inscription</div>
		</div>
	</div>
	<div id="banniere_mid">
		<div id="div_article">
			<form method="POST" action="signup.php">
				<p class="text_form">Nom :</p>
				<input class="input" type="text" name="nom" value="" readonly onfocus="this.removeAttribute('readonly');">
				<p class="text_form">Prenom :</p>
				<input class="input" type="text" name="prenom" value="" readonly onfocus="this.removeAttribute('readonly');">
				<p class="text_form">Pseudo :</p>
				<input class="input" type="text" name="pseudo" value="">
				<p class="text_form">Mail :</p>
				<input class="input" type="mail" name="email" value="" readonly onfocus="this.removeAttribute('readonly');">
				<p class="text_form">Mot de passe :</p>
				<input class="input" type="password" name="password" readonly onfocus="this.removeAttribute('readonly');">
				<button type="submit" name="submit" value="OK" id="sign_button">Inscription</button>
				<?php } ?>
			</form>
			<?php
			if ($errors != NULL)
			{ ?>
				<div class="errors"> <?php
					foreach ($errors as $msg){?>
							<p classs="error"> <?=$msg?> </p>
					<?php } ?>
				</div> <?php
			}?>
		</div>
	</div>
	<?php include("footer.php");?>
	<script type="text/javascript">
		document.getElementById("relou").removeChild(document.getElementById("login"))
	</script>
</body>
</html>