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
global $Item_num;
global $LangRu;
global $LangId;
__IncludeLang($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/ostkom/components/bitrix/catalog.section/home_internet_tv/lang/".$LangId."/template.php");
//print_r($arResult['ITEMS']);
if (!empty($arResult['ITEMS']))
{
	global $set_name;
	global $keys;
	global $arr_divice_section;
	global $arr_ip_product;
	global $arr_net_connect;
?>
        <div class="tariffs__section module n<?=$keys?>">
		<?if($set_name!=""):?>
            <div class="tariffs__section-title h5"><?=$set_name?></div>
		<?endif;?>
<?

    $arGroups = [];
	foreach ($arResult['ITEMS'] as $item)
	{
        $arTariff = $arParams["~U_PARAMS"]["TARIFFS"]["LIST"][$item["ID"]];
//echo $item["ID"] . ' - ' . $arTariff["CONTRACT_GROUP"] . ' - ' . $arTariff["PRICE"] . PHP_EOL;
        if (in_array($arTariff["CONTRACT_GROUP"], $arGroups)) continue;
        $arGroups[] = $arTariff["CONTRACT_GROUP"];
        $Item_num ++;

        if(!$LangRu){
	        $item['NAME']=$item['PROPERTIES']['NAME_LV']['VALUE'] ;
            $item['PROPERTIES']['UNIT_LV']['VALUE'];
            $item['PROPERTIES']['SPECIAL_OFFER']['~VALUE']=$item['PROPERTIES']['SPECIAL_OFFER_LV']['~VALUE'];
			$item['PROPERTIES']['SPECIAL_OFFER']['VALUE']=$item['PROPERTIES']['SPECIAL_OFFER_LV']['VALUE'];
			$item['PROPERTIES']['UNIT']['VALUE']=$item['PROPERTIES']['UNIT_LV']['VALUE'];
			$item['PROPERTIES']['ANALOG_TV']['VALUE']=$item['PROPERTIES']['ANALOG_TV_LV']['VALUE'];
            $item['PROPERTIES']['CHANNEL_DG']['VALUE']=$item['PROPERTIES']['CHANNEL_DG_LV']['VALUE'];
            $item['PROPERTIES']['CHANNEL_HD']['VALUE']=$item['PROPERTIES']['CHANNEL_HD_LV']['VALUE'];
        }
        if($item['PROPERTIES']['ADD_DEVICES_SALE']['VALUE']){
			$arr_divice_section['ITEMS']['sale'][$item['ID']]=$item['PROPERTIES']['ADD_DEVICES_SALE']['VALUE'];
            $resd = CIBlockElement::GetList(Array('SORT' => 'asc'), array('IBLOCK_ID' => 18, "ACTIVE" => "Y", 'ID' => $item['PROPERTIES']['ADD_DEVICES_SALE']['VALUE'],'!PROPERTY_DISPLAY' => "39"), false, false, array("DETAIL_PICTURE","NAME","PROPERTY_NAME_LV","ID","PROPERTY_SELLING_PRICE","PROPERTY_RENT_PRICE","PROPERTY_OLD_SELLING_PRICE","PROPERTY_OLD_RENT_PRICE","PROPERTY_TYPE_PRICE","PROPERTY_TYPE_PRICE_RENT","PROPERTY_DISPLAY","IBLOCK_SECTION_ID"));
            while($obd = $resd->GetNextElement())
            {
                 $ArDevicesd = $obd->GetFields();
                 $arr_divice_section['DEVACE'][$ArDevicesd['IBLOCK_SECTION_ID']]['sale'][]=$item['ID'];
            }
		}
        if($item['PROPERTIES']['ADD_DEVICES_RENT']['VALUE']){
			$arr_divice_section['ITEMS']['rent'][$item['ID']]=$item['PROPERTIES']['ADD_DEVICES_RENT']['VALUE'];
            $resd = CIBlockElement::GetList(Array('SORT' => 'asc'), array('IBLOCK_ID' => 18, "ACTIVE" => "Y", 'ID' => $item['PROPERTIES']['ADD_DEVICES_RENT']['VALUE'], '!PROPERTY_DISPLAY' => "39"), false, false, array("DETAIL_PICTURE","NAME","PROPERTY_NAME_LV","ID","PROPERTY_SELLING_PRICE","PROPERTY_RENT_PRICE","PROPERTY_OLD_SELLING_PRICE","PROPERTY_OLD_RENT_PRICE","PROPERTY_TYPE_PRICE","PROPERTY_TYPE_PRICE_RENT","PROPERTY_DISPLAY","IBLOCK_SECTION_ID"));
            while($obd = $resd->GetNextElement())
            {
                 $ArDevicesd = $obd->GetFields();
                 $arr_divice_section['DEVACE'][$ArDevicesd['IBLOCK_SECTION_ID']]['rent'][]=$item['ID'];
            }
		}
        if($item['PROPERTIES']['DEV_GIFT']['VALUE']){
			foreach($item['PROPERTIES']['DEV_GIFT']['VALUE'] as $val){
			    $arr_divice_section['GIFT'][$val][]=$item['ID'];
			}
		}
        if($item['PROPERTIES']['IP']['VALUE']!=""){
			$arr_ip_product[$item['ID']]['SUM']=$item['PROPERTIES']['IP']['VALUE'];
		}
        if($item['PROPERTIES']['DESC_IP']['VALUE']['TEXT']!=""){
			$arr_ip_product[$item['ID']]['TEXT']=$item['PROPERTIES']['DESC_IP']['~VALUE']['TEXT'];
		}
        if($item['PROPERTIES']['DESC_IP_LV']['VALUE']['TEXT']!=""){
			$arr_ip_product[$item['ID']]['TEXT_LV']=$item['PROPERTIES']['DESC_IP_LV']['~VALUE']['TEXT'];
		}
        if($item['PROPERTIES']['NET_CONNECT']['VALUE']){
			foreach($item['PROPERTIES']['NET_CONNECT']['VALUE'] as $kc => $val){
				$arr_net_connect[$val][]=$item['ID'];
			}
		}
			$uniqueId = $item['ID'].'_'.md5($this->randString().$component->getAction());
			$areaIds[$item['ID']] = $this->GetEditAreaId($uniqueId);
			$this->AddEditAction($uniqueId, $item['EDIT_LINK'], $elementEdit);
			$this->AddDeleteAction($uniqueId, $item['DELETE_LINK'], $elementDelete, $elementDeleteParams);
			$strMainID = $this->GetEditAreaId($item['ID']);
?>
              <div class="calc__item" id="<?echo $areaIds[$item['ID']];?>">
                <div class="calc__item-header">
                  <div class="calc__item-name"><?=$item['NAME']?>
				  <?if($item['PROPERTIES']['SPECIAL_OFFER']['~VALUE']!=""):?>
					<a class="calc__item-arrow js-toggle" data-target="#tariff<?=$item['ID']?>">
                      <svg class="icon">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/sprite/sprite.symbol.svg?v=1507037035922#icon-arrow-left"></use>
                      </svg>
					</a>
				  <?endif;?>
                  </div>
                  <div class="u-text-center">
				     <?if($item['PROPERTIES']['SPEED']['VALUE']!=""):?><div class="quantity speed"><?=$item['PROPERTIES']['SPEED']['VALUE']?> <?=$item['PROPERTIES']['UNIT']['VALUE']?></div><?endif;?>
                       <div class="quantity">
					 <?if($item['PROPERTIES']['CHANNEL_DG']['VALUE']!=""):?>
                          <div class="quantity__value"><a href="/channels.php?id=<?=$item['ID']?>&type=2" class="popup_bl"><?=$item['PROPERTIES']['CHANNEL_DG']['VALUE']?></a></div>
                     <?endif;?>
					 <?if($item['PROPERTIES']['ANALOG_TV']['VALUE']!=""):?>
                          <div class="quantity__value"><a href="/channels.php?id=<?=$item['ID']?>&type=1" class="popup_bl"><?=$item['PROPERTIES']['ANALOG_TV']['VALUE']?></a></div>
                     <?endif;?>
					 <?if($item['PROPERTIES']['CHANNEL_HD']['VALUE']!=""):?>
                          <div class="quantity__value"><a href="/channels.php?id=<?=$item['ID']?>&type=3" class="popup_bl"><?=$item['PROPERTIES']['CHANNEL_HD']['VALUE']?></a></div>
                     <?endif;?>
					   </div>
				  </div>
                  <div class="u-text-right u-nowrap">
						<?if($item['PROPERTIES']['OLD_PRICE']['VALUE']>0):?>
						  <?=$item['PROPERTIES']['PRICE']['VALUE']?>
						  <div class="price price--discount">
                             <div class="price__value"><?=$item['PROPERTIES']['OLD_PRICE']['VALUE']?></div>
                             <div class="price__suffix"><?=GetMessage('UNIT')?></div>
                          </div>
						<?else:?>
						  <div class="price">
                             <div class="price__value"><?=$item['PROPERTIES']['PRICE']['VALUE']?></div>
                             <div class="price__suffix"><?=GetMessage('UNIT')?></div>
						  </div>
						<?endif;?>
                    <div class="radio radio--alone">
                      <label>
                        <input type="radio"
                               name="tariff_radio"
                               value="<?=$item['PROPERTIES']['PRICE']['VALUE']?>"
                               data-group="<?= $arTariff["CONTRACT_GROUP"] ?>"
                               data-period="<?= $arTariff["CONTRACT_PERIOD"] ?>"
                               id="<?=$item['ID']?>"><i></i>
                      </label>
                    </div>
                  </div>
                </div>
				<?if($item['PROPERTIES']['SPECIAL_OFFER']['~VALUE']!=""):?>
                <div class="calc__item-desc" id="tariff<?=$item['ID']?>">
                  <p><?=$item['PROPERTIES']['SPECIAL_OFFER']['~VALUE']?></p>
                </div>
				<?endif;?>
<?
                 if($item['PROPERTIES']['PACKAGES']['VALUE']){
?>
                    <div class="tariffs__section module" id="package_bl">
                        <div class="tariffs__section-title h5">
						<?=GetMessage('PACKAGES')?>
						   <div class="right">
						        <span><?=GetMessage('CHECK_PACKAGES')?></span>
                                <div class="checkbox checkbox--alone checkbox--switcher">
                                    <label>
                                        <input type="checkbox" date="package_<?=$item['ID']?>" class="check_package" name="check_package[<?=$item['ID']?>]" value="<?=$item['ID']?>" disabled><i></i>
                                    </label>
                                 </div>
						   </div>
						</div>
                        <div class="tariffs__section-table">
                        <table class="u-nowrap">
                        <tbody>
<?
$arrFilter_P=array("ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y","IBLOCK_ID"=>24,"ID"=>$item['PROPERTIES']['PACKAGES']['VALUE']);
if($_SESSION['Lcity']['ID']>0){
   $arrFilter_P["PROPERTY_CITY"]=$_SESSION['Lcity']['ID'];
}
                     $res = CIBlockElement::GetList(Array("SORT"=>"asc"), $arrFilter_P, false, false, array("IBLOCK_ID","ID","NAME","PROPERTY_NAME_LV","PROPERTY_CHANNELS","PROPERTY_CHANNELS_LV","PROPERTY_SPEED","PROPERTY_PRICE","PROPERTY_PRICE","PROPERTY_OLD_PRICE","PROPERTY_CURRENCY"));
                     while($ob = $res->GetNextElement())
                     {
                        $arPack= $ob->GetFields();
						$arPackProps = $ob->GetProperties();
                        if(!$LangRu){
	                       $arPack['NAME']=$arPack['PROPERTY_NAME_LV_VALUE'];
						   $arPack['PROPERTY_CHANNELS_VALUE']=$arPack['PROPERTY_CHANNELS_LV_VALUE'];
                        }
?>
						<tr>
                           <td class="u-muted" style="width:40%;"><?=$arPack['NAME']?></td>
                           <td class="mdash u-muted u-nopad-x"><?=$arPack['PROPERTY_SPEED_VALUE']?></td>
                           <td>
						   <?if($arPackProps['CHANNELS_E']['VALUE']):?>
						      <a href="/channels.php?id=<?=$arPack['ID']?>&section=<?=$item['ID']?>" class="popup_bl"><span class="quantity"><?=$arPack['PROPERTY_CHANNELS_VALUE']?></span></a>
						   <?else:?>
                               <?=$arPack['PROPERTY_CHANNELS_VALUE']?> <?=GetMessage('KANAL')?>
						   <?endif;?>
						   </td>
                           <td style="width:30%;text-align:right">
                              <div class="price">
                                 <div class="price__value"><?=$arPack['PROPERTY_PRICE_VALUE']?><?if($arPack['PROPERTY_OLD_PRICE_VALUE']>0):?><div class="price price--discount"><div class="price__value"><?=$arPack['PROPERTY_OLD_PRICE_VALUE']?></div></div><?endif;?></div>
                                 <div class="price__suffix"><?=GetMessage('UNIT')?></div>
                              </div>
                            </td>
							<td class="right">
							   <div class="radio radio--alone">
							      <input type="hidden" name="name_package[<?=$item['ID']?>]" value="<?=$arPack['NAME']?>">
                                  <label><input date="<?=$item['ID']?>" type="checkbox" class="tariff_package" name="tariff_package[<?=$item['ID']?>][<?=$arPack['ID']?>]" value="<?=$arPack['PROPERTY_PRICE_VALUE']?>" disabled><i></i></label>
                               </div>
					        </td>
                        </tr>
<?
                     }
?>
                  </tbody></table>
                </div>
              </div>
<?
				 }
?>
              </div>
<?
	}
	foreach($arr_divice_section['DEVACE'] as $k=>$sect){
		 $arr_divice_section['DEVACE'][$k]['sale']=array_unique($arr_divice_section['DEVACE'][$k]['sale']);
		 $arr_divice_section['DEVACE'][$k]['rent']=array_unique($arr_divice_section['DEVACE'][$k]['rent']);
	}
?>
        </div>
<?
}
?>