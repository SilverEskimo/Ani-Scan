
$(document).ready(function () {
	var geocoder = new google.maps.Geocoder();

	// $('#submit-btn').on('click touchstart', function(event) {
		// event.preventDefault();
		// let firstName = $('#first-name').val();
		// let lastName = $('#last-name').val();
		// let email = $('#email').val();
		// let street = $('#street').val();
		// let phone = $('#phone').val();
		// let pass = $('#password').val();
		// let hasChip = $('#has-chip-scanner').prop("checked") ? 1 : 0 ;
		// let agreeToMessage = $('#agree-to-message').prop("checked") ? 1 : 0 ;
	
		// let lat = "";
		// let lng = "";
		// geocoder.geocode({
				// 'address': street 
		// }, function(results, status) {
			// if (status == google.maps.GeocoderStatus.OK) {
				// lat = results[0].geometry.location.lat();
				// lng = results[0].geometry.location.lng();
			
				
				// let newUser = { 'first-name': firstName, 'last-name': lastName, email: email, street: street, phone: phone, 'password': pass, 'has-chip-scanner': hasChip, 'agree-to-message': agreeToMessage, 'lat': lat, 'lng': lng};
			 
				// $.ajax({
					// type: 'post',
					// url: '/includes/php/sign-up.php',
					// headers: {
						// 'Access-Control-Allow-Origin': '*'
					// },
					// data: newUser,
					// datatype: 'json',
					// async:false,
					// success: function(data) { 
						// window.location.href='/includes/php/sign-in.php';
                    // },
					// error: function(){
						// alert('error');
					// }
				 // });
			// } else {
				// console.log("unable to find address: " + status);
			// }
		// });
    // });
});



function registerNewUser(firstName,lastName,email,phone,street,pass,hasChip,agreeToMessage){
	let lat = "";
	let lng = "";
	geocoder.geocode({
			'address': street 
	}, function(results, status) {
		 if (status == google.maps.GeocoderStatus.OK) {
			 lat = results[0].geometry.location.lat();
			 lng = results[0].geometry.location.lng();
			
			 let newUser = { 'first-name': JSON.parse(firstName), 'last-name': JSON.parse(lastName), email: JSON.parse(email), street: JSON.parse(street), phone: JSON.parse(phone), 'password': JSON.parse(pass), 'has-chip-scanner': JSON.parse(hasChip), 'agree-to-message': JSON.parse(agreeToMessage)};
			 
			  $.ajax({
					type: 'POST',
					url: '/includes/php/sign-up.php',
					data: newUser,
					dataType: 'json',
					async:false
				});
		 } else {
			 console.log("Unable to find address: " + status);
		 }
	});
}