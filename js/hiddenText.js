$(document).ready(function(){  
 $('#sel_city').change(function() {

    if($('#sel_city').val() == 'Other')
    {
      var other = true;
    }
    $('#cityInputText').css('display', ($("#sel_city").val() == 'Other' ? 'block' : 'none'));
    $('#vetNameText').css('display', ($("#sel_city").val() == 'Other' ? 'block' : 'none'));
    $('#phone').attr('readonly', ($("#sel_city").val() == 'Other' ? false : true));
    $('#pbLink').attr('readonly', ($("#sel_city").val() == 'Other' ? false : true));
    $('#pbLink').val($("#sel_city").val() == 'Other' ? "":""); 
    $('#phone').val($("#sel_city").val() == 'Other' ? "":""); 
    
});
});
