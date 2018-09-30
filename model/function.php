<?php
function cmp_km($a, $b){
	if ($a['km'] == $b['km']) {
        return 0;
    }
    return ($a['km'] < $b['km']) ? -1 : 1;
}
function cmp_age($a, $b){
	if ($a['age'] == $b['age']) {
        return 0;
    }
    return ($a['age'] < $b['age']) ? -1 : 1;
}
function cmp_pop($a, $b){
	if ($a['score'] == $b['score']) {
        return 0;
    }
    return ($a['score'] < $b['score']) ? -1 : 1;
}
function sort_by_tag($conn, $tab, $tags)
{
	$res = [];
	$i = 0;
	foreach($tab as $someone){
		$someonetags = get_user_tags($conn, $someone['id']);
		if(count(array_intersect($tags, $someonetags)) != 0)
			$res[$i++] = $someone;
	}
	return $res;
}
function applyfilters($data, $tab, $conn){
	$res = [];
	$i = 0;
	if($data['tag'][0] != "")
	{
		$my_tags = $data['tag']; 
		$tmp = 0;
		foreach($tab as $someone){
			$tags = get_user_tags($conn, $someone['id']);
			if (count(array_intersect($my_tags, $tags)) != 0){
				$res[$i] = $someone;
				$i++;
			}
		}
		$tab = $res;
		$i = 0;
	}
	foreach($tab as $someone){
		if($someone['age'] >= $data['age'] && $someone['age'] <= $data['age2'] && $someone['km'] <= $data['distance'] && $someone['score'] >= $data['pop'] && $someone['score'] <= $data['pop2'])
		{
			$res[$i] = $someone;
			$i++;
		}	
	}
	return $res;
}
function parse_data($data){
	if ($data['age2'] < $data['age']){
		$tmp = $data['age'];
		$data['age'] = $data['age2'];
		$data['age2'] = $tmp;
	}
	if ($data['pop2'] < $data['pop']){
		$tmp = $data['pop'];
		$data['pop'] = $data['pop2'];
		$data['pop2'] = $tmp;
	}
	return $data;
}
function parse_age($age)
{
	if (is_int($age) == false)
		return 0;
	if ($age >= 100)
		return 99;
	if ($age < 0)
		return 0;
	return $age; 
}
function parse_tags($string){
	$tab = explode(' ', $string);
	$i = 0;
	foreach ($tab as $e){
		if($e != '' && $e[0] != '#')
			unset($tab[$i]);
		$i++;
	}
	$tab = array_unique($tab);
	$tab = array_values($tab);
	return $tab;
}

