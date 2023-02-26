<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
/**
 * Bitrix vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @global CMain $APPLICATION
 * @global CUser $USER
 */
 global $str_name;
 global $LangRu;
 global $LangId;
__IncludeLang($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/ostkom/components/ost/main.feedback/order/lang/".$LangId."/template.php");
if($_POST){
	$_SESSION['ORDER']=$_POST;
}
else{
	$_POST=$_SESSION['ORDER'];
}
if($_POST){
	if(!CModule::IncludeModule('iblock')) return;
    if($_POST['section']>0){ 		
        $res = CIBlockSection::GetList(array(),array("ACTIVE" => "Y","GLOBAL_ACTIVE" => "Y","IBLOCK_ID" => 7, "ID" => $_POST['section']),false,array("ID","NAME","UF_NAME_LV","IBLOCK_ID"));
        $section = $res->GetNext();
		if(!$LangRu){
            $section["NAME"]=$section["UF_NAME_LV"];	
		}		
    }
	if($_POST['tariff']>0 OR $_POST['tariff_change']>0){ 
       $id_tarif=($_POST['tariff']>0)?	$_POST['tariff'] : $_POST['tariff_change'];
       $res = CIBlockElement::GetList(Array(), Array("ID"=>$id_tarif, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y"), false, false, Array("ID","NAME","PROPERTY_NAME_LV","PROPERTY_OLD_PRICE","PROPERTY_PRICE","PROPERTY_CURRENCY","IBLOCK_SECTION_ID"));
       $ob = $res->GetNextElement();
       $tariff = $ob->GetFields();
	   if(!$LangRu){
		   $tariff["NAME"]=$tariff["PROPERTY_NAME_LV_VALUE"];
		   $tariff['PROPERTY_CURRENCY_VALUE']=GetMessage("CURRENCY");
	   }
        $res = CIBlockSection::GetList(array(),array("ACTIVE" => "Y","GLOBAL_ACTIVE" => "Y","IBLOCK_ID" => 7, "ID" => $tariff['IBLOCK_SECTION_ID']),false,array("ID","NAME","UF_NAME_LV","IBLOCK_ID"));
        $section_2 = $res->GetNext();
		if(!$LangRu){
            $section_2["NAME"]=$section["UF_NAME_LV"];	
		}
		$section["NAME"]=$section["NAME"]." (".$section_2["NAME"].")";		
	}
	if($_POST['device']){
	   $ArDevices=array();
       $res = CIBlockElement::GetList(Array('SORT' => 'asc'), array('IBLOCK_ID' => 18, "ACTIVE" => "Y", 'ID' => $_POST['device']), false, false, array("NAME","PROPERTY_NAME_LV","ID","PROPERTY_SELLING_PRICE","PROPERTY_RENT_PRICE","IBLOCK_SECTION_ID","PROPERTY_TYPE_PRICE","PROPERTY_TYPE_PRICE_RENT"));
       while($ob = $res->GetNextElement())
       { 
            $d= $ob->GetFields();		
	        if(!$LangRu){
		       $d["NAME"]=$d["PROPERTY_NAME_LV_VALUE"];
	        }			
            if($_POST['router_buy'][$d['IBLOCK_SECTION_ID']]=="rent"){ 
           		$d['price']=$d['PROPERTY_RENT_PRICE_VALUE'];
				$d['price_type']=GetMessage("RENT_N");
				$d['price_cur']=GetMessage("CURRENCY");
            }				
			elseif($_POST['router_buy'][$d['IBLOCK_SECTION_ID']]=="sale"){
			    $d['price']=$d['PROPERTY_SELLING_PRICE_VALUE'];	
				$d['price_type']=GetMessage("SALE_N");
				$d['price_cur']=GetMessage("CURRENCY_SALLE");
			}
		    $d['PROPERTY_CURRENCY_VALUE']=GetMessage("CURRENCY");
			$d['PROPERTY_TYPE_PRICE_VALUE']=$d['~PROPERTY_TYPE_PRICE_VALUE']=GetMessage("CURRENCY_SALLE");
			$d['PROPERTY_TYPE_PRICE_RENT_VALUE']=$d['~PROPERTY_TYPE_PRICE_RENT_VALUE']=GetMessage("CURRENCY");  			
            $ArDevices[] = $d;
            $StrDevice=""; 			
       }  
	} 
    if($_POST['connect']>0){
       $res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID" => 21, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y","ID" => $_POST['connect']), false, false,array("NAME","PROPERTY_NAME_LV","ID","PROPERTY_TEXT","PROPERTY_TEXT_LV","PROPERTY_PRICE"));
       $ob = $res->GetNextElement();
       $connect = $ob->GetFields();	
	   if(!$LangRu){
		   $connect["NAME"]=$connect["PROPERTY_NAME_LV_VALUE"];
		   $connect["PROPERTY_TEXT_VALUE"]=$connect["PROPERTY_TEXT_LV_VALUE"];		
		   $connect["~PROPERTY_TEXT_VALUE"]=$connect["PROPERTY_TEXT_LV_VALUE"];			   
	   }	   	   
    }
    if($_POST['tariff_package']){
		$ar_p=array();
       foreach($_POST['tariff_package'] as $pack){
		  $ar_p=array_merge($ar_p,array_keys($pack));
	   }	   
       $res = CIBlockElement::GetList(Array(), Array("IBLOCK_ID" => 24,"ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y","ID" => $ar_p), false, false,array("NAME","PROPERTY_NAME_LV","ID","PROPERTY_PRICE"));
       while($ob = $res->GetNextElement()){
          $pack_a = $ob->GetFields();
		  if(!$LangRu){
		     $pack_a["NAME"]=$pack_a["PROPERTY_NAME_LV_VALUE"];	   
	     }
		  $Arr_Pack[]=$pack_a; 		  
	   } 
	}	
	$tarif_id=($_POST['tariff']>0)? $_POST['tariff'] : $_POST['tariff_id'];
}
?>


<?if($arResult["OK_MESSAGE"] OR $_GET['success']):?>
          <div class="order-details module">
            <div class="order-details__title h3"><?=GetMessage("MFT_OK_MESSAGE")?></div>
		  </div>	
<?elseif($_POST):?>
	<?if($arResult["ERROR_MESSAGE"]):?>
		<?foreach($arResult["ERROR_MESSAGE"] as $err):?>
		  <span style="color:red"><?=$err?> </span><br>
		<?endforeach;?>
	<?endif;?>	
          <div class="order-details module">
            <!-- title-->
            <div class="order-details__title h3"><?=GetMessage("MFT_ORDER")?></div>
			<div><?=$section['NAME']?></div>
            <!-- body-->
            <div class="order-details__body ug-grid">
              <div class="ug-col ug-col-tablet6">
                <!-- table-->
                <table class="order-details__table">
			    <?if($_POST['tariff']>0 AND !$_POST['check_package'][$_POST['tariff']]):?>
                  <tr>
                    <td><?=$tariff['NAME']?></td>
                    <td class="u-text-right">
                      <div class="price">				  
                        <div class="price__value"><?=$tariff['PROPERTY_PRICE_VALUE']?></div>
                        <div class="price__suffix"><?=$tariff['PROPERTY_CURRENCY_VALUE']?></div>
                      </div>
                    </td>
                  </tr>
				<?endif;?>  
				<?if($_POST['tariff_package']):?>
				<?foreach($Arr_Pack as $pack):?>
                  <tr>
                    <td>
					  <div><?=GetMessage("package")?></div>
					"<?=$pack['NAME']?>"
					</td>
                    <td class="u-text-right">
                      <div class="price">
                        <div class="price__value"><?=$pack['PROPERTY_PRICE_VALUE']?></div>
                        <div class="price__suffix"><?=$tariff['PROPERTY_CURRENCY_VALUE']?></div>
                      </div>
                    </td>
                  </tr>	
                <?endforeach;?>				  
				<?endif;?>
  
			<?foreach($ArDevices as $device):?>	  	 			
                  <tr>
                    <td><?=$device['NAME']?> (<?=$device['price_type']?>)</td>
                    <td class="u-text-right">
                      <div class="price">
                        <div class="price__value"><?=$device['price']?></div>
                        <div class="price__suffix"><?=$device['price_cur']?></div>
                      </div>
                    </td>
                  </tr>
			      <?$StrDevice.=str_replace('"', '', $device['NAME'])." (".$device['price_type'].") - ".$device['price'].$device['price_cur']."<br>";?>				  
			<?endforeach;?>
			<?if($StrDevice=="") $StrDevice= GetMessage("NO");?>
            <?if($_POST['ip']>0):?>			
                  <tr>
                    <td><?=GetMessage("IP")?></td>
                    <td class="u-text-right">
                      <div class="price">
                        <div class="price__value"><?=$_POST['ip']?></div>
                        <div class="price__suffix"><?=GetMessage("CURRENCY")?></div>
                      </div>
                    </td>
                  </tr>
			<?endif;?>
			<?if($_POST['connect']>0):?>		
                  <tr>
                    <td>
					<div><?=GetMessage("connect")?></div>
					<?=$connect['PROPERTY_TEXT_VALUE']?>
					</td>
                    <td class="u-text-right">
                      <div class="price">
                        <div class="price__value"><?=$connect['PROPERTY_PRICE_VALUE']?></div>
                        <div class="price__suffix"><?=GetMessage("CURRENCY_SALLE")?></div>
                      </div>
                    </td>
                  </tr>
			<?endif;?>	  
                </table>
              </div>
              <div class="ug-col ug-col-tablet6 ug-col-desktop5">
                <!-- total-->
                <div class="order-details__total">
<?
$_POST['summ_1']=($_POST['summ_1']>0)? $_POST['summ_1'] : 0;
$_POST['summ_2']=($_POST['summ_2']>0)? $_POST['summ_2'] : 0;
?>			
                  <div class="price order-details__total-sum">
                    <div class="price__value"><?=$_POST['summ_1']?></div>
                    <div class="price__suffix"><?=GetMessage("CURRENCY")?></div>
                  </div>
                  <div class="order-details__total-plus">+<?=$_POST['summ_2']?> <?=GetMessage("CURRENCY_SUM2")?></div>
                </div>
              </div>
            </div>
          </div>
          <!-- order-->
		  <form action="<?=$APPLICATION->GetCurPage()?>" method="POST" id="order_form">
		  <?=bitrix_sessid_post()?>		  
		  <div class="order form module">
		  	<input type="hidden" name="section_name" value="<?=$section['NAME']?>"> <!-- -->
			<input type="hidden" name="section" value="<?=$_POST['section']?>">	
		    <input type="hidden" name="tariff_name" value="<?=$tariff['NAME']?>">   <!-- -->
			<?if($_POST['tariff']>0 AND !$_POST['check_package'][$_POST['tariff']]):?>	
			<input type="hidden" name="tariff" value="<?=$_POST['tariff']?>">			
			<input type="hidden" name="tariff_id" value="<?=$tariff['ID']?>">				
			<input type="hidden" name="tariff_price" value="<?=$tariff['PROPERTY_PRICE_VALUE']?>">
			<?endif;?>				
			<input type="hidden" name="devices" value="<?=$StrDevice?>">   <!-- -->
			<?foreach($_POST['device'] as $k => $dv):?>
			 <input type="hidden" name="device[<?=$k?>]" value="<?=$dv?>">  
			<?endforeach;?>			 
			<input type="hidden" name="dev_change" value="<?=$_POST['dev_change']?>">			
			<input type="hidden" name="tariff_radio" value="<?=$_POST['tariff_radio']?>">			
		    <input type="hidden" name="ip" value="<?=$_POST['ip']?>">
			<input type="hidden" name="stat_ip" value="<?=$_POST['ip']?>">
			<input type="hidden" name="tariff_packages" value="<?=$pack['PROPERTY_PRICE_VALUE']?>">    <!-- -->
			<?foreach($_POST['tariff_package'] as $key => $dvs):?>
				<?foreach($dvs as $k => $dv):?>
			<input type="hidden" name="tariff_package[<?=$key?>][<?=$k?>]" value="<?=$dv?>">
				<?endforeach;?>	
			<?endforeach;?>				
			<input type="hidden" name="name_packages" value="<?=$pack['NAME']?>">	<!-- -->	
			<?foreach($_POST['oper_type'] as $k => $dv):?>
			<input type="hidden" name="oper_type[<?=$k?>]" value="<?=$dv?>">	
			<?endforeach;?>		
			<?foreach($_POST['router_buy'] as $k => $dv):?>
			<input type="hidden" name="router_buy[<?=$k?>]" value="<?=$dv?>">	
			<?endforeach;?>		
			<?foreach($_POST['router_price'] as $k => $dv):?>	
		    <input type="hidden" name="router_price[<?=$k?>]" value="<?=$dv?>">
			<?endforeach;?>													
			<input type="hidden" name="connect_name" value="<?=$connect['PROPERTY_TEXT_VALUE']?>">	
			<input type="hidden" name="connect_price" value="<?=$connect['PROPERTY_PRICE_VALUE']?>">	
			<input type="hidden" name="connect" value="<?=$_POST['connect']?>">
            <input type="hidden" name="summ_1" value="<?=$_POST['summ_1']?>">	
            <input type="hidden" name="summ_2" value="<?=$_POST['summ_2']?>">	
            <input type="hidden" name="tariff_change" value="<?=$_POST['tariff_change']?>">	

			
            <!-- body-->
            <div class="order__body section section--fullwidth section--compact">
              <!-- personal data-->
              <div class="form__section">
                <div class="ug-grid">
                  <div class="ug-col ug-col-phablet6">
                    <div class="h3"><?=GetMessage("FORM_PERSONAL")?></div>
                    <!-- name-->
                    <div class="form__row">
                      <input class="input" type="text" name="user_name"  value="<?=$arResult["AUTHOR_NAME"]?>" placeholder="<?=GetMessage("MFT_NAME")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("NAME", $arParams["REQUIRED_FIELDS"])):?>*<?endif?>" required>
                    </div>
                    <!-- lastname-->
                    <div class="form__row">
                      <input class="input" type="text" value="<?=$_POST['COMPANY']?>" name="COMPANY" placeholder="<?=GetMessage("FORM_SNAME")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("COMPANY", $arParams["REQUIRED_FIELDS"])):?>*<?endif?>" required>
                    </div>
                    <!-- code-->
                    <div class="form__row">
                      <input class="input" type="text" name="ABR" value="<?=$_POST['ABR']?>" placeholder="<?=GetMessage("FORM_CODE")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("ABR", $arParams["REQUIRED_FIELDS"])):?>*<?endif?>" required>
                    </div>
                    <!-- phone-->
                    <div class="form__row">				
                      <input class="input" type="tel" name="user_tel" value="<?=$arResult["AUTHOR_TEL"]?>" placeholder="<?=GetMessage("MFT_TEL")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("TEL", $arParams["REQUIRED_FIELDS"])):?>*<?endif?>" required>
                    </div>
                    <!-- email-->
                    <div class="form__row">				
                      <input class="input" type="email" name="user_email" value="<?=$arResult["AUTHOR_EMAIL"]?>" placeholder="<?=GetMessage("MFT_EMAIL")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("EMAIL", $arParams["REQUIRED_FIELDS"])):?>*<?endif?>" required>
                    </div>
                  </div>
                  <div class="ug-col ug-col-phablet6">
                    <div class="h3"><?=GetMessage("FORM_ADDRESS")?></div>
                    <!-- city-->
<?
CModule::IncludeModule('iblock');
$rsSect = CIBlockSection::GetList(array('NAME' => 'asc'),array("ACTIVE" => "Y","GLOBAL_ACTIVE" => "Y","IBLOCK_ID" => 27,"SECTION_ID" => 0),false,array("ID","NAME"));
$ArrCity=Array();
while ($arSect = $rsSect->GetNext())
{
	$ArrCity[]=$arSect; 
}
?>					
                    <div class="form__row">
                      <div class="select">
                        <select class="input" type="text" name="city" id="city_s" required disabled>
						  <option value=""><?=GetMessage("FORM_CITY")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("CITY", $arParams["REQUIRED_FIELDS"])):?>*<?endif?></option>
						<?foreach($ArrCity as $city):?>
                          <option id="<?=$city['ID']?>" value="<?=$city['NAME']?>"<?if($arResult["CITY"]==$city['NAME'] OR $_POST['city']==$city['NAME']):?> selected<?endif;?>><?=$city['NAME']?></option>
						<?endforeach;?>  
                        </select>
						<input type="hidden" name="city" value="<?=($_POST['city']!="")? $_POST['city'] : $arResult["CITY"]?>">
                      </div>
                    </div>
                    <!-- street-->
