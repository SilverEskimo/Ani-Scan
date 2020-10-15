$(document).ready(function(){

    $("#sel_vet").change(function(){
        var vet = $(this).val();
	
        $.ajax({
            url: '../php/get_link.php',
            type: 'post',
            data: {veterinars:vet},
            dataType: 'json',
            success:function(response){
              var link = response[0]['link'];
	      var vetPhone = response[0]['vetPhone'];
              $("#pbLink").val(link);
             
	      $("#phone").val(vetPhone);
	    

            }

         
        });
    });

});