function make_distance($tab, $lat, $lon)
{
	$i = 0;
	foreach($tab as $someone){
		$distance = get_distance($someone['lat'], $lat, $someone['lon'], $lon);
	//	echo $distance . "<br>";
		$tab[$i]['km'] = $distance;
		$i++;
//		$tab[$i]['tags'] = get_user_tags($conn, $someone['id']);
	}

	return $tab;
}
function get_img_name_by_id_num($conn, $id, $num){
	$sql = "SELECT name from img where uid = '" . $id . "' and num = '" . $num . "'";
	if (!($res = $conn->query($sql)))
		return false;
	if ($res->rowCount() == 0)
		return false;
	$row = $res->fetch();
//	echo $row['name'] . '\t' . $sql;
	return $row[0];
	
}
function get_suggest($id, $conn, $offset, $data)
{
	$margin = 30;
	$total1 = intval($data['score']) - $margin;
	$total2 = intval($data['score']) + $margin;
	$sexe = $data['sexe'];
	$want = $data['want'];
	if ($want == "both")
		$sexwanted = "homme' or sexe = 'femme"; 
	else if ($want == "any")
		$sexwanted = "homme' or sexe = 'femme' or sexe = 'autre"; 
	else
		$sexwanted = $want;
	if ($sexe == "homme")
		$oppsex = "femme";
	if ($sexe == "femme")
		$oppsex = "homme";
	else
		$oppsex = "homme";

	$sql = "SELECT id, lat, lon, age, score from user_data where (sexe = '" . $sexwanted ."') and want = '$oppsex' and (score <= '$total2' or score >= '$total1')";
//	echo $sql;
	if (!($res = $conn->query($sql)))
		return false;
	return $res->fetchAll();
}
function get_distance($lat1, $lat2, $lon1, $lon2) { 
  if (($lat2 == 0 && $lon1 == 0) || ($lon2 == 0 || $lat2 == 0))
  	return 13;
  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  
  return (round($miles * 1.609344));
}
function get_coords($address)
{
	$key = "AIzaSyDtnHLVfWMIe7h9cP2UIS559Jp_CgyUPhU";
	$prepAddr = str_replace(' ','+',$address);
	$url = "https://maps.google.com/maps/api/geocode/json?address=".urlencode($prepAddr)."&key=" . $key;
	//echo $url;
	$geocode=file_get_contents($url);
	$res = json_decode($geocode);
//	return $res;
	//sleep(1);
	if ($res->status == 'OK'){
		$lat = $res->results[0]->geometry->location->lat;
		$lon = $res->results[0]->geometry->location->lng;
	}
	else{
		return false;
	}	
	return( (array('lat' => $lat, 'lon' => $lon) ));
}
function get_user_data($id, $conn)
{
	$data = [];
	$sql = "SELECT * FROM user_data where id = '" . $id . "'";
	if (!($res = $conn->query($sql)))
		return false;
	if ($res->rowCount() == 0)
		return false;
	$row = $res->fetch();
	$data['age'] = $row['age'] ;
	$data['sexe'] = $row['sexe'] ;
	$data['location'] = $row['location'] ;
	$data['lat'] = $row['lat'];
	$data['lon'] = $row['lon'];
	$data['orientation'] = $row['orientation'] ;
	$data['bio'] = $row['bio']; 
	$data['score'] = $row['score'];
	$data['want'] = $row['want'];
	$row = get_user_by($conn, $id, 'id');
	$data['prenom'] = $row['prenom'];
	$data['nom'] = $row['nom'];
	$data['pseudo'] = $row['pseudo'];
	$data['pp'] = get_img_name_by_id_num($conn, $id, '4');
	$data['tags'] = get_user_tags($conn, $id);
	return $data;
}
function get_user_tags($conn, $id)
{
	$sql ="SELECT name from tags where uid = '" . $id . "'";
	if (!($res = $conn->query($sql)))
		return false;
	$row = $res->fetchAll();
	$final = [];
	$i = 0;
	foreach ($row as $tag){
		$final[$i] = $tag['name'];
		$i++;
	}
	return $final;
}
function get_user_by($conn, $value, $by) // forgotpw
{
	if (!($res = $conn->query("SELECT * FROM users WHERE ".$by." = '$value'")))
		return false;
	if ($res->rowCount() == 0)
		return false;
	$row = $res->fetch();
	return $row;
}
function get_visits($conn, $myid)
{
	$sql = "SELECT * from visits where id_visited = '$myid'";
	if (!($res = $conn->query($sql)))
		return false;
	$row = $res->fetchAll();
	$i = 0;
	foreach ($row as $key => $someone) {
		$row[$i]['img'] = get_img_name_by_id_num($conn, $someone['id'], '4');
		$i++;
	}
	return $row;

}
function get_likes($conn, $id){
	$sql = "SELECT * from likes where id_target = '$id'";
	if (!($res = $conn->query($sql)))
		return false;
	// if ($res->rowCount() == 0)
	// 	return false;
	$row = $res->fetchAll();
	foreach ($row as $key => $value) {
		$row[$key]['img'] = get_img_name_by_id_num($conn, $value['id_author'], '4');
	}
	return $row;
}
function create_user_data($id, $conn)
{
	$sql = "INSERT INTO user_data(id) VALUES ('" . $id . "')";
	if (!($res = $conn->query($sql)))
		return false;
	else
		return true;
}
function add_tag($conn, $id, $tag)
{
	$sql = "INSERT INTO tags(name, uid) VALUES ('" . $tag . "', '" . $id . "') ";
	if (!($res = $conn->query($sql)))
		return false;
	else
		return true;

}
function add_inactif_user_to_db($user, $conn)
{
	if (!($res = $conn->query("INSERT INTO users (nom, prenom, logkey, pseudo, email, password) VALUES ('" . $user['nom'] . "','" . $user['prenom'] . "','" . $user['logkey'] . "', '" . $user['pseudo']  . "', '" . $user['email'] . "', '" . $user['pass'] ."')")))
		return false;
	else
		return true;
}
function add_img_to_db($conn, $uid, $name, $i)
{
	$sql = "INSERT INTO img (uid, name, num) VALUES ('" . $uid . "' , '" . $name . "', '" . $i . "')";
	if (!($res = $conn->query($sql)))
		return false;
	return true;
}
function add_visit($conn, $myid, $id)
{
	$sql = "SELECT id from visits where id_visited = '$id' and id_visit = '$myid'";
	if (!($res = $conn->query($sql)))
		return false;
	if ($res->rowCount() == 0){
		$sql = "INSERT INTO visits (id_visited, id_visit) VALUES ('$id', '$myid')";
		if (!($res = $conn->query($sql)))
			return false;
	}
	return true;
}
function update_coords($conn, $id, $lat, $lon)
{
	// echo $lat . "\t" . $lon;
	$sql = "UPDATE user_data SET lat = '$lat', lon = '$lon' where uid = '$id'";
	echo $sql;
	if(!($res = $conn->query($sql)))
		return false;
	return true;
}
function update_wanted($conn, $id)
{
	$sql = "SELECT sexe, orientation FROM user_data where id = '$id'";
	if (!($res = $conn->query($sql)))
		return false;
	$row = $res->fetch();
	$sexe = $row['sexe'];;
	$genre = $row['orientation'];
	if ($sexe == "homme")
		$opsex = "femme";
	else if ($sexe == "femme")
		$opsex = "homme";
	//else if ($data['sexe'] = "autre")
		////
	if ($genre == "Heterosexuel")
		$sexwanted = $opsex; 
	else if ($genre == "Homosexuel")
		$sexwanted = $sexe;
	else if ($genre == "Pansexuel" || $genre == "Sapiosexuel")
		$sexwanted = "any";
	else
		$sexwanted = "both";
	return update_user_data($conn, $sexwanted, 'want', $id);		
}
function update_user_data($conn, $value, $subject, $id)
{
	$sql = "UPDATE user_data SET " . $subject . " = '" . $value . "' WHERE id = '" . $id . "'";
	if(!($res = $conn->query($sql)))
		return false;
	return true;
}
function update_user($conn, $value, $subject, $id)
{
	$sql = "UPDATE users SET " . $subject . " = '" . $value . "' WHERE id = '" . $id . "'";
	if(!($res = $conn->query($sql)))
		return false;
	return true;
}
function update_actif($logkey, $conn){
	if (!($res = $conn->query("UPDATE users SET actif = 1 WHERE users . logkey = '". $logkey . "'")))
		return false;
	else
		return true;
}
function update_logkey($user, $conn){
	$sql = "UPDATE users SET logkey = '". $user['logkey'] ."' WHERE users . pseudo = '". $user['pseudo'] . "'";
	if (!($res = $conn->query($sql)))
		return false;
	else
		return true;
}
function update_like($conn, $myid, $id, $str){
	if ($str == 'add')
		$sql = "INSERT INTO likes(id_target, id_author) values ('$id', '$myid')";
	else if ($str == 'delete')
		$sql = "DELETE FROM likes where id_target = '$id' and id_author = '$myid'";
	if (!($res = $conn->query($sql)))
		return false;
	return true;
}
function is_loving_you($conn, $myid, $otherid){
	$sql = "SELECT * from likes where id_target = '$myid' and id_author = '$otherid'";
	if (!($res = $conn->query($sql)))
		return false;
	if ($res->rowCount() == 0)
		return "";
	return "back";
}
function check_like($conn, $myid, $id){
	$sql = "SELECT * from likes where id_target = '$id' and id_author = '$myid'";
	if (!($res = $conn->query($sql)))
		return false;
	if ($res->rowCount() == 0)
		return false;
	return true;
}
function check_profile($data, $id, $conn)
{
	$miss = [];
	if ($data['location'] == "")
		$miss['location'] = "On a besoin de connaitre ta position pour trouver des plans cul pres de chez toi";
	if ($data['sexe'] == "")
		$miss['genre'] = "On est sensé deviner si tes un mec ou une fille ?";
	if ($data['age'] == '0')
		$miss['age'] = "Il nous faut connaitre ton age... pour des raisons plus ou moins bienveillantes";
	if ($data['bio'] == "")
		$miss['bio'] = "Allez, raconte nous un pti truc, rempli ta bio quoi...";
	$sql = "SELECT * from img where uid = '$id' and num = '4'";
	if (!($res = $conn->query($sql)))
		return false;
	if ($res->rowCount() === 0)
		$miss['profilepicture'] = "Ohhhh noooon il manque une jolie petite photo de toi";
	$sql = "SELECT * from tags where uid = '$id'";
	if (!($res = $conn->query($sql)))
		return false;
	if ($res->rowCount() == 0)
		$miss['tags'] = "N'avez vous donc aucun interet ? remplis moi ces putains dinterets";
	if (count($miss) == 0)
		return null;
	// check interests

	return $miss;
}
function check_if_active($user, $conn)
{
	$sql = "SELECT actif from users where pseudo = '" . $user["pseudo"] . "' AND email = '" . $user['email'] . "'";
	if (!($res = $conn->query($sql)))
		return false;
	$row = $res->fetch();
	if ($row['actif'] == 0)
		return true;
	else
		return false;
}
function check_if_pseudo_is_in_bdd($pseudo, $conn){
	$sql = "SELECT * from users where pseudo = '" . $pseudo . "'"; 
	if (!($res = $conn->query($sql)))
		return false;
	if ($res->rowCount() === 0)
	{
		return false;
	}
	return true;
}
function check_password($passwd)
{
	if (preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])#', $passwd)) 
		return true;
	else
		return false;
}
function check_email($email)
{
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
	{
  		return false; 
	}
	else
		return true;
}
function remove_tag($conn, $id, $name)
{
	$sql = "DELETE FROM tags where name = '$name' and  uid = '$id'";
	if (!($res = $conn->query($sql)))
		return false;
	return true;
}
function remove_image($img_name, $conn)
{
	$sql = "DELETE FROM img WHERE name = '" . $img_name ."'";
	if (!($res = $conn->query($sql)))
		return false;
	if (!unlink($img_name))
		return false;
	return true;
}
function remove_image_with_num($conn, $num, $id)
{
	$sql = "SELECT name from img where uid = '" . $id . "' and num = '" . $num . "'";
	if (!($res = $conn->query($sql)))
		return false;
	$row = $res->fetch();
	$name = $row[0];	
	$sql = "DELETE FROM img WHERE uid = '" . $id ."' and num = '" . $num . "'";
	if (!($res = $conn->query($sql)))
		return false;
	if (!unlink($name))
		return false;
	return true;
}
// function check_file_error($data, $conn)
// {
// 	$tmp = $data['error'];
// 	$errors = [];
// 	if (!isset($tmp['error']) || is_array($tmp['error'])) 
// 		$errors[12] = "Parametre invalide";
// 	else if ($tmp === UPLOAD_ERR_INI_SIZE || $tmp === UPLOAD_ERR_FORM_SIZE)
// 		$errors[12] = "La taille du fichier est trop importante";
// 	else if ($tmp === UPLOAD_ERR_NO_FILE)
// 		$errors[12] = "Aucun fichier n'a ete telecharge";
// 	else if ($tmp === UPLOAD_ERR_PARTIAL)
// 		$errors[12] = "Le fichier n'a été que partiellement téléchargé.";
// 	else if ($tmp === UPLOAD_ERR_NO_TMP_DIR)
// 		$errors[12] = "Un dossier temporaire est manquant";
// 	else if ($tmp === UPLOAD_ERR_CANT_WRITE)
// 		$errors[12] = "Échec de l'écriture du fichier sur le disque.";
// 	else if ($tmp === UPLOAD_ERR_EXTENSION)
// 		$errors[12] = "Une extension PHP a arrêté l'envoi de fichier.";
	
// 	return $errors;

// }
?>