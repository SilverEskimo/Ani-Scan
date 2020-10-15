$(document).ready(function(){

    $("#sel_city").change(function(){
        var city = $(this).val();
	
        $.ajax({
            url: '../php/get_vets.php',
            type: 'post',
            data: {cities:city},
            dataType: 'json',
            success:function(response){
	
                var len = response.length;
		var firstApp = "בחר";
		$("#sel_vet").empty();
		$("#sel_vet").append("<option value='"+firstApp+"'>"+firstApp+"</option>");
                for( var i = 0; i<len; i++){

                    var vetName = response[i]['vet_name'];
                    $("#sel_vet").append("<option value='"+vetName+"'>"+vetName+"</option>");
		
                }
            }
        });
    });

});