<?
$ArrStreet=Array();
if($_POST['street']){	
    $rsSections = CIBlockSection::GetList(array(), array("NAME"=>$_POST['city']));
    $arSction = $rsSections->Fetch();
    $rsSect = CIBlockSection::GetList(array('NAME' => 'asc'),array("ACTIVE" => "Y","GLOBAL_ACTIVE" => "Y","IBLOCK_ID" => 27,"SECTION_ID" => $arSction['ID']),false,array("ID","NAME"));  
    while ($arSect = $rsSect->GetNext())
    {
	   $ArrStreet[]=$arSect;
    }
}	   
?>				
                    <div class="form__row">
                      <div class="select">
                        <select class="input" type="text" name="street" required disabled>
                          <option value=""><?=GetMessage("FORM_STREET")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("STREET", $arParams["REQUIRED_FIELDS"])):?>*<?endif?></option>
						  <div>
						<?foreach($ArrStreet as $street):?>
                          <option id="<?=$street['ID']?>" value="<?=$street['NAME']?>"<?if($arResult["STREET"]==$street['NAME'] OR $_POST['street']==$street['NAME']):?> <?echo "selected"; $street_id=$street['ID'];?><?endif;?>><?=$street['NAME']?></option>
						<?endforeach;?>
                          </div>						
                        </select>
						<input type="hidden" name="street" value="<?=($_POST['street']!="")? $_POST['street'] : $arResult["STREET"]?>">
                      </div>
                    </div>
                    <!-- house-->
