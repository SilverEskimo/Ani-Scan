// This sample uses the Autocomplete widget to help the user select a
// place, then it retrieves the address components associated with that
// place, and then it populates the form fields with those details.
// This sample requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:
// <script
// src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDjZHrFdJwtrfZjG9NC-2_cY3pCJqzva3M&libraries=places">

let placeSearch;
let autocomplete;
const componentForm = {
  street_number: "short_name",
  route: "long_name",
  locality: "long_name",
  administrative_area_level_1: "short_name",
  country: "long_name",
  postal_code: "short_name",
};

function initAutocomplete() {
  // Create the autocomplete object, restricting the search predictions to
  // geographical location types.
  autocomplete = new google.maps.places.Autocomplete(
    document.getElementById("street"),
    { types: ["geocode"], componentRestrictions: { country: 'il' } }
  );

  // When the user selects an address from the drop-down, populate the
  // address fields in the form.
  autocomplete.addListener("place_changed", fillInAddress);
}

function fillInAddress() {
	const place = autocomplete.getPlace();
	const lat = place.geometry.location.lat();
	const lng = place.geometry.location.lng();
	document.getElementById("lat").value = lat;
	document.getElementById("lng").value = lng;
}

// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition((position) => {
      const geolocation = {
        lat: position.coords.latitude,
        lng: position.coords.longitude,
      };
      const circle = new google.maps.Circle({
        center: geolocation,
        radius: position.coords.accuracy,
      });
      autocomplete.setBounds(circle.getBounds());
    });
  }
}

$(document).ready(function () {

var currgeocoder;


//Set geo location lat and long
navigator.geolocation.getCurrentPosition(function (position, html5Error) {
    geo_loc = processGeolocationResult(position);
    currLatLong = geo_loc.split(",");
    initializeCurrent(currLatLong[0], currLatLong[1]);
});

//Get geo location result
function processGeolocationResult(position) {
    html5Lat = position.coords.latitude; //Get latitude
    html5Lon = position.coords.longitude; //Get longitude
    html5TimeStamp = position.timestamp; //Get timestamp
    html5Accuracy = position.coords.accuracy; //Get accuracy in meters
    return (html5Lat).toFixed(8) + ", " + (html5Lon).toFixed(8);
}

//Check value is present or
function initializeCurrent(latcurr, longcurr) {
    currgeocoder = new google.maps.Geocoder();

    if (latcurr != '' && longcurr != '') {
        //call google api function
        var myLatlng = new google.maps.LatLng(latcurr, longcurr);
        return getCurrentAddress(myLatlng);
    }
}

//Get current address
function getCurrentAddress(location) {
    currgeocoder.geocode({
        'location': location
    }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            $("#address").val(results[0].formatted_address);
        } else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
    });
}



});

