var SELECTIONS = {
	MY_ACCOUNT: 'my_account',
	DOGS_DB_OWNER: 'dog_db_owner',
	DONATIONS: 'donations',
	LOSSES: 'losses',
	GAINS: 'gains'
}
var navSelection;


$(document).ready(function() {
    $("#footer-nav").load("/includes/html/common.html");
	
	let footerNav = document.getElementById("footer-nav");
	if (footerNav) {
		let user = sessionStorage.getItem('userId');
		if (user) {
			footerNav.classList.remove("hide");
		}
		else {
			footerNav.classList.add("hide");
		}	
	}
});

	function setNavigationSelection(newNavSelection) {
		if (newNavSelection !== navSelection) {
			navSelection = newNavSelection;

			let myAccountButton = document.getElementById("my-account");
			let dogsButton = document.getElementById("dog_db_owner");
			let donationsButton = document.getElementById("donations");
			let lossesButton = document.getElementById("losses");
			let gainsButton = document.getElementById("gains");

			
			switch(newNavSelection) {
				case SELECTIONS.MY_ACCOUNT:
					myAccountButton.classList.add("selected-button");
					dogsButton.classList.remove("selected-button");
					donationsButton.classList.remove("selected-button");
					lossesButton.classList.remove("selected-button");
					gainsButton.classList.remove("selected-button");
					window.location.href = '/includes/php/my-profile.php';
					break;
					
				case SELECTIONS.DOGS_DB_OWNER:
					dogsButton.classList.add("selected-button");
					myAccountButton.classList.remove("selected-button");
					donationsButton.classList.remove("selected-button");
					lossesButton.classList.remove("selected-button");
					gainsButton.classList.remove("selected-button");
					window.location.href = 'https://www.moag.gov.il/Pages/DogSearch.aspx';
					break;

				case SELECTIONS.DONATIONS:
					donationsButton.classList.add("selected-button");
					dogsButton.classList.remove("selected-button");
					myAccountButton.classList.remove("selected-button");
					lossesButton.classList.remove("selected-button");
					gainsButton.classList.remove("selected-button");
					window.location.href = '/includes/php/donations_board.php';
					break;
				case SELECTIONS.LOSSES:
					lossesButton.classList.add("selected-button");
					donationsButton.classList.remove("selected-button");
					dogsButton.classList.remove("selected-button");
					myAccountButton.classList.remove("selected-button");
					gainsButton.classList.remove("selected-button");
					window.location.href = '/includes/php/lost_animal_board.php';
					break;
				case SELECTIONS.GAINS:
					gainsButton.classList.add("selected-button");
					donationsButton.classList.remove("selected-button");
					lossesButton.classList.remove("selected-button");
					myAccountButton.classList.remove("selected-button");
					dogsButton.classList.remove("selected-button");

					window.location.href = '/includes/php/found_animal_board.php';
					break;
			}
		}
	}
	
	

