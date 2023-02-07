<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
CModule::IncludeModule('iblock');
global $LangRu;
global $LangId;
__IncludeLang($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/ostkom/components/bitrix/catalog/home/bitrix/catalog.element/.default/lang/".$LangId."/template.php");
if(!$LangRu){
   $arResult['NAME']=$arResult['PROPERTIES']['NAME_LV']['VALUE'];
   $arResult['PROPERTIES']['Free_SIA_OSTKOM']['VALUE']=$arResult['PROPERTIES']['Free_SIA_OSTKOM_LV']['VALUE'];
   $arResult['PROPERTIES']['Free_Lattelecom']['VALUE']=$arResult['PROPERTIES']['Free_Lattelecom_LV']['VALUE'];
   $arResult['PROPERTIES']['Telephony_service']['VALUE']=$arResult['PROPERTIES']['Telephony_service_LV']['VALUE'];
   $arResult['PROPERTIES']['DESC_TARIIF']['~VALUE']['TEXT']=$arResult['PROPERTIES']['DESC_TARIIF_LV']['~VALUE']['TEXT'];
   $arResult['PROPERTIES']['TITLE_OSTKOM']['VALUE']=$arResult['PROPERTIES']['TITLE_OSTKOM_LV']['VALUE'];
   $arResult['PROPERTIES']['OSTKOM']['~VALUE']['TEXT']=$arResult['PROPERTIES']['OSTKOM_LV']['~VALUE']['TEXT'];
   $arResult['PROPERTIES']['TITLE_LATTELECOM']['VALUE']=$arResult['PROPERTIES']['TITLE_LATTELECOM_LV']['VALUE'];
   $arResult['PROPERTIES']['LATTELECOM']['~VALUE']['TEXT']=$arResult['PROPERTIES']['LATTELECOM_LV']['~VALUE']['TEXT'];
   $arResult['PROPERTIES']['TITLE_MOBIL']['VALUE']=$arResult['PROPERTIES']['TITLE_MOBIL_LV']['VALUE'];
   $arResult['PROPERTIES']['MOBILNET']['~VALUE']['TEXT']=$arResult['PROPERTIES']['MOBILNET_LV']['~VALUE']['TEXT'];
   $arResult['PROPERTIES']['TITLE_INFO']['VALUE']=$arResult['PROPERTIES']['TITLE_INFO_LV']['VALUE'];
   $arResult['PROPERTIES']['INFO_NUMBERS']['~VALUE']['TEXT']=$arResult['PROPERTIES']['INFO_NUMBERS_LV']['~VALUE']['TEXT'];
   $arResult['PROPERTIES']['TITLE_FREE']['VALUE']=$arResult['PROPERTIES']['TITLE_FREE_LV']['VALUE'];
   $arResult['PROPERTIES']['FREE_CALL']['~VALUE']['TEXT']=$arResult['PROPERTIES']['FREE_CALL_LV']['~VALUE']['TEXT'];
   $arResult['PROPERTIES']['TITLE_OTHER']['VALUE']=$arResult['PROPERTIES']['TITLE_OTHER_LV']['VALUE'];
   $arResult['PROPERTIES']['OTHER_NETWORKS']['~VALUE']['TEXT']=$arResult['PROPERTIES']['OTHER_NETWORKS_LV']['~VALUE']['TEXT'];
   $arResult['PROPERTIES']['TITLE_INTER']['VALUE']=$arResult['PROPERTIES']['TITLE_INTER_LV']['VALUE'];
   $arResult['PROPERTIES']['INTER_CALL']['~VALUE']['TEXT']=$arResult['PROPERTIES']['INTER_CALL_LV']['~VALUE']['TEXT'];
   $arResult['PROPERTIES']['Tel_Note']['~VALUE']['TEXT']=$arResult['PROPERTIES']['Tel_Note_LV']['~VALUE']['TEXT'];
}
$strMainID = $this->GetEditAreaId($arResult['ID']);
$arItemIDs = array(
	'ID' => $strMainID,
	'PICT' => $strMainID.'_pict',
	'DISCOUNT_PICT_ID' => $strMainID.'_dsc_pict',
	'STICKER_ID' => $strMainID.'_sticker',
	'BIG_SLIDER_ID' => $strMainID.'_big_slider',
	'BIG_IMG_CONT_ID' => $strMainID.'_bigimg_cont',
	'SLIDER_CONT_ID' => $strMainID.'_slider_cont',
	'SLIDER_LIST' => $strMainID.'_slider_list',
	'SLIDER_LEFT' => $strMainID.'_slider_left',
	'SLIDER_RIGHT' => $strMainID.'_slider_right',
	'OLD_PRICE' => $strMainID.'_old_price',
	'PRICE' => $strMainID.'_price',
	'DISCOUNT_PRICE' => $strMainID.'_price_discount',
	'SLIDER_CONT_OF_ID' => $strMainID.'_slider_cont_',
	'SLIDER_LIST_OF_ID' => $strMainID.'_slider_list_',
	'SLIDER_LEFT_OF_ID' => $strMainID.'_slider_left_',
	'SLIDER_RIGHT_OF_ID' => $strMainID.'_slider_right_',
	'QUANTITY' => $strMainID.'_quantity',
	'QUANTITY_DOWN' => $strMainID.'_quant_down',
	'QUANTITY_UP' => $strMainID.'_quant_up',
	'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
	'QUANTITY_LIMIT' => $strMainID.'_quant_limit',
	'BASIS_PRICE' => $strMainID.'_basis_price',
	'BUY_LINK' => $strMainID.'_buy_link',
	'ADD_BASKET_LINK' => $strMainID.'_add_basket_link',
	'BASKET_ACTIONS' => $strMainID.'_basket_actions',
	'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
	'COMPARE_LINK' => $strMainID.'_compare_link',
	'PROP' => $strMainID.'_prop_',
	'PROP_DIV' => $strMainID.'_skudiv',
	'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
	'OFFER_GROUP' => $strMainID.'_set_group_',
	'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
);

$strTitle = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"] != ''
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
	: $arResult['NAME']
);
$strAlt = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"] != ''
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	: $arResult['NAME']
);
//echo"<pre>"; print_r($arResult['PROPERTIES']);  echo"</pre>";
?>
    <div class="bx_item_detail" id="<? echo $arItemIDs['ID'];?>">
          <form class="hero-phone-tariff module" id="hero-phone-tariff" action="<?if($LangRu):?>/ru<?endif;?><?if($_SESSION['ServiceType']=="business"):?>/application/<?else:?><?if($arResult['address']['city']):?>/order/<?else:?>/verification/order/<?endif;?><?endif;?>" method="post" >
		    <input type="hidden" name="tariff" value="<?=$arResult['ID']?>">
			<input type="hidden" name="tariff_change" value="">
			<input type="hidden" name="section" value="<?=$arResult['IBLOCK_SECTION_ID']?>">
			<input type="hidden" name="stat_ip" value="">
            <input type="hidden" name="connect" value="">
			<input type="hidden" name="summ_1" value="<?=$arResult['PROPERTIES']['PRICE']['VALUE']?>">
            <input type="hidden" name="summ_2" value="">
			<?if($arResult['address']):?>
			     <input type="hidden" name="city" value="<?=$arResult['address']['city']?>">
				 <input type="hidden" name="street" value="<?=$arResult['address']['street']?>">
				 <input type="hidden" name="home" value="<?=$arResult['address']['home']?>">
			<?endif;?>
            <div class="hero-phone-tariff__inner ug-grid">
              <div class="hero-phone-tariff__image ug-col-phablet5 ug-col-desktop6">
			  <?if($arResult['DETAIL_PICTURE']['SRC']!=""):?>
			  <img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" alt="<?=$arResult["NAME"]?>">
			  <?endif;?>
			  </div>
              <div class="hero-phone-tariff__body ug-col-phablet7 ug-col-desktop6">
