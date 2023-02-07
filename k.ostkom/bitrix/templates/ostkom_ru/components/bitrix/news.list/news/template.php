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
global $Lang_2;
?>
<?if($arResult["ITEMS"]):?>
          <div class="entries module">
            <!-- title-->
            <div class="entries__title headline h2">
			<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/index_title3.php"),
			Array(),
			Array("MODE"=>"html")
		    );?> 			
			</div>
            <!-- list-->
            <ul class="entries__list ug-grid ug-block-mobile6 ug-block-tablet4">
			<?foreach($arResult["ITEMS"] as $k => $arItem):?>
	<?
if(!$LangRu){
	$arItem["NAME"]=$arItem['PROPERTIES']['NAME_LV']['VALUE']; 	
	$arItem["DETAIL_TEXT"]=$arItem['PROPERTIES']['TEXT_LV']['~VALUE']['TEXT'];  
    $arItem["PREVIEW_TEXT"]=$arItem['PROPERTIES']['ANONS_LV']['VALUE']['TEXT'];  
    $arItem["~PREVIEW_TEXT"]=$arItem['PROPERTIES']['ANONS_LV']['~VALUE']['TEXT']; 	
}	
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	$file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>600,'height'=>300),BX_RESIZE_IMAGE_PROPORTIONAL);  
	?>			
              <!-- item-->
              <li class="<?if($k<2):?>ug-colfalse<?else:?>ug-col ug-show-tablet<?endif;?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                <div class="entries__item">		
		<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>				
                  <!-- image-->
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
		<?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
		             <time class="entries__item-date" datetime="<?echo $arItem["DISPLAY_ACTIVE_FROM"]?>"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></time>
		<?endif?>				  
		<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
			   <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
				   <div class="entries__item-title h4"><a href="<?if($LangRu):?>/ru<?endif;?><?echo $arItem["DETAIL_PAGE_URL"]?>"><?echo $arItem["NAME"]?></a></div>
			   <?else:?>
				   <div class="entries__item-title h4"><?echo $arItem["NAME"]?></div>
			   <?endif;?>
		<?endif;?>				  
                    <div class="entries__item-desc">
                      <p><?echo $arItem["~PREVIEW_TEXT"];?></p>
                    </div>
                  </div>
                </div>
              </li>
              <!-- item-->
			<?endforeach;?>  
            </ul>
            <!-- all offers-->
            <div class="entries__all">
			<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/index_title4.php"),
			Array(),
			Array("MODE"=>"html")
		    );?>			
	        </div>
          </div>				
<?endif;?>			