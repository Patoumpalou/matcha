<?php
include('../model/inc.php');
if (!$_SESSION["id"])
	header("Location: ../index.php");
$achat = "active";
$css = "chat";

$id = $_SESSION['id'];
$user_data = get_user_data($id, $conn);
$restab = check_profile($user_data, $id, $conn);
$nolove = '';
if (count($restab) != 0){
	include('header.php');
	include ('../view/no_suggest.php');
}
else{
	$notifs =[]; // array qui stock les (id) de derniers msgs pour notif
	$matches = get_matches_id($conn, $_SESSION['id']);
	//$matches[0] = id match
	//$matches[1] = id user
	$_SESSION['matchstab'] = $matches;
	foreach ($matches as $key => $value) {
		$tmp = get_user_by($conn, $value[1], 'id');
		$matches[$key]['prenom'] = $tmp['prenom'];
		$matches[$key]['nom'] = $tmp['nom'];
		$matches[$key]['pseudo'] = $tmp['pseudo'];
		if (check_unseen_msg($conn, $value[0], $value[1]) == true)
			$notifs[$key] = true;
		else
			$notifs[$key] = false;
	}

	if (isset($_GET['damn'])){
		$matchid = filter_var($_GET['damn'], FILTER_SANITIZE_STRIPPED);
		$_SESSION['matchid'] = $matchid;
	}
	else if (isset($matches[0][0])){
		$matchid = $matches[0][0];
		$_SESSION['matchid'] = $matches[0][0];
	}
	else
		$nolove = "Vous n'avez pas encore de matches";
	if (isset($_POST['msgsubmit']) && $_POST['msg'] != ''){
		$data = [];
		$data['content'] = filter_var($_POST['msg'], FILTER_SANITIZE_STRIPPED);
		$data['prenom']= $user_data['prenom'];
		$data['id'] = $id;
		$data['date'] = date("Y-m-d H:i:s");
		$data['matchid'] = $matchid;
		add_msg($conn, $data);
		header('Location: chat.php?damn=' . $matchid);
	}
	$header = 'Chat';
	include('header.php');
	include ('../view/page_chat.php');
}
?>