<?
if ('Y' == $arParams['DISPLAY_NAME'])
{
?>
                <h1 class="hero-phone-tariff__title h1">
<?
    echo (
		isset($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != ''
		? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
		: $arResult["NAME"]
	);
?>
                </h1>
<?
}
?>
                <!-- desc-->
                <div class="hero-phone-tariff__desc">
                  <table class="u-size-h5">
<?if($arResult['PROPERTIES']['Free_SIA_OSTKOM']['VALUE']!=""):?>
                    <tr>
                      <td><?=GetMessage('OSTKOM')?></td>
                      <td class="u-highlight"><?=$arResult['PROPERTIES']['Free_SIA_OSTKOM']['VALUE']?></td>
                    </tr>
<?endif;?>
<?if($arResult['PROPERTIES']['Free_Lattelecom']['VALUE']!=""):?>
                    <tr>
                      <td><?=GetMessage('Lattelecom')?></td>
                      <td class="u-highlight"><?=$arResult['PROPERTIES']['Free_Lattelecom']['VALUE']?></td>
                    </tr>
<?endif;?>
                  </table>
                  <hr class="hero-phone-tariff__sep">
<?
if(count($arResult['PROPERTIES']['CONNECT']['VALUE'])>0){
    $connects=array();
    $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), Array( "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y","IBLOCK_ID"=>21, "ID" => $arResult['PROPERTIES']['CONNECT']['VALUE']), false, false,array("NAME","PROPERTY_NAME_LV","ID","PROPERTY_TEXT","PROPERTY_TEXT_LV","PROPERTY_PRICE","PROPERTY_CONTRACT_PERIOD"));
    while($ob = $res->GetNextElement())
    {
       $arFields = $ob->GetFields();
	   $connects[]=$arFields;
    }
}
?>
                  <table class="u-size-h5 connect_bl">
