window.onkeyup = function(e){
	var key = e.keycode ? e.keycode : e.which;
	if (key == 39)
	{
		var btn = document.getElementById("btn-droite");
		if (btn !== null)
			btn.click();
	}
	if (key == 37)
	{
		var btn = document.getElementById("btn-gauche");
		if (btn !== null)
			btn.click();
	}
}

document.querySelector(".form-control").onchange = function(){
	
	document.querySelector("#settingsform").submit();
}
document.querySelector('body').onload = function(){
	var bob = document.querySelectorAll("input[class='multirange ghost']");
	bob[0].name = 'age2';
	bob[1].name = 'pop2';
	var x2 = document.querySelector("input[name='age2']");
	x2.onchange = function(){
		update_age(x.value);
	};
	var z2 = document.querySelector("input[name='pop2']");
	z2.onchange = function(){
		update_score(z.value);
	};
};

function update_age(value){
	var p_age = document.querySelector('#p_age');
	var min = value.substr(0, 2);
	var max = value.substr(3);
	var str = "Age compris entre: " + min + " et " + max;
	p_age.innerHTML = str;
}
function update_score(value){
	console.log(value);
	console.log(value.length);
	var p_score = document.querySelector('#p_score');
	if (value[1] == ',')
		value = '0' + value;
	var min = value.substr(0, 2);
	var max = value.substr(3);
	var str = "Score compris entre: " + min + " et " + max;
	p_score.innerHTML = str;
}
var x = document.querySelector("input[name='age']");
x.onchange = function(){
	update_age(x.value);
};
var z = document.querySelector("input[name='pop']");
z.onchange = function(){
	update_score(z.value);
};
var y = document.querySelector("input[name='distance']");
y.onchange =function(){
	var p_km = document.querySelector('#p_km');
	var val = y.value;
	var str = "Distance max: " + val + " km";
	p_km.innerHTML = str;
}

// var x = document.querySelector("input[name='age']");
// x.onchange = function(){
// 	console.log(x.value);
// };
// z = document.querySelector("input[name='pop']");
// z.onchange = function(){
// 	console.log(z.value);
// };

// 39 droite 
// 37 gauche 

