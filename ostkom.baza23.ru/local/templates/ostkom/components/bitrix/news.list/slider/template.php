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
global $AddLang;
global $LangId;
?>
<?if($arResult["ITEMS"]):?>
    <div class="hero-slider module js-hero-slider" data-slick="{&quot;autoplay&quot;: 7000}">
    <?foreach($arResult["ITEMS"] as $arItem):?>	
	<?
		$shash="";
		if($arItem['PROPERTIES']['LINK']['VALUE']!="" AND ($arItem['PROPERTIES']['SERVICES_HOME']['VALUE'] OR $arItem['PROPERTIES']['SERVICES_BIS']['VALUE'])){
			$shash=($arItem['PROPERTIES']['SERVICES_HOME']['VALUE'])? $arItem['PROPERTIES']['SERVICES_HOME']['VALUE'] : $arItem['PROPERTIES']['SERVICES_BIS']['VALUE'];
			$shash="#S".$shash;
		}	
$this->setFrameMode(true);
if(!$LangRu){
	$arItem["NAME"]=$arItem['PROPERTIES']['NAME_LV']['VALUE']; 
	$arItem['PROPERTIES']['BUT_TEXT']['VALUE']=$arItem['PROPERTIES']['BUT_TEXT_LV']['VALUE'];
    $arItem["PREVIEW_TEXT"]=$arItem['~DETAIL_TEXT'];	
}	
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>	
      <div class="hero-slider__slide" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
        <div class="container hero-slider__slide-inner">
          <div class="hero-slider__image">
			<img class="preview_picture" border="0" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>"/>	
          </div>			
          <div class="hero-slider__content">
            <div class="hero-slider__title"><?=$arItem["NAME"]?></div>
            <div class="hero-slider__desc">
              <p><?echo $arItem["PREVIEW_TEXT"];?></p>
            </div>
			<?if($arItem['PROPERTIES']['LINK']['VALUE']!="" AND $arItem['PROPERTIES']['BUT_TEXT']['VALUE']!=""):?>
            <div class="hero-slider__action">
				<a class="hero-slider__action-btn btn btn--primary" href="<?if($LangRu):?>/<?=$LangId?><?endif;?><?=$arItem['PROPERTIES']['LINK']['VALUE']?><?=$shash?>"><?=$arItem['PROPERTIES']['BUT_TEXT']['VALUE']?></a>
			</div>
			<?endif;?>
          </div>
        </div>
      </div>
    <?endforeach;?> 	  
    </div>
<?endif;?>