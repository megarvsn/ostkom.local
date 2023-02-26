<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<?if($arResult["ITEMS"]):?>
          <div class="features module">
            <!-- title-->
            <div class="features__title headline h3">
		<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("include_areas/advantages_business_title.php"),
			Array(),
			Array("MODE"=>"html","NAME"=>"Заголовок Блока НАШИ ПРЕИМУЩЕСТВА")
		);?> 			
			</div>
            <!-- list-->
            <ul class="features__list ug-grid ug-block-phablet6 ug-block-desktop3">
<?foreach($arResult["ITEMS"] as $arItem):?>
<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
?>			
              <!-- item-->
              <li class="features__item ug-col" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
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