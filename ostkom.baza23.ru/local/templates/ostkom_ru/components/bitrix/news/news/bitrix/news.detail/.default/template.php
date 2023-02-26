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
global $str_name;
__IncludeLang($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/ostkom/components/bitrix/news/news/bitrix/news.detail/.default/lang/".$LangId."/template.php");
if(!$LangRu){
	$arResult["NAME"]=$arResult['PROPERTIES']['NAME_LV']['VALUE']; 	
	$arResult["DETAIL_TEXT"]=$arResult['PROPERTIES']['TEXT_LV']['~VALUE']['TEXT'];   
    $arResult["FIELDS"]["PREVIEW_TEXT"]=$arResult['PROPERTIES']['ANONS_LV']['~VALUE']['TEXT']; 	
}
?>
          <!-- entry-->
          <div class="entry module">
<?if($arParams["DISPLAY_DATE"]!="N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>		  
            <!-- time-->
            <time class="entry__date" datetime="<?=$arResult["DISPLAY_ACTIVE_FROM"]?>">
			<?=$arResult["DISPLAY_ACTIVE_FROM"]?>
			</time>
<?endif;?>			
            <!-- image-->
<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"])):?>			
            <div class="entry__image">
<?
    $file = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"],array('width'=>1200, 'height'=>400),BX_RESIZE_IMAGE_PROPORTIONAL);	
?>				
              <div class="image-box" style="padding-bottom:33.33%;">
			  <img src="<?=$file['src']?>" alt="<?=$arResult["NAME"]?>"/>
              </div>
            </div>
<?endif;?>			
            <!-- content-->
            <div class="entry__content">		
              <?echo $arResult["DETAIL_TEXT"];?>
            </div>	
<?
	if(array_key_exists("USE_SHARE", $arParams) && $arParams["USE_SHARE"] == "Y")
	{
		?>
		<div class="news-detail-share">
			<noindex>
			<?
			$APPLICATION->IncludeComponent("bitrix:main.share", "", array(
					"HANDLERS" => $arParams["SHARE_HANDLERS"],
					"PAGE_URL" => $arResult["~DETAIL_PAGE_URL"],
					"PAGE_TITLE" => $arResult["~NAME"],
					"SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
					"SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
					"HIDE" => $arParams["SHARE_HIDE"],
				),
				$component,
				array("HIDE_ICONS" => "Y")
			);
			?>
			</noindex>
		</div>
		<?
	}
?>	
            <!-- all entries-->		
            <div class="entry__all"><a class="entry__all-btn btn btn--secondary" href="<?=$arResult["LIST_PAGE_URL"]?>"><?=GetMessage("ALL_NEWS")?></a></div>
          </div>




