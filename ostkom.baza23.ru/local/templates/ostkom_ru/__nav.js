$(document).ready(function() {	
   if($('div').is('#cookie_warning')){
	   var fnCookie = function() {
	       if($(document).scrollTop() >= HeightCookie) {   
	          $('.header').css({'padding-top':HeightCookie+'px'}); 
		   }
		   else{
			   $('.header').css({'padding-top':'0px'}); 
		   }
       }
	   HeightCookie=$('#cookie_warning').height();
	   $('body').css({'padding-top':HeightCookie+'px'});
	   $(document).bind('scroll', fnCookie);
	   $('#cookie_warning .btn--primary').on('click', function() {
		    $('body').css({'padding-top':'0px'});
            $('#cookie_warning').detach();
			$('.header').css({'padding-top':'0px'}); 
			$(document).unbind('scroll', fnCookie);
            $.get( "/set_sessin.php?cookie=fcookie");
	   })	   
   }
   if($('div').is('.calc__details')){
	   $('div.calc__details').detach().appendTo('body');
	   $('div.calc__details').css({'position':'fixed','bottom':'0px','background':'#fff','width':'100%','min-width':'100%','padding-bottom':'20px','box-shadow':'0px -2px 2px 0px rgba(0,0, 0, 0.15)'});  
	   $('div.calc__details .wp_calc__details').css({'width':'540px','margin':'0px auto'});
	   $('div.calc__details .calc__details-action,div.calc__details table').css({'margin-top':'0px'});
	   $('body').css({'padding-bottom': $('div.calc__details').outerHeight()+'px'});
	   $('div.calc__details input[type="submit"]').on('click', function() {
             $('#form_calculator').submit();
       });
   }
   $('.markup-nav').on('click', function() {
       $(this).toggleClass('is-open');
   });	
   $('.location__geo-link').on('click', function() {
     $.get( "/set_city.php?c="+$(this).attr("date") , function( data ) {
	     location.reload(true);
		 return(false);
      });
	 $(".top-panel__location-text").html($(this).html());
	 $("#location").css("display","none");
	
   }); 
   $('a#home,a#business').on('click', function() {
     href=$(this).attr("href");
     $.get( "/set_sessin.php?s="+$(this).attr("id"), function( data ) {
       window.location.href = href;
     });
     return(false);	
   });   
   $('a[data-target="#office-contacts"]').on('click', function(e) {
	   e.preventDefault();
	   var t=$(this),n=$(t.data("target"));
	   if(n.length){
		   t.toggleClass("is-active");
	   }
	   if(t.hasClass("is-active")){
		   $('#office-contacts').animate({height: 'show'}, 500,function(){
			   $.scrollTo('#help_block_scroll',300);   
		   });  
	   }
	   else{
		   t.removeClass("is-active");	   
		   $('#office-contacts').animate({height: 'hide'}, 500); 		   
	   }
   });
   $('a.office-contacts__close').on('click', function(e){
	  	  $('a[data-target="#office-contacts"]').removeClass("is-active");
		  $('#office-contacts').animate({height: 'hide'}, 500); 
   })
  
   /*if($(".top-panel__location-text").html()==""){   
      $.get("https://ipinfo.io/json", function (response) {
         $(".top-panel__location-text").html(response.city);
      }, "jsonp");
   }  */ 
});