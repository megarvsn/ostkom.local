<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 */

$this->setFrameMode(true);

$elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
$elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
$elementDeleteParams = array('CONFIRM' => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));
global $LangRu;
global $LangId;
__IncludeLang($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/ostkom/components/bitrix/catalog.section/telephony/lang/".$LangId."/template.php");
if (!empty($arResult['ITEMS']))
{
?>	
          <div class="phone-tariff module" >
<?		  
	foreach ($arResult['ITEMS'] as $key => $item)
	{	
//echo"<pre>";  print_r($item);	echo"</pre>";		
        if(!$LangRu){
            $item['~PREVIEW_TEXT']=$item['PROPERTIES']['ANONA_LV']['~VALUE']['TEXT'];
			$item['NAME']=$item['PROPERTIES']['NAME_LV']['VALUE'];
        }
			$uniqueId = $item['ID'].'_'.md5($this->randString().$component->getAction());
			$areaIds[$item['ID']] = $this->GetEditAreaId($uniqueId);
			$this->AddEditAction($uniqueId, $item['EDIT_LINK'], $elementEdit);
			$this->AddDeleteAction($uniqueId, $item['DELETE_LINK'], $elementDelete, $elementDeleteParams);
			$strMainID = $this->GetEditAreaId($item['ID']);			
?>

            <div class="phone-tariff__inner ug-grid ug-block-phablet6" id="<?echo $areaIds[$item['ID']];?>">
			  <?if(($key % 2)== 0):?>
              <div class="phone-tariff__desc ug-col"><?=$item['~PREVIEW_TEXT']?></div>
			  <?else:?>
              <div class="phone-tariff__body ug-col">
                <div class="phone-tariff__body-inner">
                  <div class="phone-tariff__body-title h3"><?=GetMessage('TARIF')?> «<?=$item['NAME']?>»</div>
                  <div class="phone-tariff__body-price">
                    <div class="price h3">
                      <div class="price__value"><?=$item['PROPERTIES']['PRICE']['VALUE']?></div>
					  <?if($item['PROPERTIES']['OLD_PRICE']['VALUE']>0):?>
					  <div class="price price--discount"><div class="price__value"><?=$item['PROPERTIES']['OLD_PRICE']['VALUE']?></div></div>
					  <?endif;?>
                      <div class="price__suffix"><?=GetMessage('CURRENCY')?></div>
                    </div>
                  </div>
                  <div class="phone-tariff__body-action"><a class="btn btn--primary" href="<?if($LangRu):?>/ru<?endif;?><?=$item['DETAIL_PAGE_URL']?>"><?=GetMessage('CT_BCS_TPL_MESS_BTN_DETAIL')?></a></div>
                </div>
              </div>
			  <?endif;?>
			  <?if(($key % 2)== 0):?>
              <div class="phone-tariff__body ug-col">
                <div class="phone-tariff__body-inner">
                  <div class="phone-tariff__body-title h3"><?=GetMessage('TARIF')?> «<?=$item['NAME']?>»</div>
                  <div class="phone-tariff__body-price">
                    <div class="price h3">
                      <div class="price__value"><?=$item['PROPERTIES']['PRICE']['VALUE']?></div>
					  <?if($item['PROPERTIES']['OLD_PRICE']['VALUE']>0):?>
					  <div class="price price--discount"><div class="price__value"><?=$item['PROPERTIES']['OLD_PRICE']['VALUE']?></div></div>
					  <?endif;?>
                      <div class="price__suffix"><?=GetMessage('CURRENCY')?></div>
                    </div>
                  </div>
                  <div class="phone-tariff__body-action"><a class="btn btn--primary" href="<?if($LangRu):?>/ru<?endif;?><?=$item['DETAIL_PAGE_URL']?>"><?=GetMessage('CT_BCS_TPL_MESS_BTN_DETAIL')?></a></div>
                </div>
              </div>			  
			  <?else:?>
              <div class="phone-tariff__desc ug-col"><?=$item['~PREVIEW_TEXT']?> </div>
			  <?endif;?>			  
            </div>
<?			
	}
?>
          </div>
<?
}
?>