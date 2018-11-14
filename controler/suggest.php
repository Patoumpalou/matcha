<?php 
include ('../model/inc.php');
include('./../vendor/autoload.php');
//include('./../vendor/spatie/geocoder/src/Geocoder.php')
$css = "suggest";
$header="Suggestions";
$asuggest = 'active';
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
if (isset($_POST['settingsform'])){
		// echo "trois";

	$data = [];
	$data['age'] = filter_var($_POST['age'], FILTER_SANITIZE_STRIPPED);
	$data['age2']  = filter_var($_POST['age2'], FILTER_SANITIZE_STRIPPED);
	$data['distance'] = filter_var($_POST['distance'], FILTER_SANITIZE_STRIPPED);
	$data['pop'] = filter_var($_POST['pop'], FILTER_SANITIZE_STRIPPED);
	$data['pop2'] = filter_var($_POST['pop2'], FILTER_SANITIZE_STRIPPED);
	$data['tag'] = parse_tags(filter_var($_POST['tag'], FILTER_SANITIZE_STRIPPED));
	$_SESSION['data'] = parse_data($data);
 	$tmp = filter_var($_POST['sort'], FILTER_SANITIZE_STRIPPED);
 	// var_dump($tmp);
 	// echo " a";
	if (strcmp($tmp,"age") == 0)
		$_SESSION['sortby'] = "age";
	else if (strcmp($tmp,"localisation") == 0)
		$_SESSION['sortby'] = "localisation";
	else if (strcmp($tmp,"popularite") == 0)
		$_SESSION['sortby'] = "popularite";
	else if (strcmp($tmp,"tags") == 0)
		$_SESSION['sortby'] = "tags";
	else
		$_SESSION['sortby'] = "localisation";
	// if (isset($_POST['reset'])){
	// 	unset($_SESSION['data']);
	// 	unset($_SESSION['sortby']);
	// }
	header("Location: http://localhost:8080/matcha/controler/suggest.php?".$tmp);
}
if (isset($_SESSION['sortby']))// && !isset($_POST['settingsform']))
{
	// echo $_SESSION['data']['distance'];
	// echo "<br>";
	// echo $_SESSION['data']['pop'];
	// echo "porteespace";
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
	// var_dump($tagval);
	// var_dump($_SESSION['data']['tag']);
	// else
		// $tagval = '';
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
	$suggest = array_merge(sort_by_tag($conn, $suggest, $user_data['tags']), $suggest);
	$suggest = array_unique($suggest, SORT_REGULAR);
	// $suggest = array_map("unserialize", array_unique(array_map('serialize', $suggest)));
}
if (isset($_POST['reset'])){
	unset($_SESSION['data']);
	unset($_SESSION['sortby']);
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
include ('../view/page_suggest.php');
}

?>
