<?
global $LangRu;
global $LangId;
$cur_lv="EUR/men.";
if(!$LangRu){
    $services_title_N="Pakalpojuma nav izveletaja adrese";	
    $services_title_Y="Pakalpojumu saraksts pieejams izveletaja adrese";		
}
else{
    $services_title_N="Нет услуг, доступных по выбранному адресу";
    $services_title_Y="Список услуг, доступных по выбранному адресу";	
}

    $res = CIBlockElement::GetList(array(),array("ACTIVE" => "Y","IBLOCK_ID" => 27,"ID" => $_POST['home']),false,false,array("ID","NAME","PROPERTY_NAME_LV","IBLOCK_ID","PROPERTY_SERVICES","IBLOCK_SECTION_ID"));	   
    $ob = $res->GetNextElement();
	$arFieldsAddress = $ob->GetFields();
    $arProps = $ob->GetProperties();
    $arrServices=$arProps['SERVICES']['VALUE'];
	$resAddress = CIBlockSection::GetByID($arFieldsAddress['IBLOCK_SECTION_ID']);
    if($ar_address = $resAddress->GetNext()){
		$city=$ar_address['IBLOCK_SECTION_ID'];
    }	
	$arrElem=array();
	$arrSection=array();
    $rsSect = CIBlockSection::GetList(array("SORT" => 'asc'),array("ACTIVE" => "Y","ACTIVE_DATE"=>"Y","IBLOCK_ID" => 7),false,array("ID","NAME","UF_NAME_LV","SECTION_PAGE_URL","IBLOCK_SECTION_ID"));
    while ($arSect = $rsSect->GetNext())
    {	
        if(!$LangRu){
			$arSect['NAME']=$arSect['UF_NAME_LV'];
        }			
	    $arrSection[$arSect['ID']]=$arSect;
        $resel = CIBlockElement::GetList(array("SORT" => "ASC"),array("ACTIVE" => "Y","ACTIVE_DATE"=>"Y","IBLOCK_ID" => 7,"SECTION_ID"=>$arSect['ID'],"PROPERTY_FEATURE" => $arrServices, "PROPERTY_CITY" => $city),false,false,array("IBLOCK_ID","NAME","PROPERTY_NAME_LV","ID", "PROPERTY_PRICE","PROPERTY_OLD_PRICE","PROPERTY_CURRENCY"));
		while($ob = $resel->GetNextElement()){ 
          $arFields = $ob->GetFields(); 
		  $ElProps = $ob->GetProperties();	
		  $array_diff = array_diff($ElProps['FEATURE']['VALUE'],$arrServices);	
			if(count($array_diff)<1){ 	
			  $arFields['PROPERTY_SPEED_VALUE']=$ElProps['SPEED']['VALUE'];
			  $arFields['PROPERTY_UNIT_VALUE']=$ElProps['UNIT']['VALUE'];
			  $arFields['PROPERTY_CHANNEL_DG_VALUE']=$ElProps['CHANNEL_DG']['VALUE'];
			  $arFields['PROPERTY_ANALOG_TV_VALUE']=$ElProps['ANALOG_TV']['VALUE'];
			  $arFields['PROPERTY_CHANNEL_HD_VALUE']=$ElProps['CHANNEL_HD']['VALUE'];
			  
			  if(!$LangRu){
					$arFields['NAME']=$arFields['PROPERTY_NAME_LV_VALUE'];
					$arFields['~NAME']=$arFields['PROPERTY_NAME_LV_VALUE'];				
					$arFields['PROPERTY_CURRENCY_VALUE']=$cur_lv;
					$arFields['PROPERTY_UNIT_VALUE']=$ElProps['UNIT_LV']['VALUE'];
					$arFields['PROPERTY_CHANNEL_DG_VALUE']=$ElProps['CHANNEL_DG_LV']['VALUE'];
					$arFields['PROPERTY_ANALOG_TV_VALUE']=$ElProps['ANALOG_TV_LV']['VALUE'];
					$arFields['PROPERTY_CHANNEL_HD_VALUE']=$ElProps['CHANNEL_HD_LV']['VALUE'];				
			  }		  
			  if($ElProps['PACKAGES']['VALUE']){			  
				 $packages = CIBlockElement::GetList(array("SORT" => "ASC"),array("ACTIVE" => "Y","ACTIVE_DATE"=>"Y","IBLOCK_ID" => 24,"ID" => $ElProps['PACKAGES']['VALUE'],"PROPERTY_CITY" => $city),false,false,array("IBLOCK_ID","NAME","PROPERTY_NAME_LV","ID", "PROPERTY_PRICE","PROPERTY_OLD_PRICE","PROPERTY_CURRENCY"));
				 while($package = $packages->GetNextElement()){ 	
					 $arFieldsPackages = $package->GetFields();	
					 $arPropsPackages = $package->GetProperties();	
					 $arFieldsPackages['PROPERTY_CHANNELS_VALUE']=$arPropsPackages['CHANNELS']['VALUE'];				 
					 if(!$LangRu){
						 $arFieldsPackages['NAME']=$arFieldsPackages['PROPERTY_NAME_LV_VALUE'];
						 $arFieldsPackages['PROPERTY_CURRENCY_VALUE']=$cur_lv;
						 $arFieldsPackages['PROPERTY_CHANNELS_VALUE']=$arPropsPackages['CHANNELS_LV']['VALUE'];				 
					 }	
					 $arFields['PACKAGES'][]=$arFieldsPackages;				 	
				 } 
			  }			  
			  $arrElem[$arSect['ID']][]=$arFields;	
			}
        }
		$arrSection[$arSect['ID']]['ELEMENTS']=$arrElem[$arSect['ID']];
		$arrSection[$arSect['ID']]['available']=count($arrElem[$arSect['ID']]);
    }
    $available=0;	
    foreach($arrSection as $key => $sec)
    {
		if($sec['IBLOCK_SECTION_ID']>0){
			$ArrSections[$sec['IBLOCK_SECTION_ID']]['SECTIONS'][]=$sec;
			if($sec['available']>0){
				$ArrSections[$sec['IBLOCK_SECTION_ID']]['available']=$ArrSections[$sec['IBLOCK_SECTION_ID']]['available']+$sec['available'];
				$available+=$sec['available'];
			}	
		}
		else{
			$ArrSections[$sec['ID']]['SECTION']=$sec;
			$available+=$sec['available'];
		}	
    }	   	
