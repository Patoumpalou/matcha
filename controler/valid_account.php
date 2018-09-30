<?php 
include("../model/inc.php");
$header = "matcha";
$css ="valid_account";
$ok = false;
$errors = [];
if (isset($_POST['submit']) && $_POST['submit'] === "OK")
{
	if ($_POST['pseudo'] !== "" && $_POST['email'] !== "")
	{
		$user = [];
		$user['pseudo'] = htmlspecialchars($_POST['pseudo']);
		$user['email'] = htmlspecialchars($_POST['email']);
		$user['passwd'] = hash("whirlpool", htmlspecialchars($_POST['passwd']));
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
					$message = "Bonjour " . $user['pseudo'] . "\r\nPour finaliser votre inscription et valider votre compte veuillez cliquer sur le lien ci dessous :\r\n http://localhost:8080/matcha/controler/confirm_account.php?code=" . $user['logkey'];
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
?>
<?php include("header.php");
include ('../view/valid_account.php');?>
