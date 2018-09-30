<?php
include('../model/inc.php');
$header = "Reglages";
$errors = [];
$css = "settings";
$ok = 0;

if (isset($_POST['submit']) && $_POST['submit'] == "OK")
{
	$user = get_user_by($conn, $_SESSION["pseudo"], 'pseudo');
	if($_POST['email'] !== "" && strcmp($user['email'], $_POST['email']) != 0) 
	{
		$email = htmlspecialchars($_POST['email']);
		if (!check_email($email))
			$errors['00'] = "Email invalide";
		// checker si email pas deja pris.     faire de meme pour les mdp sur tout le site ? 
		else
		{
			if (!update_user($conn, $email, "email", $_SESSION["id"]))
				$errors['1'] = "probleme avec le changement d adresse mail";
			else
				$ok = 1;
			$_SESSION['email'] = $email;
		}
	}
	if ($_POST['pseudo'] !== "") 
	{
		$pseudo = htmlspecialchars($_POST['pseudo']);
		if (get_user_by($conn, $pseudo, 'pseudo') != NULL)
			$errors['2'] = "Pseudo deja pris";
		else if (strcmp($user['pseudo'], $_POST['pseudo']) == 0)
			$errors['2'] = "Pseudo identique";
		else
		{
			if (!update_user($conn, $pseudo, "pseudo", $_SESSION["id"]))
				$errors['3'] = "probleme avec le changement de pseudo";
			else
				$ok = 1;
			$_SESSION['pseudo'] = $pseudo;
		}
	}
	if ($_POST['passwd'] !== "") 
	{
		if ($_POST['oldpasswd'] !== "")
		{
			$oldpasswd = htmlspecialchars(($_POST['oldpasswd']));
			$user = get_user_by($conn, $_SESSION['pseudo'], 'pseudo');
			$hashed = hash("whirlpool", $oldpasswd);
			if (strcmp($hashed, $user['password']) == 0)
			{
				$passwd = htmlspecialchars($_POST['passwd']);
				$passwd_hashed = hash("whirlpool", $passwd);
				if (strcmp($passwd_hashed, $user['password']) != 0)
				{
					if (strlen($passwd) < 6 || strlen($passwd) > 50)
						$errors['2x'] = "le Mot de passe doit faire entre 6 et 50 caracteres";
					if (!check_password($passwd))
						$errors['111x'] = "Votre mot de passe doit contenir au moins:<br>une majuscule, un chiffre et une minuscule.";
					if (count($errors) == 0)
					{
						if (!update_user($conn, $passwd_hashed, "password", $_SESSION["id"]))
							$errors['3x'] = "probleme avec le changement de mot de passe";
						else
							$ok = 1;
					}
				}
			}
			else
				$errors['mdpincc'] = 'mot de passe invalide';
		}
		else
			$errors['passss'] = "Il faut donner l'ancien mot de passe pour pouvoir modifier";
	}
	if ($ok == 1)
		header("Location: settings.php?sucess=true");
//	header("Location: settings.php");
}
?>
<?php include("header.php"); 
include('../view/settings.php');
?>
