<?php 
include ('../model/inc.php');
include('./../vendor/autoload.php');
//include('./../vendor/spatie/geocoder/src/Geocoder.php')
$css = "suggest";
$header="Suggestions";
if (isset($_SESSION['id']))
	$id = $_SESSION['id'];
else
	header("Location: http://localhost:8080/matcha/");
$offset = 0; 
$user_data = get_user_data($id, $conn);
$restab = check_profile($user_data, $id, $conn); //verfie la completion du profil utilisateur 
if (count($restab) != 0){
	$_SESSION['profil'] = false;
	include('header.php');
	include ('../view/no_suggest.php');
}
else{
/////////////////////////////////////////////////

$my_coords = get_coords($user_data['location']);
$suggest = get_suggest($id, $conn, $offset, $user_data);
$suggest = make_distance($suggest, $my_coords['lat'], $my_coords['lon']); 
$suggest = parse_suggest($suggest, $conn, $_SESSION['id']);
$nb_pages = ceil(count($suggest) / 6); 
if ($nb_pages == 0)
	$nb_pages++;
if (isset($_POST['gauche']))
{
 	if ($_POST['gauche'] >= 0){
 		$offset = intval($_POST['gauche']);
 		$count = $offset / 6+1;
 	}
 	else{
 		$offset= 0;
 		$count = 1;
 	}
}
else if (isset($_POST['droite']))
{
 	$offset = intval($_POST['droite']);
 	$count = $offset / 6 +1;
 	if ($count > $nb_pages){
 		$count = $nb_pages;
 		$offset-= 6;
 	}
}
else{
 	$offset = 0;
 	$count = 1;
}
//valeurs pour la preselection du tri en affichage
$fage = '';
$floc = '';
$fpop = '';
$ftag = '';
if (isset($_SESSION['sortby']) && !isset($_POST['sort']))
{
	// echo $_SESSION['data']['distance'];
	// echo "<br>";
	// echo $_SESSION['data']['pop'];
	echo "porteespace";
	if (isset($_SESSION['data']))
		$suggest = applyfilters($_SESSION['data'], $suggest, $conn);
	$tmp = $_SESSION['sortby'];
	if ($tmp == 'age'){
		$fage ="selected";
		usort($suggest, "cmp_age");
	}
	if ($tmp == 'localisation'){
		$floc = 'selected';
		usort($suggest, "cmp_km");
	}
	if ($tmp == 'popularite'){
		$fpop = 'selected';
		usort($suggest, "cmp_pop");

	}
	if ($tmp == 'tags'){
		$ftag = 'selected';
		$suggest = sort_by_tag($conn, $suggest, $user_data['tags']);
	}
	$nb_pages = ceil(count($suggest) / 6); 
		if ($nb_pages == 0)
			$nb_pages++;
	// valeurs pour laffichage
	$ageval = $_SESSION['data']['age'] . ',' . $_SESSION['data']['age2'];
	$age_p = "Age compris entre: " . $_SESSION['data']['age'] . ' et ' . $_SESSION['data']['age2'];
	$scoreval = $_SESSION['data']['pop'] . ',' . $_SESSION['data']['pop2'];
	$score_p = "Score compris entre: " . $_SESSION['data']['pop'] . ' et ' . $_SESSION['data']['pop2'];
	$kmval = $_SESSION['data']['distance'];
	$tagval = implode(" ", $_SESSION['data']['tag']);
}
else{
	$tagval = '';
	$age_p = " Age compris entre: 18 et 100";
	$score_p = "Score compris entre: 0 et 100";
	$ageval = '18,100';
	$scoreval = '0,100';
	$kmval = '800';
	$floc = 'selected';
	usort($suggest, "cmp_km");
}
if (isset($_POST['sort'])){
	$data = [];
	$data['age'] = htmlspecialchars($_POST['age']);
	$data['age2']  = htmlspecialchars($_POST['age2']);
	$data['distance'] = htmlspecialchars($_POST['distance']);
	$data['pop'] = htmlspecialchars($_POST['pop']);
	$data['pop2'] = htmlspecialchars($_POST['pop2']);
	$data['tag'] = parse_tags(htmlspecialchars($_POST['tag']));
	$_SESSION['data'] = parse_data($data);
 	$tmp = htmlspecialchars($_POST['sort']);
	if ($tmp == 'age')
		$_SESSION['sortby'] = 'age';
	if ($tmp == 'localisation')
		$_SESSION['sortby'] = 'localisation';
	if ($tmp == 'popularite')
		$_SESSION['sortby'] = 'popularite';
	if ($tmp == 'tags')
		$_SESSION['sortby'] = 'tags';
	if (isset($_POST['reset'])){
		unset($_SESSION['data']);
		unset($_SESSION['sortby']);
	}
	header("Location: http://localhost:8080/matcha/controler/suggest.php");
}
//else


/// creation du tableau six pour les info des div 
$six = [];
for ($q = 0; $q < 6; $q++)
{
	if (isset($suggest[$q + $offset]))
		$six[$q] = get_user_data($suggest[$q + $offset]['id'], $conn);
	// pour avoir nom, prenom, age, coords, score et id, tags 
	else
		$q = 6;
}

///var_dump($suggest);


// if adresse rentre das le form du profil introuvable ? 
include('header.php');
include ('../view/suggest.php');
}

?>
