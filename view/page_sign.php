<body>
	<?php include("upper_nav.php");?>
	<div class="row" style="margin: 0;">
		<div class="col-6 undernav signin" id="<?=$activein?>">
			<a href="sign.php?cur=in">Se Connecter</a>
		</div>
		<div class="col-6 undernav signup" id="<?=$activeup?>">
			<a href="sign.php?cur=up">Creer un compte</a>			
		</div>
	</div>
		<?php if (isset($_GET['cur']) && $_GET['cur'] == 'up' && $auth == false){ ?>
	<div class="container-fluid">
		<div class="headform">
			<h2>Pas encore de compte ?</h2>
			<h4>Créer votre compte en quelques clics. Cela prend moins d'une minute</h4>	
		</div>
		<form method="POST" action="sign.php?cur=up" id="myForm">
			<div class="row" id="data-container">
				<div class="col-6-md">
					<div class="form-group">
						<span class="text-form">Sexe :</span>
				        <label><input name="sex" type="radio" value="homme" <?=$H?>/>Homme</label>
				        <label><input name="sex" type="radio" value="femme" <?=$F?>/>Femme</label>
				        <span class="tooltip">Vous devez sélectionnez votre sexe</span>
					</div>
					<div class="form-group">
						<label class="text_form" for="nom">Nom :</label>
						<input id="lastName" class="input" type="text" name="nom" value="<?=$user['nom']?>" >
						<span class="tooltip">Un nom ne peut pas faire moins de 2 caractères</span>
					</div>
					<div class="form-group">
						<label class="text_form" for="prenom">Prenom :</label>
						<input id="firstName" class="input" type="text" name="prenom" value="<?=$user['prenom']?>" >
						<span class="tooltip">Un prénom ne peut pas faire moins de 2 caractères</span>
					</div>
					<div class="form-group">
						<label class="text_form" for="pseudo">Pseudo :</label>
						<input id="pseudo" class="input" type="text" name="pseudo" value="<?=$user['pseudo']?>">
						<span class="tooltip">Un pseudo ne peut pas faire moins de 4 caractères</span>
					</div>
				</div>
				<div style="margin-left: 20px;" class="col-6-md">
					<div class="form-group">
						<label class="text_form" for="age">Âge :</label>
				        <input name="age" id="age" type="text" value="<?=$user['age']?>" />
				        <span class="tooltip">L'âge doit être compris entre 18 et 100</span>
					</div>
					<div class="form-group">
						<label class="text_form" for="email">Mail :</label>
						<input id="email" class="input" type="text" name="email" value="<?=$user['email']?>" >
						<span class="tooltip">Votre mail doit etre valide</span>
					</div>
					<div class="form-group">
						<label class="text_form" for="password">Mot de passe :</label>
						<input id="pwd1" class="input" type="password" name="password" >
						<span class="tooltip">Le mot de passe ne doit pas faire moins de 6 caractères <br>et contenir au moins une majuscule, une minuscule et un chiffre</span>
					</div>
					<div class="form-group">
						<label class="text_form" for="pwd2">Mot de passe (confirmation) :</label>
				        <input name="pwd2" id="pwd2" type="password" />
				        <span class="tooltip">Le mot de passe de confirmation doit être identique à celui d'origine</span>
					</div>
				</div>
			</div>
			<button type="submit" name="inscription" value="Créer mon compte" class="btn">Inscription</button>
			<?php  ?>
			<!-- readonly onfocus="this.removeAttribute('readonly');" -->
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

	<script type="text/javascript" src="../js/sign.js"></script>
	<!-- ////////////////  emaill sent ///////////////////////// -->
	<?php } else if (isset($_GET['cur']) && $_GET['cur'] == 'up' && $auth == true){ ?>
		<img src="../ressources/img/thanks.png" alt="thanks for subscribe" class="thanks">
	<!-- /////////////// lost mail ///////////////////// -->
	<?php } else if(isset($_GET['cur']) && $_GET['cur'] == 'valid'){ ?>
	<div class="container-fluid">
		<div >
			<?php if ($ok == true) {?>
			<div class="forgotpw_finish">
				<p>Mail envoye à <br><?=$user['email']?> </p>
			</div>
			<?php } else { ?>
			<form method="POST" action="sign.php?cur=valid">
				<p class="text_form">Pseudo :</p>
				<input class="input" type="text" name="pseudo" value="">
				<p class="text_form">Mail :</p>
				<input class="input" type="mail" name="email" value="">
				<p class="text_form">Mot de passe :</p>
				<input class="input" type="password" name="passwd" value="">
				<button type="submit" name="submit" value="OK" class="btn">Renvoyer un mail de validation</button>
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
	<!-- ////////////////////  forgot pw ///////////////////// -->
	<?php } else if(isset($_GET['cur']) && $_GET['cur'] == 'forgotpw'){ ?>
	<div class="container-fluid">
		<div>
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
				<button type="submit" name="submit" value="OK" class="btn">Envoyer un nouveau mot de passe</button>
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
	<!-- ///////////////// connect //////////////////////// -->
	<?php } else { ?>
	<div class="container-fluid">
		<div class="col-6-md">
			<div class="headform">
				<h2>Vous avez déjà un compte ?</h2>
				<h4>Connectez-vous avec votre pseudo et votre mot de passe</h4>
			</div>
			<form method="POST" action="sign.php" id="connform">
				<div class="form-group">
					<span class="text_form">Pseudo :</span>
					<input class="input" type ="text" name = "pseudo" value = "<?=$pseudoholder?>" placeholder="pseudo">
				</div>
				<div class="form-group">
					<span class="text_form">Mot de passe :</span>
					<input value = "" class="input" type="password" name="password" value="" >
				</div>
			</form>
			<div class="container" style="text-align: center;">				
				<button form="connform" class="btn" type="connexion" name ="connexion" value = "OK" >Connexion</button>
				<br>
				<a href="sign.php?cur=forgotpw">Mot de passe oublie ?</a>
				<br>
				<a href="sign.php?cur=valid">Renvoyer un mail de validation ?</a>
				<?php
				if ($errors != NULL)
				{ ?>
					<div class="errors"> <?php
						foreach ($errors as $msg){?>
								<p classs="error"> <?=$msg?> </p>
						<?php } ?>
					</div> <?php
				} ?>
			</div>
		</div>
	</div>
	<?php } ?>
	<script type="text/javascript">
	$('.signin').click(function() {
	  window.location.replace("http://localhost:8080/matcha/controler/sign.php?");
	});
	$('.signup').click(function() {
	  window.location.replace("http://localhost:8080/matcha/controler/sign.php?cur=up");
	});
	</script>
<?php include("footer.php");?>

</body>
</html>