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
global $LangRu;
global $LangId;
?>
<!-- news-->
<div class="entries module">
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	        <?=$arResult["NAV_STRING"]?> 
<?endif;?>
            <ul class="entries__list ug-grid ug-block-mobile6 ug-block-tablet4">
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
if(!$LangRu){
	$arItem["NAME"]=$arItem['PROPERTIES']['NAME_LV']['VALUE']; 	
	$arItem["DETAIL_TEXT"]=$arItem['PROPERTIES']['TEXT_LV']['~VALUE']['TEXT'];  
    $arItem["PREVIEW_TEXT"]=$arItem['PROPERTIES']['ANONS_LV']['VALUE']['TEXT'];  
    $arItem["~PREVIEW_TEXT"]=$arItem['PROPERTIES']['ANONS_LV']['~VALUE']['TEXT']; 	
}	
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	$file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>400,'height'=>200),BX_RESIZE_IMAGE_PROPORTIONAL);  
	?>
              <li class="ug-col" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
<?//echo"<pre>"; print_r($arItem);   echo"</pre>";?>			  
                <div class="entries__item">
                  <!-- image-->
	<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>  
	    <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
				  <a class="entries__item-image" href="<?if($LangRu):?>/ru<?endif;?><?=$arItem["DETAIL_PAGE_URL"]?>">
                    <div class="image-box">
					<img src="<?=$file['src']?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"/>
                    </div>
				  </a>
		<?else:?>	
                    <div class="image-box">
					<img src="<?=$file['src']?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"/>
                    </div>	
		<?endif;?>					
	<?endif;?>  
                  <!-- body-->
                  <div class="entries__item-body">
		<?if($arParams["DISPLAY_DATE"]!="N"):?>	
<?
$arDate = ParseDateTime($arItem["TIMESTAMP_X"], FORMAT_DATETIME);
//echo"<pre>"; print_r($arDate);   echo"</pre>";
?>		
                    <time class="entries__item-date" datetime="<?echo $arItem["DISPLAY_ACTIVE_FROM"]?>"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></time>
		<?endif?>					
    <?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>	
			<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>	
                    <div class="entries__item-title h4"><a href="<?if($LangRu):?>/ru<?endif;?><?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></div>
			<?else:?>	
                    <div class="entries__item-title h4"><?=$arItem["NAME"]?></div>
            <?endif;?>					
	<?endif;?>  
		<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
			        <div class="entries__item-desc">
                      <p><?=$arItem["~PREVIEW_TEXT"]?></p>
                    </div>
		<?endif;?>	
                  </div>				  
                </div>
              </li>	
<?endforeach;?>
           </ul>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	       <?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>