<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
global $LangRu;
global $arr_divice_section;
?>
<br><br>
<?if(count(arResult["ITEMS"])>0):?>
   <?foreach($arResult["ITEMS"] as $arItem): 
//echo"<pre>"; print_r($arItem['ID']);  echo"</pre>"; 
     $class_gift=""; 
     if(count($arr_divice_section['GIFT'][$arItem['ID']])>0){
         $class_gift=" gift_block";
         foreach($arr_divice_section['GIFT'][$arItem['ID']] as $val){
		    $class_gift.=" gift_".$val;
	     }  
     }	 
if(!$LangRu){
     $arItem['~PREVIEW_TEXT']=$arItem['~DETAIL_TEXT'];
	 $arItem['NAME']=$arItem['NAME_LV'];
	 $arItem['PROPERTIES']['HTML_STANDART']['~VALUE']['TEXT']=$arItem['PROPERTIES']['HTML_STANDART_LV']['~VALUE']['TEXT'];
}   
   
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
   ?>
   <?if($arItem['PROPERTIES']['IMAGE_POS']['VALUE_XML_ID']=="right"):?>
          <div class="free-router module<?=$class_gift?>">
            <div class="free-router__inner ug-grid">
              <!-- image-->
              <div class="free-router__image ug-col-mobile5 ug-col-wide7">
			     <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['PREVIEW_PICTURE']['ALT']?>">
			  </div>
              <!-- body-->
              <div class="free-router__body ug-col-mobile7 ug-col-wide5">
                <div class="free-router__title h2">			  
			  <?if($arItem['~PREVIEW_TEXT']!=""):?>
			    <?=$arItem['~PREVIEW_TEXT']?>
			  <?else:?>	
			    <?=$arItem['NAME']?>
			  <?endif;?></div>
				<?if($arItem['PROPERTIES']['HTML_STANDART']['~VALUE']['TEXT']!=""):?>
                <div class="free-box__standards"><?=$arItem['PROPERTIES']['HTML_STANDART']['~VALUE']['TEXT']?></div> 
				<?endif;?>					
              </div>
            </div>
          </div> 
    <?else:?>
           <div class="free-box module<?=$class_gift?>">  
            <div class="free-box__inner ug-grid" id="<?=$this->GetEditAreaId($arItem['ID']);?>">	
              <div class="free-box__body  ug-col-mobile7 ug-col-tablet6">
                <div class="free-box__title h2">
			  <?if($arItem['~PREVIEW_TEXT']!=""):?>
			    <?=$arItem['~PREVIEW_TEXT']?>
			  <?else:?>	
			    <?=$arItem['NAME']?>
			  <?endif;?>
			    </div>		  
				<?if($arItem['PROPERTIES']['HTML_STANDART']['~VALUE']['TEXT']!=""):?>
                <div class="free-box__standards"><?=$arItem['PROPERTIES']['HTML_STANDART']['~VALUE']['TEXT']?></div>
				<?endif;?>			
              </div>
			  <?if($arItem['PREVIEW_PICTURE']):?>
              <div class="free-box__image  ug-col-mobile5 ug-col-tablet6">
			    <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['PREVIEW_PICTURE']['ALT']?>">
			  </div>
			  <?endif;?>
            </div>
          </div> 	

    <?endif;?>	
   <?endforeach;?>	
<?endif;?>


