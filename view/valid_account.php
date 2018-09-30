<body>
	<?php include("upper_nav.php");?>

<div id="banniere_mid">
		<div id="div_article">
			<?php if ($ok == true) {?>
			<div class="forgotpw_finish">
				<p>Mail envoye à <br><?=$user['email']?> </p>
			</div>
			<?php } else { ?>
			<form method="POST" action="valid_account.php">
				<p class="text_form">Pseudo :</p>
				<input class="input" type="text" name="pseudo" value="">
				<p class="text_form">Mail :</p>
				<input class="input" type="mail" name="email" value="">
				<p class="text_form">Mot de passe :</p>
				<input class="input" type="password" name="passwd" value="">
				<button type="submit" name="submit" value="OK" id="sign_button">Renvoyer un mail de validation</button>
			</form>
			<?php
			}
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