<?
$ArrHouse=Array();
if($_POST['street']){
    $res = CIBlockElement::GetList(array('NAME' => 'asc'),array("ACTIVE" => "Y","IBLOCK_ID" => 27,"SECTION_ID" => $street_id),false,false,array("ID","NAME"));
    while($ob = $res->GetNextElement())
    {
       $str = $ob->GetFields();     
	   $ArrHouse[]=$str;
    }	
}
?>					
                    <div class="form__row">
                      <div class="select">
                        <select class="input" type="text" name="home" disabled required>
                          <option value=""><?=GetMessage("FORM_HOME")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("HOME", $arParams["REQUIRED_FIELDS"])):?>*<?endif?></option>
						<?foreach($ArrHouse as $home):?>
                          <option value="<?=$home['ID']?>"<?if($arResult["HOME"]==$home['ID'] OR $_POST['home']==$home['ID']):?><?$home_id=$home['ID'];?> selected<?endif;?>><?=$home['NAME']?></option>
						<?endforeach;?>						  
                        </select>
						<input type="hidden" name="home" value="<?=$home_id?>">
                      </div>
                    </div>
                    <!-- flat-->
                    <div class="form__row">
                      <input class="input" type="text" name="flat" value="<?=$_POST['flat']?>" placeholder="<?=GetMessage("FORM_FLAT")?><?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("FLAT", $arParams["REQUIRED_FIELDS"])):?>*<?endif?>" required>
                    </div>
                  </div>
                </div>
              </div>
              <!-- invoice-->
              <div class="order__invoice form__section">
                <div class="h3"><?=GetMessage("FORM_INVOICE")?></div>
                <div class="radio radio--plain">
                  <label>
                    <input type="radio" name="radio-1" value="<?=GetMessage("FORM_INVOICE_EMAIL")?>" checked="checked"><i></i><span class="radio__text"><?=GetMessage("FORM_INVOICE_EMAIL")?></span>
                  </label>
                </div>
                <div class="radio radio--plain">
                  <label>
                    <input type="radio" name="radio-1" value="<?=GetMessage("FORM_INVOICE_ADDRESS")?>"><i></i><span class="radio__text"><?=GetMessage("FORM_INVOICE_ADDRESS")?></span>
                  </label>
                </div>
              </div>
            </div>
	        <?if($arParams["USE_CAPTCHA"] == "Y"):?>
            <div class="form__row mf-captcha"><br>
                  <div class="ug-grid">
                    <div class="ug-col ug-col-phablet6">
		               <input type="hidden" name="captcha_sid" value="<?=$arResult["capCode"]?>">
		               <img style="margin:0px 10px 0px 0px; float:left;height:46px" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["capCode"]?>" alt="CAPTCHA">
		               <input type="text" style="width:207px" name="captcha_word"  class="input" value="" placeholder="<?=GetMessage("MFT_CAPTCHA_CODE")?>">
                    </div>
                  </div>
            </div>				
	        <?endif;?>			
            <!-- actions-->
            <div class="order__actions form__actions">
              <!-- terms-->
              <div class="order__terms">
			  <?//=GetMessage("FORM_USER_TERMS")?>
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/form_private.php"),
			Array(),
			Array("MODE"=>"html")
		);?> 			  
			  </div>
              <!-- agree-->
              <div class="order__agree">
                <div class="checkbox checkbox--plain">
                  <label>
                    <input type="checkbox" name="agree" value="да" required><i></i><span class="checkbox__text">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/form_agree.php"),
			Array(),
			Array("MODE"=>"html")
		);?> 					
					<?//=GetMessage("FORM_USER_AGREE")?>
					</span>
                  </label>
                </div>
              </div>			  
	          <input type="hidden" name="PARAMS_HASH" value="<?=$arResult["PARAMS_HASH"]?>">
              <!-- submit-->
              <input class="btn btn--primary" name="submit" type="submit" value="<?=GetMessage("MFT_SUBMIT")?>"> 
            </div>
			<div class="form_required_f"><font color="red"><span class="form-required starrequired">*</span></font><?=$arResult["REQUIRED_SIGN"];?> - <?=GetMessage("FORM_REQUIRED_FIELDS")?></div>	  
		  </div>		  
          </form>
<?endif;?>