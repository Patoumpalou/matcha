<?php
include("database.php");
//include("../model/function.php");
include('./../vendor/autoload.php');
include('./../vendor/fzaninotto/faker/src/autoload.php');
// echo "<a href='../index.php'>retour a la maison</a>";
try{
	$count = 750;
	$db = new PDO($DB_DSN1, $DB_USER, $DB_PASSWORD,  array(
        PDO::ATTR_PERSISTENT => true));
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "drop database if exists matcha; create database matcha";
	$db->exec($sql);
	$db = new PDO($DB_DSN2, $DB_USER, $DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
	$sql = "
	CREATE TABLE users (
	id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	pseudo varchar(30) NOT NULL, 
	email varchar(150) NOT NULL, 
	nom varchar(30) DEFAULT NULL, 
	prenom varchar(30) DEFAULT NULL,
	actif int(11) NOT NULL DEFAULT 0, 
	password text DEFAULT NULL, 
	logkey text DEFAULT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	
	CREATE TABLE user_data (
	id int NOT NULL PRIMARY KEY AUTO_INCREMENT, 
	age int default 0, 
	sexe varchar(10) default 'indefini', 
	location varchar(255) default null, 
	orientation varchar(255) default 'Bisexuel', 
	bio text, 
	score FLOAT NULL default '50',
	want varchar(30) default null,
	lat float NULL,
	lon float NULL,
	signaled boolean default false
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	CREATE TABLE img (
	id int NOT NULL PRIMARY KEY AUTO_INCREMENT, 
	uid int NOT NULL, 
	name varchar(255) NOT NULL, num int NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	CREATE TABLE tags (
	id int NOT NULL PRIMARY KEY AUTO_INCREMENT, 
	uid int NOT NULL, 
	name varchar(255) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	CREATE TABLE visits (
	id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
	id_visited int NULL,
	id_visit int NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	CREATE TABLE matches (
	id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
	small_id int not NULL,
	big_id int not null
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	CREATE TABLE likes (
	id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
	id_target int NOT NULL,
	id_author int NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	CREATE TABLE conns (
	id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
	status boolean DEFAULT NULL,
	ladate datetime DEFAULT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	CREATE TABLE unwanted (
	id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
	id_auteur int default null, 
	id_sujet int default null
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	CREATE TABLE messages (
	id int not null primary key AUTO_INCREMENT,
	content varchar(255) not null,
	id_author int default null, 
	name_author varchar(30) default null, 
	match_id int default null,
	udate datetime default null,
	seen boolean not null default false
	)ENGINE=InnoDB DEFAULT CHARSET=utf8;
	CREATE TABLE notifs (
	id int not null primary key AUTO_INCREMENT,
	id_target int not null, 
	id_auth int not null, 
	seen boolean not null default false,
	subject varchar(255) not null
	)ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	$db->exec($sql);

	// $seeder = new \tebazil\dbseeder\Seeder($db);
	// $generator = $seeder->getGeneratorConfigurator();
	//$faker = $generator->getFakerConfigurator();

	$faker = Faker\Factory::create('fr_FR');
	$faker->addProvider(new Faker\Provider\fr_FR\Person($faker));
	$faker->addProvider(new Faker\Provider\fr_FR\Address($faker));
	$faker->addProvider(new Faker\Provider\fr_FR\PhoneNumber($faker));
	$faker->addProvider(new Faker\Provider\fr_FR\Company($faker));
	$faker->addProvider(new Faker\Provider\Lorem($faker));
	$faker->addProvider(new Faker\Provider\Internet($faker));
	$stmt = $db->prepare("SET FOREIGN_KEY_CHECKS=0");
	$stmt->execute();

	foreach (range(1, 750) as $x)
	{
		$prenom = $faker->firstName($gender = 'male'|'female');
		$db->query("INSERT INTO users (pseudo, email, nom, prenom) VALUES 
			('{$faker->userName}', 
			'{$faker->email}',
			 '{$faker->lastName}', 
			 '" . $prenom . "')");
	}
	foreach (range(1, 750) as $x)
	{
		$lat = $faker->latitude($min = 43, $max = 49);
		$lon = $faker->longitude($min = 0, $max = 6);
		$geoloc = $faker->address;
		$bio = $faker->paragraph($nbSentences = 3, $variableNbSentences = true);
		$score = $faker->numberBetween($min = 0, $max = 100);
		$want = $faker->randomElement($array = array ('femme','homme', 'both', 'any'));
		$sexe = $faker->randomElement($array = array ('homme','femme'));
		$age = $faker->numberBetween($min = 14, $max = 70);
		$orientation = $faker->randomElement($array = array ('Heterosexuel','Homosexuel','Bisexuel', 'Pansexuel', 'Sapiosexuel'));
		$db->query("INSERT INTO user_data (lat, lon, age, sexe, location, orientation, bio, score, want) VALUES 
			(
			'". $lat . "', 
			'". $lon . "', 
			'". $age . "', 
			'". $sexe . "',
			 '".$geoloc."', 
			 '". $orientation . "',
			 '". $bio . "',
			 '". $score . "',
			 '". $want . "'
			)");
	}
	foreach (range(1, 750) as $x)
	{
		$tag = '#' . $faker->word;
		$uid = $faker->numberBetween($min = 1, $max = 750);
		$db->query("INSERT INTO tags (uid, name) VALUES 
			('". $uid . "', 
			'".$tag. "')");
	}
	$i = 1;
	foreach (range(1, 751) as $x)
	{
		$uid = $i;
		$imgurl = $faker->imageUrl($width = 640, $height = 480);
		$num = 4;
		$db->query("INSERT INTO img (uid, name, num) VALUES 
			('". $uid . "', 
			'".$imgurl."',
			'".$num."')");
		$i++;
	}
	$i = 1;
    $stmt = $db->prepare("SET FOREIGN_KEY_CHECKS=1");
    $stmt->execute();

    $sql = "INSERT into users ( pseudo, email, nom, prenom, actif, password) values ('qweqwe','patoumpalouu@hotmail.fr','leponge','bob','1','98ce74fe4eacbb70cf16920714135c61a838912663dbe0f3e3b60268253a8da870c1f8d8b36ddd557029688729b93a59945fb635c515e4a66986d434bca0cb6b');
    INSERT INTO user_data (lat, lon, want, location, bio, score, sexe, age, orientation) VALUES ('48.8966491', '2.31834989999993', 'femme', '96 Boulevard Bessières, 75017 Paris, France','asdasd', '50', 'homme', '32', 'Heterosexuel');
    INSERT INTO tags(uid, name) values ('751', '#alcool')";
    $db->exec($sql);
    $sql = "INSERT into users ( pseudo, email, nom, prenom, actif, password) values ('qwe','patoumpalouu@hotmail.fr','Jackob','Alfonse','1','354d50956ab1f07bceda7fb2bb53c3e08dc943948912c662fbf9b23aa1bb86d188bd69796c955381435ac94d2092b936da8f84afb5539bb72e4d84b1f7f751b6');
    INSERT INTO user_data (lat, lon, want, location, bio, score, sexe, age, orientation) VALUES ('48.8966491', '2.31834989999993', 'femme', '96 Boulevard Bessières, 75017 Paris, France','asdasd', '50', 'homme', '32', 'Heterosexuel');
    INSERT INTO tags(uid, name) values ('751', '#alcool')";
    $db->exec($sql);

} catch (PDOException $e){
	echo 'Connection failed: ' . $e->getMessage();
	die();
}
//// https://github.com/fzaninotto/Faker#fakerprovideren_usperson
/// https://www.youtube.com/watch?v=jdUM0r_LeMo
header("Location: http://localhost:8080/matcha/index.php");
?>