<?foreach($connects as $k => $connect):?>
<?
if(!$LangRu){
    $connect['PROPERTY_TEXT_VALUE']=$connect['PROPERTY_TEXT_LV_VALUE'];
}
?>
                    <tr>
                      <td><?=$connect['PROPERTY_TEXT_VALUE']?></td>
                      <td class="u-highlight u-text-right u-nowrap">
                        <div class="price">
                          <div class="price__value"><?=$connect['PROPERTY_PRICE_VALUE']?></div>
                          <div class="price__suffix">€</div>
                        </div>
                        <div class="radio radio--alone">
                          <label>
                            <input type="radio" name="detail_radio_connect" id="<?=$connect['ID']?>" value="<?=$connect['PROPERTY_PRICE_VALUE']?>"<?if($k==0):?> checked<?endif;?>><i></i>
                          </label>
                        </div>
                      </td>
                    </tr>
<?endforeach;?>
                  </table>
<?if($arResult['PROPERTIES']['ADD_DEVICES_RENT']['VALUE'] OR $arResult['PROPERTIES']['ADD_DEVICES_SALE']['VALUE']):?>
<?
if($arResult['PROPERTIES']['ADD_DEVICES_RENT']['VALUE']){
	$ArrDivices=array();
    $res = CIBlockElement::GetList(Array('SORT' => 'asc'), array('IBLOCK_ID' => 18, "ACTIVE" => "Y", 'ID' => $arResult['PROPERTIES']['ADD_DEVICES_RENT']['VALUE'], '!PROPERTY_DISPLAY' => "39"), false, false, array("DETAIL_PICTURE","NAME","PROPERTY_NAME_LV","ID","PROPERTY_SELLING_PRICE","PROPERTY_RENT_PRICE","PROPERTY_OLD_SELLING_PRICE","PROPERTY_OLD_RENT_PRICE","PROPERTY_TYPE_PRICE","PROPERTY_TYPE_PRICE_RENT","PROPERTY_DISPLAY","IBLOCK_SECTION_ID"));
    while($arFields = $res->GetNextElement())
    {
		$dv = $arFields->GetFields();
//echo"<pre>";	print_r($dv);  echo"</pre>";
		$ArrDivices[$dv['IBLOCK_SECTION_ID']]['rent'][]=$dv;
    }
}
if($arResult['PROPERTIES']['ADD_DEVICES_SALE']['VALUE']){
    $res = CIBlockElement::GetList(Array('SORT' => 'asc'), array('IBLOCK_ID' => 18, "ACTIVE" => "Y", 'ID' => $arResult['PROPERTIES']['ADD_DEVICES_SALE']['VALUE'], '!PROPERTY_DISPLAY' => "39"), false, false, array("DETAIL_PICTURE","NAME","PROPERTY_NAME_LV","ID","PROPERTY_SELLING_PRICE","PROPERTY_RENT_PRICE","PROPERTY_OLD_SELLING_PRICE","PROPERTY_OLD_RENT_PRICE","PROPERTY_TYPE_PRICE","PROPERTY_TYPE_PRICE_RENT","PROPERTY_DISPLAY","IBLOCK_SECTION_ID"));
    while($arFields = $res->GetNextElement())
    {
		$dv = $arFields->GetFields();
		$ArrDivices[$dv['IBLOCK_SECTION_ID']]['sale'][]=$dv;
    }
}
?>
                  <hr class="hero-phone-tariff__sep">
				  <div id="devices_title"><b><?=GetMessage('ServiceTitleDev')?> </b></div>
					<table class="u-size-h5">
						 <tr>
						    <td style="text-align:right"></td>
				            <td class="std">
                               <div class="radio radio--alone radio--switcher calc__device-radio">
                                  <label>
                                     <a href="#" id="reset_b"><?=GetMessage('CT_RESER')?></a>
                                  </label>
                               </div>
				            </td>
						 </tr>
                    </table>
				  <div class="devices_block">
