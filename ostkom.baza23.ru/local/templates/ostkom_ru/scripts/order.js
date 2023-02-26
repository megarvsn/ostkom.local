$(document).ready(function() {	
   $('#order_form select[name="city"]').on('change', function() {
	  if($(this).val()){
        if($('#order_form input[name="land"]').val()==1){
			land="/"+$('#order_form input[name="land_id"]').val();
		}
        else{
			land="";
		}		
		$('#order_form select[name="street"]').load( land+"/order/get_address.php?t=s&id="+$('#order_form select[name="city"] option:selected').attr('id'));
	  }
   });	
   $('#order_form select[name="street"]').on('change', function() {
	  if($(this).val()){
		$('#order_form select[name="home"]').load( land+"/order/get_address.php?t=h&id="+$('#order_form select[name="street"] option:selected').attr('id'));
	  }
   });	  
   $('#order_form input[name="STAT"]').on('click', function() {
	   if($(this).prop("checked")){
		   $('#order_form input[name="NISP"]').val(""+$('#order_form select[name="city"]').val()+", "+$('#order_form select[name="street"]').val()+", "+$('#order_form select[name="home"] option:selected').text()+", "+$('#order_form input[name="flat"]').val());
	   }
       else{
		   $('#order_form input[name="NISP"]').val("");
	   }	   
   })

   $('#order_form input[type="submit"]').on('click', function() {
		if (!$('#order_form input[name="agree"').is(':checked')){
		   return false;	
		}
   });	  
   
});

