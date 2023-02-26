$(document).ready(function(){
    $('.popup_bl').click(function (){
		var cont_top = window.pageYOffset ? window.pageYOffset : document.body.scrollTop;
		$('<div id="popup"><div id="wp_popup"><div class="mfeedback"></div></div></div>').appendTo($("body"));
		$('#wp_popup .mfeedback').load($(this).attr("href"), function (){
			$('<div class="close"></div>').appendTo($("#wp_popup .mfeedback"));
			$('#popup').css({"height": $(document).height(), "display": "block"});
			var popupWidth =  ($(window).width()-$("#wp_popup>div").innerWidth())/2;
			
			if(($("#wp_popup").innerHeight()+50)<$(window).height()){
			    var popupHeight = ($(window).height()-$("#wp_popup").innerHeight())/2+window.pageYOffset;
            }
			else{
				var popupHeight = window.pageYOffset+100;
			}			
			$('#wp_popup').css({"top": popupHeight+"px", "display": "none"});
			$('#wp_popup').fadeIn(300);
			$('.close').click(function (){
				$('#wp_popup').fadeOut(200, function (){
					$('#popup').remove();
				});
			});			
		});
		return false;
   }); 	
})