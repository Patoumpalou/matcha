var get = location.search.substring(1).split('&');

function extractUrlParams(){	
	var t = location.search.substring(1).split('&');
	var f = [];
	for (var i=0; i<t.length; i++){
		var x = t[ i ].split('=');
		f[x[0]]=x[1];
	}
	return f;
}

var test = extractUrlParams();

if (test['sucess'] === "true")
{
	var newchild = document.createElement("p");
	newchild.setAttribute("id", "beta");
	document.querySelector("body").appendChild(newchild);
	document.getElementById('beta').innerHTML = "Operation Reussie";
	setTimeout(function() {
	document.querySelector("body").removeChild(document.getElementById('beta'));
	},2000);
}



