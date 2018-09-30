<body>
	<?php include("upper_nav.php");?>
	<div id = "banniere_menu">
		<div id = "menu">
			<div class ="button_menu" id="PACK">Connexion
			</div>
			<a href = "signup.php" class = "button_menu">Inscription</a>
		</div>
	</div>
	<div id = "banniere_mid">
		<div id="div_article">
			<form method="POST" action="signin.php">
				<p class="text_form">Pseudo :</p>
				<input class="input" type ="text" name = "pseudo" value = "" readonly onfocus="this.removeAttribute('readonly');">
				<p class="text_form">Mot de passe :</p>
				<input value = "" class="input" type="password" name="password" readonly onfocus="this.removeAttribute('readonly');">
				<br>

				<button type="submit" name = "submit" value = "OK" id = "sign_button">
				Connexion</button>
				<br>
				<a href="forgotpw.php">Mot de passe oublie ?</a>
				<br>
				<a href="valid_account.php">Renvoyer un mail de validation ?</a>
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