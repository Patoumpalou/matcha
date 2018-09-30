<body>
	<?php include( "upper_nav.php");?>

<div id="banniere_mid">
		<div id="div_article">
			<?php if ($ok == true) {?>
			<div class="forgotpw_finish">
				<p>Nouveau mot de passe envoye à <br><?=$user['email']?> </p>
			</div>
			<?php } else { ?>
			<form method="POST" action="forgotpw.php">
				<p class="text_form">Pseudo :</p>
				<input class="input" type="text" name="pseudo" value="">
				<p class="text_form">Mail :</p>
				<input class="input" type="mail" name="email" value="">
				<button type="submit" name="submit" value="OK" id="sign_button">Envoyer un nouveau mot de passe</button>
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