<?
foreach($ArrDivices as $k=>$diveces){
   $rsSect = CIBlockSection::GetList(array('SORT' => 'asc'),array('IBLOCK_ID' => 18, 'ID' => $k,"ACTIVE" => "Y"),false,array("NAME","UF_NAME_LV","ID"));
   $arSect = $rsSect->GetNext();
if(!$LangRu){
	$arSect['NAME']=$arSect['NAME_LV'];
}
?>
            <div class="device_sec" id="<?=$arSect['ID']?>">
			        <div class="div_oper"><?=$arSect['NAME']?></div>
			        <div class="calc__toggle">
			          <input type="hidden" name="oper_type[<?=$arSect['ID']?>]" value="rent">
					  <?if($diveces['rent']):?>
                      <label class="calc__toggle-oper">
                        <input type="radio" class="router_buy" name="router_buy[<?=$arSect['ID']?>]" value="rent" checked=""><span class="calc__toggle-text active"><?=GetMessage('Service_Rent')?></span>
                      </label>
					  <?endif;?>
					  <?if($diveces['sale']):?>
                      <label class="calc__toggle-oper">
                        <input type="radio" class="router_buy" name="router_buy[<?=$arSect['ID']?>]" value="sale" <?if(!$diveces['rent']):?>checked=""<?endif;?>><span class="calc__toggle-text"><?=GetMessage('Service_Sale')?></span>
                      </label>
					  <?endif;?>
                    </div>
					<?if($diveces['rent']):?>
					<table class="u-size-h5 device_table d_rent">
                        <?foreach($diveces['rent'] as $dev):?>
<?
if(!$LangRu){
	$dev['NAME']=$dev['PROPERTY_NAME_LV_VALUE'];
}
?>
						 <tr>
						    <td><?=$dev['NAME']?> <span class="price__value"><?=(($dev['PROPERTY_RENT_PRICE_VALUE']>0)? $dev['PROPERTY_RENT_PRICE_VALUE'] :0)?> €</span></td>
				            <td class="std">
					           <input type="hidden" class="price_dev" value="<?=(($dev['PROPERTY_RENT_PRICE_VALUE']>0)? $dev['PROPERTY_RENT_PRICE_VALUE'] :0)?>" id="rent_<?=$dev['ID']?>">
                               <div class="radio radio--alone radio--switcher calc__device-radio">
                                  <label>
                                     <input type="radio" class="device_item Orent" name="device[<?=$arSect['ID']?>]" value="<?=$dev['ID']?>"><i></i>
                                  </label>
                               </div>
				            </td>
						 </tr>
						 <?endforeach;?>
					</table>
                    <?endif;?>
					<?if($diveces['sale']):?>
					<table class="u-size-h5 device_table d_sale" <?if($diveces['rent']):?>style="display:none"<?endif;?>>
                         <?foreach($diveces['sale'] as $dev):?>
<?
if(!$LangRu){
	$dev['NAME']=$dev['PROPERTY_NAME_LV_VALUE'];
}
?>
						 <tr>
						    <td><?=$dev['NAME']?> <span class="price__value"><?=(($dev['PROPERTY_SELLING_PRICE_VALUE']>0)? $dev['PROPERTY_SELLING_PRICE_VALUE'] :0 )?>€</span></td>
				            <td class="std">
					           <input type="hidden" class="price_dev" value="<?=(($dev['PROPERTY_SELLING_PRICE_VALUE']>0)? $dev['PROPERTY_SELLING_PRICE_VALUE'] :0 )?>" id="sale_<?=$dev['ID']?>">
                               <div class="radio radio--alone radio--switcher calc__device-radio">
                                  <label>
                                     <input type="radio" class="device_item Osale" name="device[<?=$arSect['ID']?>]" value="<?=$dev['ID']?>"><i></i>
                                  </label>
                               </div>
				            </td>
						 </tr>
						 <?endforeach;?>
					</table>
                    <?endif;?>
			</div>
<?  } ?>
                  </div>
