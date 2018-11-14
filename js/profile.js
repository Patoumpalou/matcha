var x = document.getElementById("demo");

var like = document.getElementById("love");
if (like != null)
{
    like.onclick = function(){
        document.getElementById('formlove').submit();
    }
}
var match = document.querySelector('#match');
if (match != null){
        setTimeout(function(){ match.style= "opacity: 0.9;"; }, 1000);
        setTimeout(function(){ match.style= "opacity: 0.8;"; }, 1500);
        setTimeout(function(){ match.style= "opacity: 0.7;"; }, 2000);
        setTimeout(function(){ match.style= "opacity: 0.6;"; }, 2500);
        setTimeout(function(){ match.style= "opacity: 0.5;"; }, 3000);
        setTimeout(function(){ match.style= "opacity: 0.4;"; }, 3500);
        setTimeout(function(){ match.style= "opacity: 0.3;"; }, 4000);
        setTimeout(function(){ match.style= "opacity: 0.2;"; }, 4500);
        setTimeout(function(){ match.style= "opacity: 0.1;"; }, 5000);
        setTimeout(function(){ match.style= "opacity: 0.0;"; }, 5500);
        setTimeout(function(){ match.style="display:none;"; }, 6000);
        ;
}
function del_img(img)
{
    if (confirm("Voulez vous supprimer cette photo ?"))
        window.location.replace("http://localhost:8080/matcha/controler/profile.php?img=" + img);
}
const button = document.querySelector('#modifprofil');
if (button !== null){
    button.onclick = function() {
        window.location.replace('http://localhost:8080/matcha/controler/profile.php?modif=set');
    }
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
        navigator.geolocation.getCurrentPosition(getAdress);
    } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}
else{
    var elemdemo = document.querySelector('#demo');
    var latlon = elemdemo.title;
    elemdemo.title = '';
    var key = "AIzaSyDtnHLVfWMIe7h9cP2UIS559Jp_CgyUPhU" ;
    var img_url = "https://maps.googleapis.com/maps/api/staticmap?center="
    +latlon+"&zoom=14&size=400x300&key=" + key;
    document.getElementById("mapholder").innerHTML = "<img src='"+img_url+"'>";
}
// document.getElementById("me").onchange = function()
// {
//     document.getElementById("formimg").submit();
// };
//document.querySelectorAll('.pic').onclick = function();

// var unlike = document.querySelector(".dislike");
// if (unlike != null){
//     document.querySelector('#love').style = "background-color: grey;";
//     document.querySelector('#likebtn').style = "background-color: grey;";
// }


function showPosition(position) {
    var latlon = position.coords.latitude + "," + position.coords.longitude;
    var key = "AIzaSyDtnHLVfWMIe7h9cP2UIS559Jp_CgyUPhU" ;
    var img_url = "https://maps.googleapis.com/maps/api/staticmap?center="
    +latlon+"&zoom=14&size=400x300&key=" + key;
    document.getElementById("mapholder").innerHTML = "<img src='"+img_url+"'>";
}
//To use this code on your website, get a free API key from Google.
//Read more at: https://www.w3schools.com/graphics/google_maps_basic.asp

function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            x.innerHTML = "User denied the request for Geolocation."
            break;
        case error.POSITION_UNAVAILABLE:
            x.innerHTML = "Location information is unavailable."
            break;
        case error.TIMEOUT:
            x.innerHTML = "The request to get user location timed out."
            break;
        case error.UNKNOWN_ERROR:
            x.innerHTML = "An unknown error occurred."
            break;
    }
}

function getReverseGeocodingData(lat, lng) {
    var latlng = new google.maps.LatLng(lat, lng);
    // This is making the Geocode request
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'latLng': latlng }, function (results, status) {
        if (status !== google.maps.GeocoderStatus.OK) {
            alert(status);
        }
        // This is checking to see if the Geoeode Status is OK before proceeding
        if (status == google.maps.GeocoderStatus.OK) {
            var address = (results[0].formatted_address);
            var element = document.getElementById("adress");
            if (element !== null){
                element.innerHTML = "Localisation :" + address;
                // document.getElementById("jenova").value = address;
            }
        }
    });
}
var ineedloc = document.getElementById("ineedloc");
if (ineedloc !== null){
    locval = ineedloc.value;
    if (locval == "")
        showmeloc();
}
function showmeloc(){
    navigator.geolocation.getCurrentPosition(showPosition, showError);
    navigator.geolocation.getCurrentPosition(getAdress_bis);
}
function getAdress_bis(position){
    getReverseGeocodingData_bis(position.coords.latitude, position.coords.longitude);
}
function  getReverseGeocodingData_bis(lat, lng){
    var latlng = new google.maps.LatLng(lat, lng);
    // This is making the Geocode request
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'latLng': latlng }, function (results, status) {
        if (status !== google.maps.GeocoderStatus.OK) {
            alert(status);
        }
        // This is checking to see if the Geoeode Status is OK before proceeding
        if (status == google.maps.GeocoderStatus.OK) {
            var address = (results[0].formatted_address);
            // console.log(address);
            document.getElementById("ineedloc").value = address;
        }
    });
}

function getAdress(position) {
    getReverseGeocodingData(position.coords.latitude, position.coords.longitude);
}