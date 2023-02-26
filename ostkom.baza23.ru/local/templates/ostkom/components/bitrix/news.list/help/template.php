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
<div class="quest-list">
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	//echo"<pre>"; print_r($arItem);    echo"</pre>";
	if(!$LangRu){
		$arItem['PROPERTIES']['QUEST']['VALUE']=$arItem['PROPERTIES']['QUEST_LV']['VALUE']; 
		$arItem['PROPERTIES']['QUEST']['~VALUE']=$arItem['PROPERTIES']['QUEST_LV']['~VALUE']; 
		$arItem["~PREVIEW_TEXT"]=$arItem['~DETAIL_TEXT'];	
		$arItem["PREVIEW_TEXT"]=$arItem['DETAIL_TEXT'];		
	}	
	?>
	<div class="quest-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
    <?if($arItem['PROPERTIES']['QUEST']['~VALUE']['TEXT']!=""):?>
		<div class="quest">
			<a href="#"><?=$arItem['PROPERTIES']['QUEST']['~VALUE']['TEXT'] ?></a>
		</div>
		<div class="answer">
			<?=$arItem["~PREVIEW_TEXT"]?>
		</div>
	<?endif;?>
	</div>
<?endforeach;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
</div>
