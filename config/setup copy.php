<?php
include("database.php");
//include("../model/function.php");
include('./../vendor/autoload.php');
include('./../vendor/fzaninotto/faker/src/autoload.php');

try{
	$count = 750;
	$db = new PDO($DB_DSN1, $DB_USER, $DB_PASSWORD,  array(
        PDO::ATTR_PERSISTENT => true));
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "create database matcha";
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
	password text NOT NULL, 
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
	want varchar(30) default null
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
	";
	$db->exec($sql);

	$seeder = new \tebazil\dbseeder\Seeder($db);
	$generator = $seeder->getGeneratorConfigurator();
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
	$seeder->table('users')->columns([
	            'id', //automatic pk
	            'pseudo'=>$faker->userName,
	            'email'=>$faker->email,
	            'nom'=>$faker->lastName,
	            'prenom'=>$faker->firstName($gender = 'male'|'female'),
	            'password'=>$faker->password      
	        ])->rowQuantity( $count );

	 $seeder->table('user_data')->columns([ 
	            'age'=>$faker->numberBetween($min = 18, $max = 100),
	            'sexe'=>$faker->randomElement($array = array ('homme','femme','autre')),
	            'location'=>$faker->city, 
	            'orientation'=>$faker->randomElement($array = array ('Heterosexuel','Homosexuel','Bisexuel', 'Pansexuel', 'Sapiosexuel')), 
	            'bio'=>$faker->paragraph($nbSentences = 3, $variableNbSentences = true), 
	            'score'=>$faker->numberBetween($min = 0, $max = 100),
	            'want'=>$faker->randomElement($array = array ('femme','homme', 'both', 'any'))
	        ])->rowQuantity( $count );

	 $seeder->table('tags')->columns([
	 			'uid'=>$faker->numberBetween($min = 1, $max = 750),
	 			'name'=>$faker->word
	 ])->rowQuantity( $count );

	$seeder->refill();

    $stmt = $db->prepare("SET FOREIGN_KEY_CHECKS=1");
    $stmt->execute();

    // $sql = "INSERT into users (pseudo, email, nom, prenom, actif, password) values ('qweqwe','patoumpalou@hotmail.fr','leponge','bob','1','98ce74fe4eacbb70cf16920714135c61a838912663dbe0f3e3b60268253a8da870c1f8d8b36ddd557029688729b93a59945fb635c515e4a66986d434bca0cb6b')";
    // $db->exec($sql);

} catch (PDOException $e){
	echo 'Connection failed: ' . $e->getMessage();
	die();
}
//// https://github.com/fzaninotto/Faker#fakerprovideren_usperson
/// https://www.youtube.com/watch?v=jdUM0r_LeMo
header("Location: http://localhost:8080/matcha/index.php");
?>