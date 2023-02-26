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
__IncludeLang($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/ostkom/components/bitrix/news.list/actions/lang/".$LangId."/template.php");
?>
<?if($arResult["ITEMS"]):?>
          <div class="entries module">
            <!-- title-->
            <div class="entries__title headline h2"><?=GetMessage("CT_BNL_ELEMENT_TITLE")?></div>
            <!-- list-->
            <ul class="entries__list ug-grid ug-block-mobile6">
			<?foreach($arResult["ITEMS"] as $arItem):?>
	<?	
if(!$LangRu){
	$arItem["NAME"]=$arItem['PROPERTIES']['NAME_LV']['VALUE']; 	
	$arItem["DETAIL_TEXT"]=$arItem['PROPERTIES']['TEXT_LV']['~VALUE']['TEXT'];  
    $arItem["PREVIEW_TEXT"]=$arItem['PROPERTIES']['ANONS_LV']['VALUE']['TEXT'];  
    $arItem["~PREVIEW_TEXT"]=$arItem['PROPERTIES']['ANONS_LV']['~VALUE']['TEXT']; 	
}	
	$list_page=$arItem['LIST_PAGE_URL'];
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	$file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>600,'height'=>300),BX_RESIZE_IMAGE_PROPORTIONAL);  
	?>			
              <!-- item-->
              <li class="ug-col" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                <div class="entries__item">
		<?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
			<span class="news-date-time"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></span>
		<?endif?>		
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
               <a class="btn btn--secondary" href="<?if($LangRu):?>/<?=$Lang_2?><?endif;?><?=$list_page?>"><?=GetMessage("CT_BNL_ELEMENT_ALL")?></a> 			
			</div>
          </div>			
<?endif;?>			
