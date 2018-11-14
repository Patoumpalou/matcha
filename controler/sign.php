<?php
require('../model/inc.php');
$header = "Connectes toi !";
$css = "sign";
$errors= [];
$pseudoholder = '';
if (isset($_SESSION["pseudo"]))
	header("Location: ../index.php");
////////////////////  PAGE OUBLI MDP /////////////////////
if (isset($_GET['cur']) && $_GET['cur'] == "forgotpw"){
	$ok = false;
	if (isset($_POST['submit']) && $_POST['submit'] === "OK")
	{
		if ($_POST['pseudo'] !== "" && $_POST['email'] !== "")
		{
			$user = [];
			$user['pseudo'] = filter_var($_POST['pseudo'], FILTER_SANITIZE_STRIPPED);
			$user['email'] = filter_var($_POST['email'], FILTER_SANITIZE_STRIPPED);
			if (!($user_db = get_user_by($conn, $user['pseudo'], "pseudo")))
			{
				$errors['pseudo'] = 'pseudo inconnu';
			}
			if (!check_email($user['email']))
				$errors['00'] = "Email invalide";
			if (strcmp($user_db['email'], $user['email']) != 0)
				$errors['7654'] = "L'adresse mail et le pseudo ne correspondent pas";
			if (count($errors) == 0)
			{

				if (($row = get_user_by($conn, $user['pseudo'], "pseudo")) != false)
				{
					$id = $row['id'];
					$newpass = uniqid();
					if (!update_user($conn, hash("whirlpool", $newpass), "password", $id))
						$errors['999'] = "Erreur lors du changement du mdp";
					else
					{
						$message = "Bonjour " . $user['pseudo'] . "\r\nVoici votre nouveau mot de passe :\r\n " . $newpass . "\r\nhttp://localhost:8080/matcha/controler/sign.php";
						$message = wordwrap($message, 70, "\r\n");
						if (mail($user['email'], 'Nouveau mot de passe matcha', $message) == false)
							$errors['666'] = "Probleme d'envoi du mail";
						$ok = true;
					}
				}
				else
					$errors['4'] = "Cet email est associe a aucun compte !";
			}
		}
		else
			$errors['plop'] = "Veuillez remplir tous les champs";
	}
}
////////////////// PAGE RENVOI MAIL /////////////////
if (isset($_GET['cur']) && $_GET['cur'] == 'valid'){
	$ok = false;
	if (isset($_POST['submit']) && $_POST['submit'] === "OK")
	{
		if ($_POST['pseudo'] !== "" && $_POST['email'] !== "")
		{
			$user = [];
			$user['pseudo'] = filter_var($_POST['pseudo'], FILTER_SANITIZE_STRIPPED);
			$user['email'] = filter_var($_POST['email'], FILTER_SANITIZE_STRIPPED);
			$user['passwd'] = hash("whirlpool", filter_var($_POST['passwd'], FILTER_SANITIZE_STRIPPED));
			if (!($user_db = get_user_by($conn, $user['pseudo'], "pseudo")))
			{
				$errors['pseudo'] = 'pseudo inconnu';
			}
			if (!check_email($user['email']))
				$errors['00'] = "Email invalide";
			if (strcmp($user_db['email'], $user['email']) != 0)
				$errors['7654'] = "L adresse mail et le pseudo ne correspondent pas";
			if (strcmp($user['passwd'], $user_db['password']) != 0)
				$errors['password'] = "Mot de passe incorect";
			if (!check_if_active($user, $conn))
				$errors['999'] = "Compte deja active";
			if (count($errors) == 0)
			{
				if (get_user_by($conn, $user['email'], 'email'))
				{
					$user['logkey'] = hash("whirlpool", uniqid());
					if (!update_logkey($user, $conn))
						$errors['89'] = "Probleme avec lajout dans la base de donnee";
					else
					{
						// envoi du mail
						$message = "Bonjour " . $user['pseudo'] . "\r\nPour finaliser votre inscription et valider votre compte veuillez cliquer sur le lien ci dessous :\r\n http://localhost:8080/matcha/index.php?code=" . $user['logkey'];
						$message = wordwrap($message, 70, "\r\n");
						if (mail($user['email'], 'Confirmation matcha Pihouvie', $message) == false)
							$errors['666'] = "Probleme denvoi du mail";
						$ok = true;
					}
				}
			}
		}
		else
			$errors['plop'] = "Veuillez remplir tous les champs";
	}
}
/////////////////    PAGE INSCRIPTION ////////////////////
if (isset($_GET['cur']) && $_GET['cur'] == 'up'){
	$activeup = "activelink";
	$activein = '';
	$auth = false;
	$user = [];
	$user['email'] = '';
	$user['pseudo'] = '';
	$user['prenom'] = '';
	$user['nom'] = '';
	$user['age'] = '';
	$F = '';
	$H = '';
	if  (isset($_POST['inscription']))
	{	
		if (isset($_POST['email']) && isset($_POST['email']) && $_POST['email'] !== "" && $_POST['password'] !== "" && $_POST['pseudo'] !== "" && $_POST['nom'] !== "" && $_POST['prenom'] !== "")
		{
			
			$user['pass'] = hash("whirlpool", filter_var($_POST['password'], FILTER_SANITIZE_STRIPPED));
			$user['email'] = filter_var($_POST['email'], FILTER_SANITIZE_STRIPPED);
			$user['pseudo'] = filter_var($_POST['pseudo'], FILTER_SANITIZE_STRIPPED);
			$user['prenom'] = filter_var($_POST['prenom'], FILTER_SANITIZE_STRIPPED);
			$user['nom'] = filter_var($_POST['nom'], FILTER_SANITIZE_STRIPPED);
			$user['age'] = filter_var($_POST['age'], FILTER_SANITIZE_STRIPPED);
			$user['sex'] = filter_var($_POST['sex'], FILTER_SANITIZE_STRIPPED);
			if ($user['sex'] == 'homme')
				$F = 'checked';
			else if ($user['sex'] == 'femme')
				$H = 'checked';
			if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL))
				$errors['00'] = "Email invalide";
			if (get_user_by($conn, $user['pseudo'], 'pseudo') != NULL)
				$errors['1'] = "Pseudo deja pris";
			if (intval($user['age']) > 100 || intval($user['age']) < 18)
				$errors['age'] = "l'age doit etre compris entre 18 et 100";
			if (strlen($_POST['password']) < 6 || strlen($_POST['password']) > 50)
				$errors['2'] = "le Mot de passe doit faire entre 6 et 50 caracteres";
			if (!check_password($_POST['password']))
				$errors['111'] = "Votre mot de passe doit contenir au moins:<br>une majuscule, un chiffre et une minuscule.";
			if (strlen($user['prenom']) > 20 || strlen($user['nom']) > 20)
				$errors['names'] = "Nom ou prenom incorrect";
			if (count($errors) == 0)
			{
				if (get_user_by($conn, $user['email'], 'email') == NULL)
				{
					$auth = true;
					$user['logkey'] = hash("whirlpool", uniqid());
					if (!add_inactif_user_to_db($user, $conn))
						$errors['89'] = "Probleme avec lajout dans la base de donnee";
					$user['id'] = get_user_by($conn, $user['pseudo'], 'pseudo')['id'];
					create_user_data($user, $conn);

				//	envoi du mail
					$message = "Bonjour " . $user['pseudo'] . "\r\nPour finaliser votre inscription et valider votre compte veuillez cliquer sur le lien ci dessous :\r\n http://localhost:8080/matcha/index.php?code=" . $user['logkey'];
					$message = wordwrap($message, 70, "\r\n");
					if (mail($user['email'], 'Inscription matcha Pihouvie', $message) == false)
						$errors['666'] = "Probleme denvoi du mail";

				}
				else
					$errors['4'] = "Cet email est deja associe a un compte !";
			}
		}
		else
			$errors['4000'] = "Veuillez renseigner tous les champs";
	}
}
/////////////////    PAGE CONNEXION ////////////////////
else
{
	$activeup = "";
	$activein = 'activelink';
	if (isset($_POST['connexion']) && $_POST['connexion'] === "OK")
	{
		if ($_POST['pseudo'] !== "" && $_POST['password'] !== "")
		{
			$pseudo = filter_var($_POST['pseudo'], FILTER_SANITIZE_STRIPPED);
			$pseudoholder = $pseudo;
			$hashed = hash("whirlpool", $_POST['password']);
			if (!($user = get_user_by($conn, $pseudo, 'pseudo')))
			{
				$errors['pseudo'] = 'pseudo invalide';
			}
			if (strcmp($hashed, $user['password']) != 0)
				$errors['mdp'] = 'mot de passe invalide';
			else
			{
				if ($user['actif'] != 1)
					$errors['actif'] = "compte pas validé";
				else
				{
					$_SESSION["pseudo"] = $user['pseudo'];
					$_SESSION["id"] = $user['id'];
					$_SESSION["option"] = true;
					$_SESSION['email']= $user["email"];
					create_conns($conn, $_SESSION['id']);
					update_conns($conn, $_SESSION['id'], date("Y-m-d H:i:s"), '1');
					header("Location: ../index.php");
				}
			}
		}
		else
			$errors['plop'] = "Veuillez remplir tous les champs";

	}
}



include("header.php");
include ('../view/page_sign.php');
?>

