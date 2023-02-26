<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
global $LangRu;
global $LangId;
__IncludeLang($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/ostkom/components/bitrix/news.list/advantages_business/lang/".$LangId."/template.php");
?>
<?if($arResult["ITEMS"]):?>
          <div class="features module">
            <!-- title-->
            <div class="features__title headline h2">
                <?=GetMessage("AV_ELEMENT_TITLE")?>			
			</div>
            <!-- list-->
            <ul class="features__list ug-grid ug-block-phablet6 ug-block-desktop3">
<?
  if(count($arResult["ITEMS"])<4){
	  $pr=100/count($arResult["ITEMS"]);
	  $style=" style='-ms-flex: 1 1 ".$pr."%;flex: 1 1 ".$pr."%;max-width: ".$pr."%;'";
  } 	  
?>	
<?foreach($arResult["ITEMS"] as $arItem):?>
<?
if(!$LangRu){
	$arItem["NAME"]=$arItem['PROPERTIES']['NAME_LV']['VALUE']; 	 
    $arItem["PREVIEW_TEXT"]=$arItem['PROPERTIES']['ANONS_LV']['VALUE']['TEXT'];  
    $arItem["~PREVIEW_TEXT"]=$arItem['PROPERTIES']['ANONS_LV']['~VALUE']['TEXT']; 	
}
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
?>			
              <!-- item-->
              <li class="features__item ug-col" id="<?=$this->GetEditAreaId($arItem['ID']);?>"<?=$style?>>
                <div class="features__item-icon">
				<?if($arItem['PROPERTIES']['IMAGE_ICON']['VALUE']!=""):?>
				   <?=$arItem['PROPERTIES']['IMAGE_ICON']['~VALUE']?>
				<?elseif($arItem["PREVIEW_PICTURE"]["SRC"]!=""):?>
				   <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"/>
				<?endif;?>
                </div>
                <div class="features__item-body">
                  <div class="features__item-title h5"><?=$arItem['NAME']?></div>
                  <div class="features__item-desc">
                    <p><?=$arItem['~PREVIEW_TEXT']?></p>
                  </div>
                </div>
              </li>
<?endforeach;?>			  
            </ul>
          </div>
<?endif;?>