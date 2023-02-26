$(document).ready(function() {
	var tariff=0,
        stat_ip=0,
        connect=0,
        connect_group='',
        connect_period='',
        package=0,
        device= {};

	function init_devices(id){
		if(id>0){
           $(".block_device").each(function(index) {
	            if($(this).hasClass("rent_"+id)){
					device[$(this).attr('id')]['oper']="rent";
					$(this).find('input[value="rent"]').prop("checked",true);
					$(this).find(".item_rent_"+id).css("display","block");
                }
                else{
	               if($(this).hasClass("sale_"+id)){
					   device[$(this).attr('id')]['oper']="sale";
					   $(this).find('input[value="sale"]').prop("checked",true);
					   $(this).find(".item_sale_"+id).css("display","block");
                   }
				}
				$(this).find('input.devices_val').prop("checked",true);
           });
           if($("#devices div").hasClass("sale_"+id) || $("#devices").hasClass("rent_"+id)){
		      $("#devices,.sale_"+id+",.rent_"+id+",.rent"+id+",.sale"+id).css("display","block");
		   }
           $("div.calc__item-header").find(".js-toggle").removeClass("is-active")
	    }
	    else{
          $(".block_device").each(function(index) {
		      $(this).css("display", "none");
		      device[$(this).attr('id')]={"active":1,"oper":"rent","price":0,"device": ""};
		      $(this).find('.calc__item-header .price__value').html("0");
		      $(this).find(".calc__item-desc").find('input:radio:checked').prop('checked', false);
		    //  $(this).find(".devices_val").prop('checked', false);
		      $(this).find(".calc__item-desc").css("display","none");
          });
	      $('#devices,.block_device,.calc__toggle-item,.calc__device').css("display","none");
	    }
       TotalSum();
	}
	function init_ip(id){
		if(id>0){
			if(arr_ip[id]){
			   $('.stat_ip input[name="ip"]').val(arr_ip[id]['sum']);
			   $('.stat_ip .price__value').html(arr_ip[id]['sum']);
			   $('.stat_ip .static-ip-desc p').html(arr_ip[id]['text']);
			   $(".ip_"+id).css("display","block");
			}
		}
		else{
            stat_ip=0;
		    $('input[name="stat_ip"]').val(0);
			$('.stat_ip input[name="ip"]').val(0);
			$('.stat_ip .price__value').html(0);
			$('.stat_ip .static-ip-desc p').html("");
            $(".stat_ip,.js-toggle-ip").css("display","none");
            $('input[name="ip"]').prop('checked', false);
		}
		TotalSum();
	}
	function init_connect(id, group, period){
		if(id>0){
            $(".connect_"+id).each(function(index){
                let radioConnect = $(this).find('input[name="detail_radio_connect"]');
                radioConnect.data('group', group);

                if(index == 0){
				    radioConnect.prop('checked', true);
                    connect=radioConnect.val();
                    connect_group=group;
                    connect_period=radioConnect.data('period');

                    $('input[name="connect"]').val(radioConnect.val());
				}
			});
            $("#connect_block,.connect_"+id+",.connect_title_"+id).css("display","block");
		}
		else{
            connect=0;
            connect_group='';
            connect_period='';

			$("#connect_block").find('input:radio:checked').prop('checked', false);
            $('input[name="connect"]').val(0);
 			$("#connect_block,.connect_item,.connects_title_bl").css("display","none");
		}
		TotalSum();
	}
	function init_gift(id){
		if(id>0){
            $(".gift_"+id).css("display","block");
		}
		else{
 			$(".gift_block").css("display","none");
		}
		TotalSum();
	}
	init_devices(0);
    $('input[name="tariff_radio"]').on('click', function() {
        let id = $(this).attr('id'),
            group = $(this).data('group'),
            period = $(this).data('period'),
            price = $(this).val();

		//alert($('input[name="tariff_change"]').val());
		//alert($('input[name="tariff"]').val());
        if($('input[name="tariff"]').val() == id
                && $('input[name="tariff_change"]').val() == id){
			$('input[name="tariff"]').val("");
			$('input.tariff_package').prop("disabled",true).prop("checked",false);
			$('input.check_package').prop("disabled",true).prop("checked",false);
			$(this).prop('checked', false);

			tariff=0;
            init_devices(0);
            init_ip(0);
            init_connect(0, group, period);
            init_gift(0);
		    TotalSum();
		}
        $('input[name="tariff_change"]').val(id);
    });
	$('input[name="tariff_radio"]').on('change', function() {
        let id = $(this).attr("id"),
            group = $(this).data('group'),
            period = $(this).data('period'),
            price = $(this).val();

        tariff=0;
		init_devices(0);
        init_ip(0);
        init_connect(0, group, period);
        init_gift(0);
		TotalSum();

		$('input[name="tariff"]').val(id);
		if($('input.tariff_package'))
		     $('input.tariff_package').prop("disabled",true).prop("checked",false);
		if($('input.check_package'))
		    $('input.check_package').prop("disabled",true).prop("checked",false);
	    if($('input[date="'+id+'"]'))
	       $('input[date="'+id+'"]').prop("disabled",false);
	    if($('input[date="package_'+id+'"]'))
	       $('input[date="package_'+id+'"]').prop("disabled",false);

        tariff=parseFloat(price);
		init_ip(id);
		init_connect(id, group, period);
		init_gift(id);
		init_devices(id);
		TotalSum();
    });
	$('input.check_package').on('change', function() {
		var cur_tariff=$(this).closest("div.calc__item").find('input[name="tariff_radio"]');
		var id=$(this).closest("div.calc__item").find('input[name="tariff_radio"]').attr('id');
		if ($(this).is(':checked')) {
           //$('input[name="tariff"]').val(0);
		   //$('input[name="tariff_change"]').val(0);
		   tariff=0;
        }else{
		  // $('input[name="tariff"]').val(id);
		  // $('input[name="tariff_change"]').val(id);
		   tariff=parseFloat(cur_tariff.val());
        }
        TotalSum();
    });
	$('input.tariff_package').on('change', function() {
		if ($(this).is(':checked')) {
		   package+=parseFloat($(this).val());
		   $('input[name="tariff_id"]').val($(this).attr("date"));
        }
		else{
			package-=parseFloat($(this).val());
		}
		TotalSum();
    });
	$('input.devices_val').on('change', function() {
		var id=$(this).closest("div.calc__item").attr('id');
		if ($(this).is(':checked')) {
		   device[id]['active']=1;
		   $(this).closest("div.calc__item-header").next(".calc__item-desc").css("display","block");
           $(this).closest("div.calc__item-header").find(".js-toggle").addClass("is-active");
        }else{
           device[id]['active']=0;
		   device[id]['price']=0;
		   $(this).closest("div.calc__item-header").find('.price__value').html("0");
		   $(this).closest("div.calc__item-header").next(".calc__item-desc").find('input:radio:checked').prop('checked', false);
		   $(this).closest("div.calc__item-header").next(".calc__item-desc").css("display","none");
           $(this).closest("div.calc__item-header").find(".js-toggle").removeClass("is-active");
        }
        TotalSum();
    });
	$('input.router_buy').on('change', function() {
		var id=$(this).closest("div.calc__item").attr('id');
		var id_d=$('input[name="tariff_radio"]:checked').attr('id');
		var op=$(this).closest("div.calc__item-header").next(".calc__item-desc").find('input:radio:checked').val();
		if($(this).val()=="rent"){
				$(this).closest("div.calc__item-header").find('#SUM_rent').removeClass("hidden");
				$(this).closest("div.calc__item-header").find('#SUM_sale').addClass("hidden");
				$(this).closest(".block_device").find(".item_rent_"+id_d).css("display","block");
		}
		if($(this).val()=="sale"){
				$(this).closest("div.calc__item-header").find('#SUM_sale').removeClass("hidden");
				$(this).closest("div.calc__item-header").find('#SUM_rent').addClass("hidden");
                $(this).closest(".block_device").find(".item_sale_"+id_d).css("display","block");
		}
		$(this).parent().siblings('input[type="hidden"]').val($(this).val());
		device[id]['oper']=$(this).val();
	    if(op){
			if($(this).val()=="rent"){
                if($('input[value='+op+']').closest('.calc__device').hasClass('item_rent_'+$('input[name="tariff"]').val())){
				   device[id]['price']=$('#rent_'+op).val();
				}
                else{
				  device[id]['price']=0;
				  $('input[value='+op+']').prop('checked', false);
				}
			}
			if($(this).val()=="sale"){
			    if($('input[value='+op+']').closest('.calc__device').hasClass('item_sale_'+$('input[name="tariff"]').val())){
				   device[id]['price']=$('#sale_'+op).val();
				}
                else{
				  device[id]['price']=0;
				  $('input[value='+op+']').prop('checked', false);
				}
			}
			$(this).closest("div.calc__item-header").find('.price__value').html(device[id]['price']);
	        TotalSum();
		}
    });
	$('.calc__device-radio input[type="radio"]').on('change', function() {
		var id=$(this).closest("div.calc__item").attr('id');
		if(device[id].oper=="sale"){
			device[id]['price']=$('#sale_'+$(this).val()).val();
		}
		else{
		   if(device[id].oper=="rent"){
			   device[id]['price']=$('#rent_'+$(this).val()).val();
		   }
		}
		if($(this).closest(".block_device").find('input.devices_val').prop('checked'))
		    $(this).closest(".block_device").find('.price__value').html(device[id]['price']);
		//$('input[name="dev_ce"]').val($(this).val());
		device[id]['device']=$(this).val();
        TotalSum();
	})
	$('.calc__device-radio input[type="radio"]').on('click', function() {
		var id=$(this).closest("div.calc__item").attr('id');
        if(device[id]['device']==$(this).val() && $('input[name="dev_change"]').val()==$(this).val())	{
			device[id]['device']="";
			device[id]['price']=0;
			$(this).prop('checked', false);
		    if($(this).closest(".block_device").find('input.devices_val').prop('checked'))
		         $(this).closest(".block_device").find('.price__value').html("");
		}
        $('input[name="dev_change"]').val($(this).val());
		TotalSum();
    });
	$('input[name="ip"]').on('change', function() {
		if ($(this).is(':checked')) {
		   stat_ip=$(this).val();
        }else{
           stat_ip=0;
        }
		$('input[name="stat_ip"]').val(stat_ip);
		TotalSum();
    });
	$('input[name="detail_radio_connect"]').on('change', function() {
		if ($(this).is(':checked')) {
		   connect=$(this).val();
           connect_group=$(this).data('group');
           connect_period=$(this).data('period');
        }else{
           connect=0;
           connect_group='';
           connect_period='';
        }
        $('input[name="connect"]').val($(this).attr('id'));
		TotalSum();
    });
    $('#devices a.calc__item-arrow').on('click', function() {
		$(this).closest("div.calc__item-header").find('input.devices_val').prop("checked",true);
    });
	function TotalSum(){
		var total_sum=0;
		var total_sum_1=0;
		var device_sum=0;
		var device_sum_1=0;
        for( var id in device) {
			if(device[id].active==1){
			   if(device[id].oper=="rent"){
                  device_sum+=parseFloat(device[id].price);
			   }
			   if(device[id].oper=="sale"){
                  device_sum_1+=parseFloat(device[id].price);
			   }
			}
        }
		package=0;
	   	$("input.tariff_package").each(function(el){
			if($(this).is(':checked')){
				 package=package+parseFloat($(this).val());
			}
		});

        if (jsv_tariffs
                && jsv_tariffs['GROUPS']
                && connect_group
                && jsv_tariffs['GROUPS'][connect_group]
                && connect_period
                && jsv_tariffs['GROUPS'][connect_group][connect_period]) {

            let tariff_id = jsv_tariffs['GROUPS'][connect_group][connect_period];
            if (tariff_id && jsv_tariffs['LIST'][tariff_id]) {
                tariff = parseFloat(jsv_tariffs['LIST'][tariff_id]['PRICE']);
            }
        }

		total_sum = parseFloat(tariff)
                + parseFloat(device_sum)
                + parseFloat(stat_ip)
                + parseFloat(package);

		total_sum=total_sum.toFixed(2);
		total_sum_1=parseFloat(device_sum_1)+parseFloat(connect);
		total_sum_1=total_sum_1.toFixed(2);
		if(total_sum<0) total_sum=0;
		if(total_sum_1<0) total_sum_1=0;

		$('#total_sum').html(total_sum);
		$('input[name="summ_1"]').val(total_sum);
		$('#total_sum_1').html(total_sum_1);
		$('input[name="summ_2"]').val(total_sum_1);
		if(package <=0) {
			$('input[name="tariff_id"]').val("");
		}
	}
});