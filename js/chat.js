document.querySelector('#contentbox').scroll(0,1000000);
$('#contentbox').load('chatt.php');
setInterval(function(){
	$('#actucontainer').load('actu.php');
}, 1000);


var auto_refresh = setInterval(
function ()
{
	if (document.querySelector('#actu') != null){
	$('#contentbox').load('chatt.php').fadeIn("slow");
	}
}, 2000);


 // toutes les 1sec regarde la bdd si changement
 // , si changement actualise chatt.php
 // le changement n'en est plus un 


 // pop toutes les 1s un php qui check_actu
 // si il trouve actu, fais une div actu
 // toutes les 1s check si div actu 
 //si div actu , reload chatt.php et enleve la div actu