

var selectedAnimalType = '';


$(document).ready(function() {
	
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
		
		let elements = document.getElementsByClassName("animal-post");
		[...elements].filter(
			element =>  (element.getElementsByClassName("user-details")[0].innerHTML.includes(value) || element.getElementsByClassName("user-address")[0].innerHTML.includes(value)) ? element.hidden = false : element.hidden = true
		);
      });
});




function changeSelectedAnimalType(newSelectedAnimalType) {
	if (selectedAnimalType != newSelectedAnimalType) {
		selectedAnimalType = newSelectedAnimalType;
		filterFoundAnimals();
	}
}

function filterFoundAnimals() {
	$.each($("[id=animals]").find("div"), function(index, div) {
        let divAnimalType = div.id;
        if (divAnimalType) {
            let divSelector = '#' + div.id;
            if (divAnimalType != selectedAnimalType) {
                $(divSelector).hide();
            }
            else {
                $(divSelector).show();
            }
        }
    });    
}