<?endif;?>
                <!-- action-->
                <div class="hero-phone-tariff__action">
                  <div class="price u-size-h1">
                    <div class="price__value" id="total_sum"><?=$arResult['PROPERTIES']['PRICE']['VALUE']?></div>
                    <div class="price__suffix"><?=GetMessage('UNIT_CUR')?></div>
                  </div>
				  <input type="submit" class="btn btn--primary" value="<?if($_SESSION['ServiceType']=="business"):?><?=GetMessage('ServiceType_B')?><?else:?><?=GetMessage('ServiceType_H')?><?endif;?>">
                  <div class="hero-phone-tariff__plus" id="phone-tariff-plus">
				  <span class="u-size-h4 u-highlight" id="total_sum_1">0</span>
				  <span class="u-highlight">€</span>&nbsp;&nbsp;&nbsp;<?=GetMessage('total_sum_1')?>
				  </div>
                </div>
              </div>
            </div>
          </form><br><br clear=all>
          <!-- tariff detail-->
          <div class="tariff-detail module">
			<?if($arResult['PROPERTIES']['Telephony_service']['VALUE']!=""):?>
            <div class="tariff-detail__item">
              <div class="tariff-detail__item-title h5"><?=$arResult['PROPERTIES']['Telephony_service']['VALUE']?></div>
              <?if($arResult['PROPERTIES']['DESC_TARIIF']['~VALUE']['TEXT']!=""):?>
              <div class="tariff-detail__item-table">
                 <?=$arResult['PROPERTIES']['DESC_TARIIF']['~VALUE']['TEXT']?>
              </div>
			  <?endif;?>
            </div>
			<?endif;?>
			<?if($arResult['PROPERTIES']['TITLE_OSTKOM']['VALUE']!=""):?>
            <div class="tariff-detail__item">
              <div class="tariff-detail__item-title h5"><?=$arResult['PROPERTIES']['TITLE_OSTKOM']['VALUE']?></div>
			  <?if($arResult['PROPERTIES']['OSTKOM']['~VALUE']['TEXT']!=""):?>
              <div class="tariff-detail__item-table">
                <?=$arResult['PROPERTIES']['OSTKOM']['~VALUE']['TEXT']?>
              </div>
			  <?endif;?>
            </div>
			<?endif;?>
			<?if($arResult['PROPERTIES']['TITLE_LATTELECOM']['VALUE']!=""):?>
            <div class="tariff-detail__item">
              <div class="tariff-detail__item-title h5"><?=$arResult['PROPERTIES']['TITLE_LATTELECOM']['VALUE']?></div>
			  <?if($arResult['PROPERTIES']['LATTELECOM']['~VALUE']['TEXT']!=""):?>
              <div class="tariff-detail__item-table">
              <?=$arResult['PROPERTIES']['LATTELECOM']['~VALUE']['TEXT']?>
              </div>
			  <?endif;?>
            </div>
            <?endif;?>
			<?if($arResult['PROPERTIES']['TITLE_MOBIL']['VALUE']!=""):?>
            <div class="tariff-detail__item">
              <div class="tariff-detail__item-title h5"><?=$arResult['PROPERTIES']['TITLE_MOBIL']['VALUE']?></div>
			  <?if($arResult['PROPERTIES']['MOBILNET']['~VALUE']['TEXT']!=""):?>
              <div class="tariff-detail__item-table">
                 <?=$arResult['PROPERTIES']['MOBILNET']['~VALUE']['TEXT']?>
              </div>
			  <?endif;?>
            </div>
			<?endif;?>
			<?if($arResult['PROPERTIES']['TITLE_INFO']['VALUE']!=""):?>
            <div class="tariff-detail__item">
              <div class="tariff-detail__item-title h5"><?=$arResult['PROPERTIES']['TITLE_INFO']['VALUE']?></div>
			  <?if($arResult['PROPERTIES']['INFO_NUMBERS']['~VALUE']['TEXT']!=""):?>
              <div class="tariff-detail__item-table">
                 <?=$arResult['PROPERTIES']['INFO_NUMBERS']['~VALUE']['TEXT']?>
              </div>
			  <?endif;?>
            </div>
			<?endif;?>
			<?if($arResult['PROPERTIES']['TITLE_FREE']['VALUE']!=""):?>
            <div class="tariff-detail__item">
              <div class="tariff-detail__item-title h5"><?=$arResult['PROPERTIES']['TITLE_FREE']['VALUE']?></div>
			  <?if($arResult['PROPERTIES']['FREE_CALL']['~VALUE']['TEXT']!=""):?>
              <div class="tariff-detail__item-table">
                 <?=$arResult['PROPERTIES']['FREE_CALL']['~VALUE']['TEXT']?>
              </div>
			  <?endif;?>
            </div>
			<?endif;?>
			<?if($arResult['PROPERTIES']['TITLE_OTHER']['VALUE']!=""):?>
            <div class="tariff-detail__item">
              <div class="tariff-detail__item-title h5"><?=$arResult['PROPERTIES']['TITLE_OTHER']['VALUE']?></div>
			  <?if($arResult['PROPERTIES']['OTHER_NETWORKS']['~VALUE']['TEXT']!=""):?>
              <div class="tariff-detail__item-table">
                <?=$arResult['PROPERTIES']['OTHER_NETWORKS']['~VALUE']['TEXT']?>
              </div>
			  <?endif;?>
            </div>
			<?endif;?>
			<?if($arResult['PROPERTIES']['TITLE_INTER']['VALUE']!=""):?>
            <div class="tariff-detail__item">
              <div class="tariff-detail__item-title h5"><?=$arResult['PROPERTIES']['TITLE_INTER']['VALUE']?></div>
			  <?if($arResult['PROPERTIES']['INTER_CALL']['~VALUE']['TEXT']!=""):?>
              <div class="tariff-detail__item-table">
                <?=$arResult['PROPERTIES']['INTER_CALL']['~VALUE']['TEXT']?>
              </div>
			  <?endif;?>
            </div>
			<?endif;?>
			<?if($arResult['PROPERTIES']['Tel_Note']['~VALUE']['TEXT']!=""):?>
            <div class="tariff-detail__footnote">
              <p><?=$arResult['PROPERTIES']['Tel_Note']['~VALUE']['TEXT']?></p>
            </div>
			<?endif;?>
          </div>
    </div>
    <script>
	tariff=<?=$arResult['PROPERTIES']['PRICE']['VALUE']?>;
	var connect=0;
    var device= {};
	var router_buy= {};
    $(document).ready(function() {
	    function init_devices(id){
		    if(id>0){
		    }
	        else{
                $(".device_item").each(function(index) {
                   device[$(this).val()]={"active":0,"oper":"rent","price":0};
                });
                $(".router_buy").each(function(index) {
				   if($(this).prop('checked'))
                   router_buy[$(this).closest(".device_sec").attr("id")]=$(this).val();
                });
	        }
            TotalSum();
	    }
        $('.device_item__').on('click', function() {
			if(device[$(this).val()]['active']==1){
				device[id]['active']=0;
				device[id]['price']=0;
				$(this).prop("checked",false);
				TotalSum();
			}
        })
        $('#reset_b').on('click', function(e) {
             $(".device_item").each(function(index) {
				   $(this).prop('checked',false);
             });
			 init_devices(0);
			 return false;
		})
        $('.device_item').on('change', function(e) {
			id=$(this).val();
			$(".device_item").each(function(index) {
                device[$(this).val()]={"active":0,"oper":"rent","price":0};
            });
		    if($(this).is(':checked')){
		        device[id]['active']=1;
//alert(router_buy[$(this).closest(".device_sec").attr("id")]);
				device[id]['oper']=router_buy[$(this).closest(".device_sec").attr("id")];
				device[id]['price']= $(this).closest(".std").find("input.price_dev").val();
            }else{
                device[id]['active']=0;
				device[id]['price']=0;
		    }
            TotalSum();
        });
	    $('input.router_buy').on('change', function() {
			$(this).closest(".device_sec").find(".device_table").css("display","none");
			$(this).closest(".device_sec").find(".d_"+$(this).val()).css("display","block");
			//alert($(this).val());
			router_buy[$(this).closest(".device_sec").attr("id")]=$(this).val();
        });
        $('#hero-phone-tariff .connect_bl').find(':radio').on('change', function(e) {
            var $this = $(this);
            $('input[name="connect"]').val($(this).attr('id'));
            connect=$(this).val();
            TotalSum();
        }).filter(':checked');
		init_devices(0);
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
		total_sum=parseFloat(tariff)+parseFloat(device_sum);
		total_sum=total_sum.toFixed(2);

		total_sum_1=parseFloat(device_sum_1)+parseFloat(connect);
		total_sum_1=total_sum_1.toFixed(2);

		if(total_sum<0) total_sum=0;
		if(total_sum_1<0) total_sum_1=0;

		$('#total_sum_1').html(total_sum_1);
		$('#total_sum').html(total_sum);
		if(total_sum_1>0){
			$('.hero-phone-tariff__plus').css("display","block");
		}
		$('input[name="summ_1"]').val(total_sum);
		$('input[name="summ_2"]').val(total_sum_1);
	}
    </script>