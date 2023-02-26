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
if(!$LangRu){
   $arResult["DISPLAY_PROPERTIES"]["TEXT_TOP"]["VALUE"]=$arResult["DISPLAY_PROPERTIES"]["TEXT_TOP_LV"]["VALUE"];
   $arResult["DISPLAY_PROPERTIES"]["TEXT_BOT"]["VALUE"]=$arResult["DISPLAY_PROPERTIES"]["TEXT_BOT_LV"]["VALUE"];   
}   
?>
	<div class="hero-inet-tv__inner ug-grid">
		<div class="hero-inet-tv__image ug-col-phablet6 ug-col-desktop7">
		    <img  src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>">
		</div>
		<div class="hero-inet-tv__body ug-col-phablet6 ug-col-desktop5">
			<div class="hero-tv__subtitle h3">
				<?=$arResult["DISPLAY_PROPERTIES"]["TEXT_TOP"]["VALUE"]?>                    
			</div>
            <div class="purple_22">
                 <?=$arResult["DISPLAY_PROPERTIES"]["TEXT_BOT"]["VALUE"]?>   			                     
			 </div>							   
		</div>
	</div>