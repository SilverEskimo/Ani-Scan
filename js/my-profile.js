var selectedDiv;
var PROFILE_SELECTIONS = {
	MY_REPORTS: 'my-reports',
	MY_NOTIFICATIONS: 'my-notifications',
	MY_DETAILS: 'my-details',
}

$(document).ready(function(){
	let firstName = sessionStorage.getItem('firstName');
	if (firstName) {
		let message = "אהלן " + firstName + "!";
        $('#firstName').html(message);
	}
	
	let savedAnimals = sessionStorage.getItem('savedAnimals');
	let message = "";
	if (savedAnimals === "0") {
		message = "טרם הצלת בעלי חיים :(";
	} else {
		message = "עד כה הצלת " + savedAnimals + " בעלי חיים";
	}
	
	$('#animalsFound').html(message);
	
	$('#first-name').val(sessionStorage.getItem('firstName'));
	$('#last-name').val(sessionStorage.getItem('lastName'));
	$('#email').val(sessionStorage.getItem('email'));
	$('#street').val(sessionStorage.getItem('street'));
	$('#phone').val(sessionStorage.getItem('phone'));
	if (sessionStorage.getItem('hasChipScanner') === "1"){
		$('#has-chip-scanner').prop('checked', true);
	}
	if (sessionStorage.getItem('agreeToMessage') === "1"){
		$('#agree-to-message').prop('checked', true);
	}

	let notifications = sessionStorage.getItem('notifications');
	notifications = JSON.parse(notifications);
	let tbl = document.getElementById('notifications-table');
	if (notifications.length != 0) {
		tbl.style.width = '100%';
		tbl.setAttribute('border', '1');
		let tbdy = document.createElement('tbody');
		
		for (let i = 0; i < notifications.length; i++) {
			let tr = document.createElement('tr');
			let td = document.createElement('td');
			td.classList.add("notification");
			
			let a = document.createElement('a');
			a.href = "/includes/php/found_animal_board.php?id=" + notifications[i].animal_id;
			a.innerHTML = notifications[i].text;
			td.appendChild(a);
			
			let span = document.createElement('span');
			span.classList.add("delete-notification");
			span.id = notifications[i].id;
			span.innerHTML = '&times;';
			td.appendChild(span);
			
			tr.appendChild(td)
			tbdy.appendChild(tr);
		}
		tbl.appendChild(tbdy); 
	} else {
		$("#my-notifications").text("אין התראות חדשות");
	}
	
	
	$(".delete-notification").on("click", function(){
		let id = $(this)[0].id;
		let data = { 'id': id };
		$.ajax({
			type: 'post',
			url: '/includes/php/delete_notification.php',
			headers: {
				'Access-Control-Allow-Origin': '*'
			},
			data: data,
			datatype: 'json',
			async:false,
			success: function(data) { 
				alert("התראה נמחקה בהצלחה");
				notifications = notifications.filter(notification => notification.id != id);
				sessionStorage.removeItem('notifications');
				notifications = JSON.stringify(notifications);
				sessionStorage.setItem('notifications', notifications);
				window.location.href='/includes/php/my-profile.php';
			},
			error: function(){
				alert('error');
			}
		 });
	});
	
	$(".delete-animal-post").on("click", function(){
		let id = $(this)[0].id;
		let data = { 'id': id };
		$.ajax({
			type: 'post',
			url: '/includes/php/delete_animal_post.php',
			headers: {
				'Access-Control-Allow-Origin': '*'
			},
			data: data,
			datatype: 'json',
			async:false,
			success: function(data) { 
				alert("הפרסום נמחק בהצלחה");
				window.location.href='/includes/php/my-profile.php';
			},
			error: function(){
				alert('error');
			}
		 });
	});
	
	$(".post-status").change(function(){
		let id = $(this)[0].id;
		let data = { 'id': id, 'type': "my-reports" };
		let value = $(this)[0].value;
		
		if (value === "חזר לבעלים") {
			if (confirm("האם אתה מאשר שהכלב חזר לבעליו? שינוי זה ימחק את הדיווח")) {
				$.ajax({
					type: 'post',
					url: '/includes/php/delete_animal_post.php',
					headers: {
						'Access-Control-Allow-Origin': '*'
					},
					data: data,
					datatype: 'json',
					async:false,
					success: function(data) { 
						alert("הפרסום נמחק בהצלחה");
						sessionStorage.setItem('savedAnimals', data);
						window.location.href='/includes/php/my-profile.php';
					},
					error: function(){
						alert('error');
					}
				});
			}
			else {
				$(this)[0].value = "משוטט";
			}
		}
	});
	
	
	// $(".post-status").on("change", function(){
		// let id = $(this)[0].id;
		// let data = { 'id': id, 'type': "my-reports" };
		// let value = $(this)[0].value;
		
		// if (value === "חזר לבעלים") {
			// if (confirm("האם אתה מאשר שהכלב חזר לבעליו? שינוי זה ימחק את הדיווח")) {
				// $.ajax({
					// type: 'post',
					// url: '/includes/php/delete_animal_post.php',
					// headers: {
						// 'Access-Control-Allow-Origin': '*'
					// },
					// data: data,
					// datatype: 'json',
					// async:false,
					// success: function(data) { 
						// alert("הפרסום נמחק בהצלחה");
						// sessionStorage.setItem('savedAnimals', data);
						// window.location.href='/includes/php/my-profile.php';
					// },
					// error: function(){
						// alert('error');
					// }
				// });
			// }
			// else {
				// $(this)[0].value = "משוטט";
			// }
		// }
	// });
});

function changeDivSelection(newDivSelection) {
	let myReports = document.getElementById("my-reports");
	let myNotifications = document.getElementById("my-notifications");
	let myDetails = document.getElementById("my-details");
		
	if (selectedDiv !== newDivSelection) {
		
		selectedDiv = newDivSelection;
		
	
		if (newDivSelection === PROFILE_SELECTIONS.MY_REPORTS) {
			myReports.style.display = "block";
			myNotifications.style.display = "none";
			myDetails.style.display = "none";
		} else if (newDivSelection === PROFILE_SELECTIONS.MY_NOTIFICATIONS) {
			myReports.style.display = "none";
			myNotifications.style.display = "block";
			myDetails.style.display = "none";
		}
		else {
			myReports.style.display = "none";
			myNotifications.style.display = "none";
			myDetails.style.display = "block";
		}
	} else {
		if (newDivSelection === PROFILE_SELECTIONS.MY_REPORTS) {
			myReports.style.display = myReports.style.display === "none" ? "block" : "none";
		} else if (newDivSelection === PROFILE_SELECTIONS.MY_NOTIFICATIONS) {
			myNotifications.style.display = myNotifications.style.display === "none" ? "block" : "none";
		}
		else {
			myDetails.style.display = myDetails.style.display === "none" ? "block" : "none";
		}
	}
}

