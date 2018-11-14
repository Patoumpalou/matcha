function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}

setInterval(function(){
		$('#omegabox').load('check_notif.php');
}, 1000);

// window.onbeforeunload = function(){
// 	$('#myTopnav').load('outlogout.php');
// 	// event.returnValue = 'chaine de caractere';
// 	// event.preventDefault();
// }