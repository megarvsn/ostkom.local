<?
CModule::IncludeModule('iblock');
global $LangRu;
global $LangId;
if(!$LangRu){
    $city_n="Pilsēta";	
	$street_n="Iela";
    $home_n="Mājas numurs";	
    $submit_n="Sākt pārbaudi";		
}
else{
    $city_n="Город";
	$street_n="Улица";	
	$home_n="Номер дома";
    $submit_n="Начать проверку";	
}
$rsSect = CIBlockSection::GetList(array('NAME' => 'asc'),array("ACTIVE" => "Y","GLOBAL_ACTIVE" => "Y","IBLOCK_ID" => 27,"SECTION_ID" => 0),false,array("ID","NAME"));
$ArrCity=Array();
while ($arSect = $rsSect->GetNext())
{
	$ArrCity[]=$arSect; 
}
?>	
            <form class="scan__form form" action="<?if($LangRu):?>/<?=$LangId?><?endif;?><?if($_POST['tariff'] OR $_POST['section']):?>/order/<?else:?>/verification/<?endif;?>" id="order_form" method="POST">
			 <input type="hidden" name="land_id" value="<?=$LangId?>">
			<input type="hidden" name="land" value="<?=$LangRu?>">
<?if($_POST['tariff'] OR $_POST['tariff_id']):?>
<?//echo"<pre>";  print_r($_POST);  echo"</pre>";?>
    <?foreach($_POST as $key => $post):?>
        <?if(is_array($post)):?>
             <?foreach($post as $k=>$p):?>
			  <?if(is_array($p)):?>
			    <?foreach($p as $k1=>$p1):?>
			  <input type="hidden" name="<?=$key?>[<?=$k?>][<?=$k1?>]" value="<?=$p1?>">	
				<?endforeach;?>	
			  <?else:?>
			  <input type="hidden" name="<?=$key?>[<?=$k?>]" value="<?=$p?>">	
			  <?endif;?>
            <?endforeach;?>			  
		<?else:?>
              <input type="hidden" name="<?=$key?>" value="<?=$post?>">
		<?endif;?>	  
	<?endforeach;?>
<?endif;?>
              <div class="form__grid ug-grid ug-block-tablet4">
                <div class="form__col ug-col">
                  <div class="select">
					<select class="input" type="text" name="city" id="city_s" required>
                      <option value=""><?=$city_n?>*</option>
					<?foreach($ArrCity as $city):?>
                      <option id="<?=$city['ID']?>" value="<?=$city['NAME']?>"<?if($city['NAME']==$_POST['city']):?> selected<?endif;?>><?=$city['NAME']?></option>
					<?endforeach;?>
                    </select>
                  </div>
                </div>
<?
$ArrStreet=Array();
if($_POST['city']){	
    $rsSections = CIBlockSection::GetList(array(), array("NAME"=>$_POST['city']));
    $arSction = $rsSections->Fetch();
    $rsSect = CIBlockSection::GetList(array('NAME' => 'asc'),array("ACTIVE" => "Y","GLOBAL_ACTIVE" => "Y","IBLOCK_ID" => 27,"SECTION_ID" => $arSction['ID']),false,array("ID","NAME"));  
    while ($arSect = $rsSect->GetNext())
    {
	   $ArrStreet[]=$arSect;
    }
}	   
?>
                <div class="form__col ug-col">
                  <div class="select">
                    <select class="input" type="text" name="street" required>
                      <option value=""><?=$street_n?>*</option>
						<?foreach($ArrStreet as $street):?>
                          <option id="<?=$street['ID']?>" value="<?=$street['NAME']?>"<?if($street['NAME']==$_POST['street']):?><?echo" selected"; $street_id=$street['ID']?><?endif;?>><?=$street['NAME']?></option>
						<?endforeach;?>
                    </select>
                  </div>
                </div>
<?
$ArrHouse=Array();
$res = CIBlockElement::GetList(array('NAME' => 'asc'),array("ACTIVE" => "Y","IBLOCK_ID" => 27,"SECTION_ID" => $street_id),false,false,array("ID","NAME"));
while($ob = $res->GetNextElement())
{
       $str = $ob->GetFields();   
	   $ArrHouse[]=$str;
}	
?>	
                <div class="form__col ug-col">
                  <div class="select">
                    <select class="input" type="text" name="home" required>
                      <option value=""><?=$home_n?>*</option>
						<?foreach($ArrHouse as $home):?>
                          <option value="<?=$home['ID']?>"<?if($home['ID']==$_POST['home']):?> selected<?endif;?>><?=$home['NAME']?></option>
						<?endforeach;?>
                    </select>
                  </div>
                </div>
              </div>
              <!-- actions-->
              <div class="form__actions u-text-center">
                <button class="btn btn--primary" type="submit"><?=$submit_n?></button>
              </div>
            </form>
<script src="/bitrix/templates/ostkom/scripts/order.js"></script>	
