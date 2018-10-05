<?php
include('../model/inc.php');
if (!$_SESSION["id"])
	header("Location: ../index.php");

$css = "chat";

$id = $_SESSION['id'];
$user_data = get_user_data($id, $conn);
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
	$matchid = htmlspecialchars($_GET['damn']);
	$_SESSION['matchid'] = $matchid;
}
else{
	$matchid = $matches[0][0];
	$_SESSION['matchid'] = $matches[0][0];
}
if (isset($_POST['msgsubmit']) && $_POST['msg'] != ''){
	$data = [];
	$data['content'] = htmlspecialchars($_POST['msg']);
	$data['prenom']= $user_data['prenom'];
	$data['id'] = $id;
	$data['date'] = date("Y-m-d H:i:s");
	$data['matchid'] = $matchid;
	add_msg($conn, $data);
	header('Location: chat.php?damn=' . $matchid);
}
$header = 'Chat';
include('header.php');
include ('../view/chat.html');

?>

