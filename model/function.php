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
function ft_radiogender($str){
	$tab = [];
	if ($str == 'homme')
		$tab[0] = 'checked';
	else
		$tab[0] = '';
	if ($str == 'femme')
		$tab[1] = 'checked';
	else
		$tab[1] = '';
	if ($str == 'autre')
		$tab[2] = 'checked';
	else
		$tab[2] = '';
	return $tab;
}
function ft_optionorientation($str){
	$tab = [];
	if ($str == 'Heterosexuel')
		$tab[0] = 'selected';
	else
		$tab[0] = '';
	if ($str == 'Homosexuel')
		$tab[1] = 'selected';
	else
		$tab[1] = '';
	if ($str == 'Bisexuel')
		$tab[2] = 'selected';
	else
		$tab[2] = '';
	if ($str == 'Pansexuel')
		$tab[3] = 'selected';
	else
		$tab[3] = '';
	if ($str == 'Sapiosexuel')
		$tab[4] = 'selected';
	else
		$tab[4] = '';
	return $tab;
}
function applyfilters($data, $tab, $conn){
	$res = [];
	$i = 0;
	// if(count($data['tag']) != 0)
	if(isset($data['tag'][0]) && $data['tag'][0] != "")
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
		unset($res);
		$res = [];
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
function parse_gender($str){
	if (strcmp($str, 'Heterosexuel') == 0)
		return true;
	if (strcmp($str, 'Homosexuel') == 0)
		return true;
	if (strcmp($str, 'Bisexuel') == 0)
		return true;
	if (strcmp($str, 'Pansexuel') == 0)
		return true;
	if (strcmp($str, 'Sapiosexuel') == 0)
		return true;
	else
		return false;
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
function parse_suggest($suggest, $conn, $id){
	$unwanted = [];
	$i = 0;
	$result = [];
	// recupere tous les id indesirables dans unwanted et dans liked
	$sql = "SELECT * from unwanted where id_auteur = '$id'"; // les id_sujets que tu veux pas
	$res = $conn->query($sql);
	$row = $res->fetchAll();
	foreach ($row as $value) {
		$unwanted[$i] = $value['id_sujet'];
		$i++;
	}
	$sql = "SELECT * from likes where id_author = '$id'";
	$res = $conn->query($sql);
	$row2 = $res->fetchAll();
	foreach($row2 as $value){
		$unwanted[$i] = $value['id_target'];
		$i++;
	}
	$i = 0;
	$unwanted = array_values(array_unique($unwanted));
	foreach ($suggest as $key => $value) {
		if (in_array($value['id'], $unwanted) == false)
			$result[$i] = $suggest[$i];
		$i++;
	}
	return array_values($result);
}
function make_distance($tab, $lat, $lon)
{
	$i = 0;
	foreach($tab as $someone){
		$distance = get_distance($someone['lat'], $lat, $someone['lon'], $lon);
		$tab[$i]['km'] = $distance;
		$i++;
	}
	return $tab;
}
function get_msgs($conn, $mid){
	$sql = "SELECT * from messages where match_id = '$mid' order by udate";
	if (!($res = $conn->query($sql)))
		return false;
	return $res->fetchAll();
}
function get_matches_id($conn, $id){
	$sql = "SELECT id, small_id from matches where big_id = '$id'";
	if (!($res = $conn->query($sql)))
		return false;
	$row = $res->fetchAll();
	$sql = "SELECT id, big_id from matches where small_id = '$id'";
	if (!($res = $conn->query($sql)))
		return false;
	$row2 = $res->fetchAll();
	$res = array_merge($row, $row2);
	return $res;
}
function get_status($conn, $id)
{
	$sql = "SELECT * from conns where id = '$id'";
	if (!($res = $conn->query($sql)))
		return false;
	if ($res->rowCount() == 0)
		return "Ne s'est jamais connecté";
	$row = $res->fetch();
	if ($row['status'] == '1')
		return "En ligne";
	$date = $row['ladate'];
	$date = $date[8] . $date[9] . "/" . $date[5] . $date[6] . "/" . substr($date, 0, 4). " à " . $date[11] . $date[12] . "h" . $date[14] . $date[15];
	return "Deconnecté depuis le ". $date;
}
function get_img_name_by_id_num($conn, $id, $num){
	$sql = "SELECT name from img where uid = '" . $id . "' and num = '" . $num . "'";
	if (!($res = $conn->query($sql)))
		return false;
	if ($res->rowCount() == 0)
		return false;
	$row = $res->fetch();
	return $row[0];
}
function get_suggest($id, $conn, $offset, $data)
{
	// $margin = 30;
	// $total1 = intval($data['score']) - $margin;
	// $total2 = intval($data['score']) + $margin;
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
	$sql = "SELECT id, lat, lon, age, score from user_data where (sexe = '" . $sexwanted ."') and want = '$oppsex'";
	// " and (score <= '$total2' or score >= '$total1')";
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
	$geocode=file_get_contents($url);
	$res = json_decode($geocode);
	if ($res->status == 'OK'){
		$lat = $res->results[0]->geometry->location->lat;
		$lon = $res->results[0]->geometry->location->lng;
	}
	else
		return false;
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
	$row = $res->fetchAll();
	foreach ($row as $key => $value) {
		$row[$key]['img'] = get_img_name_by_id_num($conn, $value['id_author'], '4');
	}
	return $row;
}
function get_notifs($conn, $id){
	$sql = "SELECT * from notifs where id_target = '$id' and seen = '0'";
	if (!($res = $conn->query($sql)))
		return false;
	$row = $res->fetchAll();
	return $row;
}
function get_all_notifs($conn, $id){
	$sql = "SELECT * from notifs where id_target = '$id' ORDER BY id DESC ";
	if (!($res = $conn->query($sql)))
		return false;
	$row = $res->fetchAll();
	return $row;
}
function create_conns($conn, $id)
{
	$sql = "INSERT INTO conns(id) SELECT $id WHERE NOT EXISTS (SELECT * from conns where id = '$id')";
	if (!($res = $conn->query($sql)))
		return false;
	else
		return true;	
}
function create_user_data($user, $conn)
{
	$sql = "SELECT * FROM user_data where id = '". $user['id'] . "'";
	$res = $conn->query($sql);
	if ($res->rowCount() != 0)
		return false;
	$sql = "INSERT INTO user_data(id, age, sexe) VALUES ('" . $user['id'] . "','".$user['age']."','".$user['sex']."')";
	if (!($res = $conn->query($sql)))
		return false;
	else
		return true;
}

function add_notif($conn, $id_target, $id_auth, $subject){
	if (check_signaled($conn, $id_auth) == true)
		return false;
	if ($subject == 'match'){
		$sql = "SELECT id from notifs where id_target = '$id_target' and id_auth = '$id_auth' and subject = '$subject' and seen = '0'";
		if (!($res = $conn->query($sql)))
			return false;
		if ($res->rowCount() > 0)
			return true;
	}
	if ($subject == 'visit'){
		$sql = "SELECT id from notifs where id_target = '$id_target' and id_auth = '$id_auth' and subject = '$subject'";
		if (!($res = $conn->query($sql)))
			return false;
		if ($res->rowCount() > 0)
			return true;
	}
	$sql = "INSERT INTO notifs(id_target, id_auth, subject) VALUES ('$id_target', '$id_auth', '$subject') ";
	if (!($res = $conn->query($sql)))
		return false;
	else
		return true;
}
function add_msg($conn, $data){
	$sql = "INSERT INTO messages (content, id_author, name_author, match_id, udate) VALUES (
	'" .$data['content']. "', '" .$data['id'] . "', '" .$data['prenom'] ."', '".$data['matchid']."', '".$data['date']. "') ";
	if (!($res = $conn->query($sql)))
		return false;
	return true;
}
function add_match($conn, $id1, $id2){
	if ($id1 > $id2){
		$tmp =$id1;
		$id1 = $id2;
		$id2 = $tmp;
	}
	$sql = "SELECT * from matches where small_id = '$id1' and big_id = '$id2'";
	if (!($res = $conn->query($sql)))
		return false;
	if ($res->rowCount() == 0){
		$sql = "INSERT INTO matches(small_id, big_id) VALUES ('$id1', '$id2') ";
		$conn->query($sql);
		return true;
	}
	return false;
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
function add_or_del_unwanted($conn, $id_auteur, $id_sujet, $bool){
	if ($bool == '0'){
		$sql = "DELETE FROM `unwanted` WHERE id_auteur = '$id_auteur' and id_sujet = '$id_sujet'";
	}
	else if ($bool == '1'){
		$sql = "INSERT INTO unwanted(id_auteur, id_sujet) values ('$id_auteur', '$id_sujet')";
	}
	if(!($res = $conn->query($sql)))
		return false;
	return true;
}
function update_score($conn, $id, $val, $str){
	// if ($str == 'more'){
	// 	$sql = "UPDATE user_data set score = score + $val where id = '$id'";
	// }
	// else if ($str == 'less'){
	// 	$sql = "UPDATE user_data set score = score - $val where id = '$id'";
	// }
	// else if ($str == 'visit'){
	// 	$sql = "SELECT * from visits where id_visited = '$id' and id_visit = '$val'";
	// 	$res = $conn->query($sql);
	// 	if ($res->rowCount() == 0)
	// 		update_score($conn, $id, '1', 'more');
	// 	return true;
	// }
	// else
	// 	return false;
	// if(!($res = $conn->query($sql)))
	// 	return false;
	return true;
}
function update_notifs($conn, $id){
	$sql = "UPDATE notifs set seen = true where id_target = '$id'";
	if(!($res = $conn->query($sql)))
		return false;
	return true;
}
function update_msg($conn, $mid, $id){
	$sql = "UPDATE messages SET seen = true where match_id = '$mid' and id_author = '$id'";
	if(!($res = $conn->query($sql)))
		return false;
	return true;
}
function update_conns($conn, $id, $date, $bool)
{
	$sql = "UPDATE conns SET status = '$bool', ladate = '$date' where id = '$id'";
	if(!($res = $conn->query($sql)))
		return false;
	return true;
}
function update_coords($conn, $id, $lat, $lon)
{
	$sql = "UPDATE user_data SET lat = '$lat', lon = '$lon' where id = '$id'";
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
function check_log($conn, $id, $date){
	$sql = "SELECT * from conns where id = '$id' and status = '0'";
 	if (!($res = $conn->query($sql)))
		return false;
	if ($res->rowCount() == 0){
		return false;
	}
	$sql = "UPDATE conns set status = '1' and ladate = '$date' where id = '$id'";
 	if (!($res = $conn->query($sql)))
		return false;
	return true;
}
function check_unseen_msg($conn, $mid, $otherid){
 	$sql = "SELECT * from messages where match_id = '$mid' and seen = '0' and id_author = '$otherid'";
 	if (!($res = $conn->query($sql)))
		return false;
	if ($res->rowCount() == 0)
		return false;
	return true;

}
function check_signaled($conn, $id){
 	$sql = "SELECT * from user_data where id = '$id' and signaled = 'true'";
 	if (!($res = $conn->query($sql)))
		return false;
	if ($res->rowCount() != 0)
		return false;
	return true;

}
function check_actu($conn, $lastmsg, $mid){
	$sql = "SELECT content from messages where match_id = '$mid' order by udate desc";
	if (!($res = $conn->query($sql)))
		return false;
	$row = $res->fetch();
	if (strcmp($row['content'], $lastmsg) != 0)
		return true;
	return false;
}
function check_if_bloqued($conn, $id_auteur, $id_sujet){
	$sql = "SELECT * from unwanted where id_auteur = '$id_auteur' and id_sujet = '$id_sujet'";
	if (!($res = $conn->query($sql)))
		return false;
	if ($res->rowCount() == 0)
		return false;
	return true;
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
  		return false; 
	else
		return true;
}
function remove_match($conn, $id1, $id2){
	if ($id1 > $id2){
		$tmp =$id1;
		$id1 = $id2;
		$id2 = $tmp;
	}
	$sql = "DELETE FROM matches where small_id = '$id1' and  big_id = '$id2'";
	if (!($res = $conn->query($sql)))
		return false;
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