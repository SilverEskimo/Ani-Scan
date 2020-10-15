"use strict";

$(document).ready(function(){
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(setMapCenterToUserLocation);
    }
	
	if (sessionStorage.getItem('userId')) {
		$.ajax({
		type: 'post',
		url: '/includes/php/get_all_animals.php',
		headers: {
			'Access-Control-Allow-Origin': '*'
		},
		data: '',
		async:false,
		success: function(response) { 
			let animals = JSON.parse(response);
			for (let i = 0; i < animals.length; i++){
				createMarkerOnMap({lat: animals[i].lat, lng: animals[i].lng});
			}
		},
		error: function(){
			alert('error loading lost animals');
		}
	 });
	}
	  
	if (sessionStorage.getItem('firstName')) {
        $('#log-out').css({"display": "inline"});
		$('#found').css({"display": "inline"});
		$('#lost').css({"display": "inline"});
		
		let message = "שלום " +sessionStorage.getItem("firstName");
        $('#firstName').html(message);
    } else {
        $('#sign-in').css({"display": "inline"});
        $('#sign-up').css({"display": "inline"});
    } 
});


let map;

function initMap() {
  map = new google.maps.Map(document.getElementById("map"), {
    center: {
      lat: 31.975194,
      lng: 34.8133206
    },
    zoom: 10,
	streetViewControl: false,
	mapTypeControl: false
  });
}

function setMapCenterToUserLocation(position) {
    map.setCenter({lat: position.coords.latitude, lng: position.coords.longitude});
    map.setZoom(17);
    let url = "https://maps.google.com/mapfiles/ms/icons/blue-dot.png";
	var marker = new google.maps.Marker({
            map: map,
            position: new google.maps.LatLng(position.coords.latitude, position.coords.longitude),
			icon: {
			  url: url
			}
        });
}

function createMarkerOnMap(position){
	var marker = new google.maps.Marker({
            map: map,
            position: new google.maps.LatLng(position.lat, position.lng)
        });
}
