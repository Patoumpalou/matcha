<?php

include("database.php");
include("../model/function.php");
include('./../vendor/autoload.php');

try {
	$count = 750;
	$db = new PDO($DB_DSN2, $DB_USER, $DB_PASSWORD,  array(
        PDO::ATTR_PERSISTENT => true
    ));	
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	
	$seeder = new \tebazil\dbseeder\Seeder($db);
	$generator = $seeder->getGeneratorConfigurator();
	$faker = $generator->getFakerConfigurator();
	//$faker = Faker\Factory::create('fr_FR');
	$stmt = $db->prepare("SET FOREIGN_KEY_CHECKS=0");
	$stmt->execute();
	$seeder->table('users')->columns([
	            'id', //automatic pk
	            'pseudo'=>$faker->userName,
	            'email'=>$faker->email,
	            'password'=>$faker->password
	            
	            
	        ])->rowQuantity( $count );

	 $seeder->table('user_data')->columns([
	            'nom'=>$faker->lastName,
	            'prenom'=>$faker->firstName($gender = 'male'|'female'),
	            'age'=>$faker->numberBetween($min = 18, $max = 100),
	            'sexe'=>$faker->randomElement($array = array ('homme','femme','autre')),
	            'location'=>$faker->city, 
	            'orientation'=>$faker->randomElement($array = array ('Heterosexuel','Homosexuel','Bisexuel', 'Asexuel', 'Transexuel')), 
	            'bio'=>$faker->paragraph($nbSentences = 3, $variableNbSentences = true), 
	            'score'=>$faker->numberBetween($min = 0, $max = 100)
	        ])->rowQuantity( $count );

	 $seeder->table('tags')->columns([
	 			'uid'=>$faker->numberBetween($min = 1, $max = 750),
	 			'name'=>$faker->word


	 ])->rowQuantity( $count );

	$seeder->refill();

    $stmt = $db->prepare("SET FOREIGN_KEY_CHECKS=1");
    $stmt->execute();

}
catch (PDOException $e){
	echo 'Connection failed: ' . $e->getMessage();
}

?>