?>	
      <div class="services module">
<?if($available>0):?>
            <div class="services__title h2"><?=$services_title_Y?></div>		
<?
    $Arr_Av=Array();
    foreach($ArrSections as $section)
    {
        if($section['available']>0 OR $section['SECTION']['available']>0){			
?>
            <div class="services__section">
              <div class="services__section-title h2"><?=$section['SECTION']['NAME']?></div>
			<?if(count($section['SECTION']['ELEMENTS'])>0){
			  $Arr_Av[]=$section['SECTION']['ID'];			
			  GetElements($section['SECTION']['ELEMENTS'],$section['SECTION']['SECTION_PAGE_URL']);   
            }?>			
			<?if(count($section['SECTIONS'])>0):?>  
			  <?foreach($section['SECTIONS'] as $subsection):?>
			  <?if($subsection['available']>0):?> 
			 <?$Arr_Av[]=$subsection['ID'];?> 
              <div class="services__section-subtitle u-size-h5"><?=$subsection['NAME']?></div>
			  <?if(count($subsection['ELEMENTS'])>0){
                 GetElements($subsection['ELEMENTS'],$section['SECTION']['SECTION_PAGE_URL']); 
			  }?>
			  <?endif;?>
			  <?endforeach;?>
			 <?endif;?> 
              <!-- list-->
            </div>
<?
        }
    }
	$_SESSION['available']=$Arr_Av;
?>	
<?else:?>
             <div class="services__title h2"><?=$services_title_N?></div>		
<?endif;?>	
          </div>
<?
function GetElements($ArElements,$url){
	global $LangRu;
    global $LangId;
	//echo"--$LangRu -  $LangId --";
	if($LangRu==1)  $url="/".$LangId.$url;
?>	
				<div class="services__list">
				    <ul class="ug-grid ug-block-mobile6 ug-block-tablet4">
                  <?foreach($ArElements as $element):?>
				  <?if($element['PACKAGES']):?>
				    </ul> 
				</div>	
				<div class="services__list">
				    <ul class="ug-grid ug-block-mobile6 ug-block-tablet4">
				  <?endif;?>
                       <li class="services__item ug-col<?if($element['PACKAGES']):?> SParent<?endif;?>">			   
				          <a class="services__link" href="<?=$url?>">
                             <div class="services__link-name"><?=$element['NAME']?></div>	 
				     <?if($element['PROPERTY_SPEED_VALUE']!=""):?>
							<div class="prop_value"><?=$element['PROPERTY_SPEED_VALUE']?> <?=$element['PROPERTY_UNIT_VALUE']?></div>
					 <?endif;?>

					 <?if($element['PROPERTY_CHANNEL_DG_VALUE']!=""):?>
                            <div class="prop_value"><span href="/channels.php?id=<?=$element['ID']?>&type=2" class="popup_bl"><?=$element['PROPERTY_CHANNEL_DG_VALUE']?></span></div>
                     <?endif;?>						   
					 <?if($element['PROPERTY_ANALOG_TV_VALUE']!=""):?>
							<div class="prop_value"><span href="/channels.php?id=<?=$element['ID']?>&type=1" class="popup_bl"><?=$element['PROPERTY_ANALOG_TV_VALUE']?></span></div>
                     <?endif;?>
					 <?if($element['PROPERTY_CHANNEL_HD_VALUE']!=""):?>
							<div class="prop_value"><span href="/channels.php?id=<?=$element['ID']?>&type=3" class="popup_bl"><?=$element['PROPERTY_CHANNEL_HD_VALUE']?></span></div>
                     <?endif;?>								 
                             <div class="services__link-price">
                                <div class="price">
                                   <div class="price__value"><?=$element['PROPERTY_PRICE_VALUE']?></div>
                                   <div class="price__suffix"><?=$element['PROPERTY_CURRENCY_VALUE']?></div>
                                </div>
                             </div>
				          </a>
					  </li>		  
                  <?if($element['PACKAGES']):?>
				  <?foreach($element['PACKAGES'] as $element):?>
                       <li class="services__item ug-col addpack">			   
				          <a class="services__link" href="<?=$url?>">
                             <div class="services__link-name"><?=$element['NAME']?></div>
					 <?if($element['PROPERTY_CHANNELS_VALUE']!=""):?>
							 <div class="prop_value"><span href="/channels.php?id=<?=$element['ID']?>" class="popup_bl"><?=$element['PROPERTY_CHANNELS_VALUE']?></span></div>
                     <?endif;?>								 
                             <div class="services__link-price">
                                <div class="price">
                                   <div class="price__value"><?=$element['PROPERTY_PRICE_VALUE']?></div>								   
                                   <div class="price__suffix"><?=$element['PROPERTY_CURRENCY_VALUE']?></div>
                                </div>
                             </div>
				          </a>
				       </li>
                  <?endforeach;?>
                  <?endif;?>						  				   
                  <?endforeach;?> 				  
                    </ul>
                 </div>	
<?				 
}	
?>		  