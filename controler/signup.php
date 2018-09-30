<?php
$header = "Inscrit toi !";
include("../model/inc.php");

$css = "signup";
$errors = [];
$auth = false;

if  (isset($_POST['submit']) && $_POST['submit'] === "OK")
{	
	if ($_POST['email'] !== "" && $_POST['password'] !== "" && $_POST['pseudo'] !== "" && $_POST['nom'] !== "" && $_POST['prenom'] !== "")
	{
		$user = [];
		$user['pass'] = hash("whirlpool", $_POST['password']);
		$user['email'] = htmlspecialchars($_POST['email']);
		$user['pseudo'] = htmlspecialchars($_POST['pseudo']);
		$user['prenom'] = htmlspecialchars(($_POST['prenom']));
		$user['nom'] = htmlspecialchars(($_POST['nom']));
		if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL))
			$errors['00'] = "Email invalide";
		if (get_user_by($conn, $user['pseudo'], 'pseudo') != NULL)
			$errors['1'] = "Pseudo deja pris";
		if (strlen($_POST['password']) < 6 || strlen($_POST['password']) > 50)
			$errors['2'] = "le Mot de passe doit faire entre 6 et 50 caracteres";
		if (!check_password($_POST['password']))
			$errors['111'] = "Votre mot de passe doit contenir au moins:<br>une majuscule, un chiffre et une minuscule.";
		if (strlen($user['prenom']) > 20 || strlen($user['nom']) > 20)
			$errors['names'] = "Nom ou prenom incorrect";
		if (count($errors) == 0)
		{
			if (1)//get_user_by_mail($conn, $user['email']) == NULL)
			{
				$auth = true;
				$user['logkey'] = hash("whirlpool", uniqid());
				if (!add_inactif_user_to_db($user, $conn))
					$errors['89'] = "Probleme avec lajout dans la base de donnee";
				
			//	envoi du mail
				$message = "Bonjour " . $user['pseudo'] . "\r\nPour finaliser votre inscription et valider votre compte veuillez cliquer sur le lien ci dessous :\r\n http://localhost:8080/matcha/controler/confirm_account.php?code=" . $user['logkey'];
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
?>

<?php include("header.php");
include('../view/signup.php');
?>
