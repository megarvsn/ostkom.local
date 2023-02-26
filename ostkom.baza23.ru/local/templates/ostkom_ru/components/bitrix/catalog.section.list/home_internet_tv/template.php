<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
global $arrParams;
$arrParams=$arParams;
$arViewModeList = $arResult['VIEW_MODE_LIST'];

$arViewStyles = array(
	'LIST' => array(
		'CONT' => 'bx_sitemap',
		'TITLE' => 'bx_sitemap_title',
		'LIST' => 'bx_sitemap_ul',
	),
	'LINE' => array(
		'CONT' => 'bx_catalog_line',
		'TITLE' => 'bx_catalog_line_category_title',
		'LIST' => 'bx_catalog_line_ul',
		'EMPTY_IMG' => $this->GetFolder().'/images/line-empty.png'
	),
	'TEXT' => array(
		'CONT' => 'bx_catalog_text',
		'TITLE' => 'bx_catalog_text_category_title',
		'LIST' => 'bx_catalog_text_ul'
	),
	'TILE' => array(
		'CONT' => 'bx_catalog_tile',
		'TITLE' => 'bx_catalog_tile_category_title',
		'LIST' => 'bx_catalog_tile_ul',
		'EMPTY_IMG' => $this->GetFolder().'/images/tile-empty.png'
	)
);
$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
global $Item_num;
global $LangRu;
global $LangId;
__IncludeLang($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/ostkom/components/bitrix/catalog.section.list/home_internet_tv/lang/".$LangId."/template.php");
if(!$LangRu){
	$arResult['SECTION']['NAME']=$arResult['SECTION']['UF_NAME_LV'];
    $arResult['SECTION']['IPROPERTY_VALUES']['SECTION_PAGE_TITLE']=($arResult['SECTION']['UF_SEC_NAME_LV']!="")? $arResult['SECTION']['UF_SEC_NAME_LV'] : $arResult['SECTION']['UF_NAME_LV'];
    $arResult['SECTION']['~DESCRIPTION']=$arResult['SECTION']['~UF_TOP_TEXT_LV'];
	$arResult['SECTION']['UF_LIST_TITLE']=$arResult['SECTION']['UF_NAME_LIST_LV'];
	$arResult['SECTION']['~UF_LIST_TITLE']=$arResult['SECTION']['~UF_NAME_LIST_LV'];
	$arResult['SECTION']['~UF_TEXT_IN_BLOCK']=$arResult['SECTION']['~UF_TEXT_IN_BLOCK_LV'];
    $arResult['SECTION']['IPROPERTY_VALUES']['SECTION_META_TITLE']=$arResult['SECTION']['UF_TITLE_LV'];
    $arResult['SECTION']['IPROPERTY_VALUES']['SECTION_META_KEYWORDS']=$arResult['SECTION']['UF_SEC_KEYWORDS_LV'];
    $arResult['SECTION']['IPROPERTY_VALUES']['SECTION_META_DESCRIPTION']=$arResult['SECTION']['UF_SEC_DESC_LV'];
	$arResult['SECTION']['~UF_FORM_TEXT']=$arResult['SECTION']['~UF_FORM_TEXT_LV'];
    $arResult['SECTION']['UF_DESC_IP']=$arResult['SECTION']['UF_TEXT_IP_LV'];
	$arResult['SECTION']['UF_TOPBLOCK1']=$arResult['SECTION']['UF_TOPBLOCK1_LV'];
	$arResult['SECTION']['UF_TOPBLOCK2']=$arResult['SECTION']['UF_TOPBLOCK2_LV'];
	$arResult['SECTION']['UF_TOPBLOCK3']=$arResult['SECTION']['UF_TOPBLOCK3_LV'];
	$arResult['SECTION']['UF_TOPBLOCK4']=$arResult['SECTION']['UF_TOPBLOCK4_LV'];
}
?>
          <div class="hero-inet-tv module">
            <div class="hero-inet-tv__inner ug-grid">
              <div class="hero-inet-tv__image ug-col-phablet6 ug-col-desktop7"><img alt="<?=$arResult['SECTION']['NAME']?>" src="<?=CFile::GetPath($arResult['SECTION']['PICTURE'])?>"></div>
              <div class="hero-inet-tv__body ug-col-phablet6 ug-col-desktop5">
			  <?if($arResult['SECTION']['IPROPERTY_VALUES']['SECTION_PAGE_TITLE']):?>
	            <h1 class="hero-inet-tv__title h1"><?=$arResult['SECTION']['IPROPERTY_VALUES']['SECTION_PAGE_TITLE']?></h1>
              <?else:?>
                <h1 class="hero-inet-tv__title h1"><?=$arResult['SECTION']['NAME']?></h1>
			  <?endif;?>
			  <?if($arResult['SECTION']['UF_TOPBLOCK1']):?>
                    <div class="hero-tv__subtitle h3">
                         <?=$arResult['SECTION']['UF_TOPBLOCK1']?>
                    </div>
               <?endif;?>
				<?if($arResult['SECTION']['UF_TOPBLOCK2']):?>
                    <div class="purple_22">
                         <?=$arResult['SECTION']['UF_TOPBLOCK2']?>
                    </div>
               <?endif;?>
				<?if($arResult['SECTION']['UF_TOPBLOCK3']):?>
                    <div class="hero-tv__subtitle h3">
                         <?=$arResult['SECTION']['UF_TOPBLOCK3']?>
                    </div>
               <?endif;?>
				<?if($arResult['SECTION']['UF_TOPBLOCK4']):?>
                    <div class="purple_22">
                         <?=$arResult['SECTION']['UF_TOPBLOCK4']?>
                    </div>
               <?endif;?>
                    <?//=$arResult['SECTION']['~DESCRIPTION']?>
              </div>
            </div>
          </div>
<?
global $set_name;
global $keys;
global $arrParamsSection;
global $arrParams;
global $arrFilter_T;
global $arr_divice_section;
global $arr_ip_product;
global $arr_net_connect;
$arr_divice_section=array();
$arr_ip_product=array();
$arr_net_connect=array();
$arParams=$arrParamsSection;
if($_SESSION['Lcity']['ID']>0){
   $arrFilter_T["PROPERTY_CITY"]=$_SESSION['Lcity']['ID'];
}

?>
<?if($arResult['SECTION']['UF_LAYOUT']==2):?>
<?$intSectionID = $APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"telephony",
	array(
		"SECTION_ID" => $arResult['SECTION']['ID'],
		"SECTION_CODE" => $arResult['SECTION']["CODE"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
		"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
		"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
		"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
		"PROPERTY_CODE" => $arParams['DETAIL_PROPERTY_CODE'],
		"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
		"BROWSER_TITLE" => "-",
		"SET_LAST_MODIFIED" => $arParams[""],
		"SECTION_USER_FIELDS" => array("UF_IMAGE","UF_ADD_TEXT","UF_ANONS",""),
		"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
		"FILTER_NAME" => "arrFilter_T",
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_FILTER" => $arParams["CACHE_FILTER"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"SET_TITLE" => "N",
		"MESSAGE_404" => $arParams["MESSAGE_404"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"SHOW_404" => $arParams["SHOW_404"],
		"FILE_404" => $arParams["FILE_404"],
		"DISPLAY_COMPARE" => "N",
		"PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
		"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
		"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
		"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
		"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

		"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => $arParams["PAGER_TITLE"],
		"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
		"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
		"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
		"PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
		"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
		"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],

		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
		"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
		"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
		"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
		'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
		'CURRENCY_ID' => $arParams['CURRENCY_ID'],
		'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],

		'LABEL_PROP' => $arParams['LABEL_PROP'],
		'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
		'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],

		'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
		'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
		'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
		'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
		'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
		'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
		'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
		'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
		'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
		'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
		"ADD_SECTIONS_CHAIN" => "N",
		'ADD_TO_BASKET_ACTION' => $basketAction,
		'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
		'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare']
	),
	$component
);?>
<?elseif(count($arResult['SECTIONS'])>0):?>
<?$Item_num=0;?>
		  <form action="<?if($LangRu):?>/<?=$LangId?><?endif;?><?if($_SESSION['ServiceType']=="business"):?>/application/<?else:?><?if($arResult['address']['city']):?>/order/<?else:?>/verification/order/<?endif;?><?endif;?>" method="post" id="form_calculator">
          <div class="calc module">
		    <input type="hidden" name="tariff" value="">
		    <input type="hidden" name="tariff_group" value="">
		    <input type="hidden" name="tariff_period" value="">
            <input type="hidden" name="tariff_change" value="">
			<input type="hidden" name="section" value="<?=$arResult['SECTION']['ID']?>">
			<input type="hidden" name="stat_ip" value="">
            <input type="hidden" name="connect" value="">
            <input type="hidden" name="dev_ce" value="">
            <input type="hidden" name="dev_change" value="">
			<input type="hidden" name="summ_1" value="">
            <input type="hidden" name="summ_2" value="">
			<?if($arResult['address']):?>
			     <input type="hidden" name="city" value="<?=$arResult['address']['city']?>">
				 <input type="hidden" name="street" value="<?=$arResult['address']['street']?>">
				 <input type="hidden" name="home" value="<?=$arResult['address']['home']?>">
			<?endif;?>
            <div class="calc__title headline u-text-center h2"><?=$arResult['SECTION']['UF_LIST_TITLE']?></div>
            <div class="calc__body">
<?
	foreach ($arResult['SECTIONS'] as  $kn => &$arSection)
	{
       if(!$LangRu){$arSection['NAME']=$arSection['UF_NAME_LV'];}
        $set_name=($arSection['NAME']!="")? $arSection['NAME'] : "";
		$keys=($kn % 2);
		$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
		$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
?>
			<div id="S<?=$arSection['ID']?>"></div>
<?
 $intSectionID = $APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"home_internet_tv",
	array(
		"SECTION_ID" => $arSection["ID"],
		"SECTION_CODE" => $arSection["CODE"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
		"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
		"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
		"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
		"PROPERTY_CODE" => $arParams['DETAIL_PROPERTY_CODE'],
		"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
		"BROWSER_TITLE" => "-",
		"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
		"SECTION_USER_FIELDS" => array("UF_IMAGE","UF_ADD_TEXT","UF_ANONS",""),
		"INCLUDE_SUBSECTIONS" => "N",
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
		"FILTER_NAME" => "arrFilter_T",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_FILTER" => $arParams["CACHE_FILTER"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"SET_TITLE" => "N",
		"MESSAGE_404" => $arParams["MESSAGE_404"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"SHOW_404" => $arParams["SHOW_404"],
		"FILE_404" => $arParams["FILE_404"],
		"DISPLAY_COMPARE" => "N",
		"PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
		"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
		"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
		"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
		"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

		"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => $arParams["PAGER_TITLE"],
		"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
		"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
		"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
		"PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
		"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
		"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],

		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
		"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
		"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
		"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
		'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
		'CURRENCY_ID' => $arParams['CURRENCY_ID'],
		'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],

		'LABEL_PROP' => $arParams['LABEL_PROP'],
		'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
		'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],

		'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
		'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
		'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
		'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
		'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
		'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
		'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
		'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
		'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
		'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
		"ADD_SECTIONS_CHAIN" => "N",
		'ADD_TO_BASKET_ACTION' => $basketAction,
		'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
		'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],

        "U_PARAMS" => $arParams["~U_PARAMS"]
	),
	$component
);
}
if($Item_num==0){
?>
  <div id="NO_SERVICE"><?=GetMessage('NO_SERVICE')?></div>
<?
}
$arResult['SECTION']['UF_DEVICES']=array();
foreach($arr_divice_section['DEVACE'] as $k => $d){
	$arResult['SECTION']['UF_DEVICES'][]=$k;
}
   if(count($arResult['SECTION']['UF_DEVICES'])>0){
?>
	 <div id="devices">
        <div class="tariffs__section-title h5"><?=GetMessage('CT_DEVICES')?></div>
<?
   $rsSect = CIBlockSection::GetList(array('SORT' => 'asc'),array('IBLOCK_ID' => 18, 'ID' => $arResult['SECTION']['UF_DEVICES'],"ACTIVE" => "Y"),false,array("NAME","UF_NAME_LV","ID"));
   while ($arSect = $rsSect->GetNext())
   {
	  $rentpr=0;
	  $sellpr=0;
	  $ArDevices=array();
      $res = CIBlockElement::GetList(Array('SORT' => 'asc'), array('IBLOCK_ID' => 18, "ACTIVE" => "Y", 'SECTION_ID' => $arSect['ID'], '!PROPERTY_DISPLAY' => "39"), false, false, array("DETAIL_PICTURE","NAME","PROPERTY_NAME_LV","ID","PROPERTY_SELLING_PRICE","PROPERTY_RENT_PRICE","PROPERTY_OLD_SELLING_PRICE","PROPERTY_OLD_RENT_PRICE","PROPERTY_TYPE_PRICE","PROPERTY_TYPE_PRICE_RENT","PROPERTY_DISPLAY"));
      while($ob = $res->GetNextElement())
      {
         $ArDevices[] = $ob->GetFields();
      }
if(!$LangRu){
	$arSect['NAME']=$arSect['UF_NAME_LV'];
}
$class_sale="";
$class_rent="";
$class="";
$class_item=array();
foreach($arr_divice_section['ITEMS']['sale'] as $kn => $cn){
	foreach($cn as $cnn){
		$class_item['sale'][$cnn].=" item_sale_".$kn;
	}
}
foreach($arr_divice_section['ITEMS']['rent'] as $kn => $cn){
	foreach($cn as $cnn){
		$class_item['rent'][$cnn].=" item_rent_".$kn;
	}
}
foreach($arr_divice_section['DEVACE'][$arSect['ID']]['sale'] as $cn){
	$class_sale.=" sale".$cn;
	$class.=" sale_".$cn;
}
foreach($arr_divice_section['DEVACE'][$arSect['ID']]['rent'] as $cn){
	$class_rent.=" rent".$cn;
	$class.=" rent_".$cn;
}
?>
              <div class="calc__item block_device<?=$class?>" id="<?=$arSect['ID']?>">
                <div class="calc__item-header">
                  <div class="calc__item-name"><?=$arSect['NAME']?>
				  <?if(count($ArDevices)>0):?>
                    <a class="calc__item-arrow js-toggle" data-target="#router<?=$arSect['ID']?>">
                      <svg class="icon">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/sprite/sprite.symbol.svg?v=1507037035922#icon-arrow-left"></use>
                      </svg>
					</a>
				  <?endif;?>
                  </div>
                  <div class="u-text-center">
                    <div class="calc__toggle">
					  <?if($class_rent!=""):?>
			          <input type="hidden" name="oper_type[<?=$arSect['ID']?>]" value="rent">
					  <?elseif($class_sale!=""):?>
					   <input type="hidden" name="oper_type[<?=$arSect['ID']?>]" value="sale">
					  <?endif;?>
					  <?if($class_rent!=""):?>
                      <label class="calc__toggle-item but_rent<?=$class_rent?>">
                        <input <?if($class_sale!=""):?>type="radio"<?else:?>type="hidden"<?endif;?> class="router_buy" name="router_buy[<?=$arSect['ID']?>]" value="rent" checked >
						<?if($class_sale!=""):?>
						<span class="calc__toggle-text active"><?=GetMessage('RENT')?></span>
						<?endif;?>
                      </label>
					  <?endif;?>
					  <?if($class_sale!=""):?>
                      <label class="calc__toggle-item but_sale<?=$class_sale?>">
                        <input <?if($class_rent!=""):?>type="radio"<?else:?>type="hidden"<?endif;?> class="router_buy" name="router_buy[<?=$arSect['ID']?>]" value="sale"<?if($class_rent!=""):?> checked<?endif;?>>
						<?if($class_rent!=""):?>
						<span class="calc__toggle-text"><?=GetMessage('BUY')?></span>
						<?endif;?>
                      </label>
					  <?endif;?>
                    </div>
                  </div>
                  <div class="u-text-right u-nowrap">
                    <div class="price">
                      <div class="price__value">0</div>
                      <div class="price__suffix" id="SUM_rent"><?=GetMessage('TOTAL_SUM')?></div>
                      <div class="price__suffix hidden" id="SUM_sale"><?=GetMessage('TOTAL_SUM_A')?></div>
                    </div>
                    <div class="checkbox checkbox--alone checkbox--switcher">
                      <label>
                        <input checked="checked" type="checkbox" class="devices_val" name="router_price[<?=$arSect['ID']?>]" value="0"><i></i>
                      </label>
                    </div>
                  </div>
                </div>
				<?if(count($ArDevices)>0):?>
                <div class="calc__item-desc" id="router<?=$arSect['ID']?>">
                  <div class="calc__devices">
                    <?foreach($ArDevices as $k=>$dev):?>
<?
if(!$LangRu){
	$dev['NAME']=$dev['PROPERTY_NAME_LV_VALUE'];
}
?>
                    <div class="calc__device<?=$class_item['sale'][$dev['ID']]?><?=$class_item['rent'][$dev['ID']]?>">
                      <div class="radio radio--alone radio--switcher calc__device-radio">
<?
$dev['PROPERTY_SELLING_PRICE_VALUE']= ($dev['PROPERTY_SELLING_PRICE_VALUE']>0)? $dev['PROPERTY_SELLING_PRICE_VALUE'] : 0;
$dev['PROPERTY_RENT_PRICE_VALUE']=($dev['PROPERTY_RENT_PRICE_VALUE']>0)? $dev['PROPERTY_RENT_PRICE_VALUE'] : 0;
?>
					    <input type="hidden" value="<?=$dev['PROPERTY_SELLING_PRICE_VALUE']?>" id="sale_<?=$dev['ID']?>">
					    <input type="hidden" value="<?=$dev['PROPERTY_RENT_PRICE_VALUE']?>" id="rent_<?=$dev['ID']?>">
                        <label>
                          <input type="radio" name="device[<?=$arSect['ID']?>]" value="<?=$dev['ID']?>"><i></i>
                        </label>
                      </div><img class="calc__device-img" src="<?=CFile::GetPath($dev['DETAIL_PICTURE'])?>" alt="<?=$dev['NAME']?>">
                      <div class="calc__device-name"><?=$dev['NAME']?></div>
                    </div>
                    <?endforeach;?>
                  </div>
                </div>
				<?endif;?>
              </div>
   <?
    }
   ?>
    </div>
   <?
    }
   ?>
<?
   $class_ip="";
   $class_text="";
   foreach($arr_ip_product as $k => $ip){
	   $class_ip.=" ip_".$k;
       if(!$LangRu) $ip['TEXT']= $ip['TEXT_LV'];
	   if($ip['TEXT']!="") $class_ip_text.=" ip_".$k;
   }
?>
   <?if(count($arr_ip_product)>0):?>
              <div class="calc__item stat_ip<?=$class_ip?>">
                <div class="calc__item-header">
                  <div class="calc__item-name"><?=GetMessage('IP')?>
					<a class="calc__item-arrow js-toggle js-toggle-ip<?=$class_ip?>" data-target="#static-ip">
                      <svg class="icon">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/sprite/sprite.symbol.svg?v=1507037035922#icon-arrow-left"></use>
                      </svg>
					</a>
                  </div>
                  <div class="u-text-right u-nowrap">
                    <div class="price">
                      <div class="price__value">0</div>
                      <div class="price__suffix"><?=GetMessage('TOTAL_SUM')?></div>
                    </div>
                    <div class="checkbox checkbox--alone checkbox--switcher">
                      <label>
                        <input type="checkbox" name="ip" value="0"><i></i>
                      </label>
                    </div>
                  </div>
                </div>
                <!-- desc-->
                <div class="calc__item-desc static-ip-desc" id="static-ip">
                  <p></p>
                </div>
              </div>
	<?endif;?>
<?
$class_connect=array();
$connects_class="";
foreach ($arr_net_connect as $k => $connect) {
    $arResult['SECTION']['UF_CONNECT'][] = $k;
    foreach ($connect as $c) {
        $class_connect[$k] .= " connect_" . $c;
        $connects_class .= " connect_title_" . $c;
    }
}
if(count($arr_net_connect)>0):
    $connects=array();

    $res = CIBlockElement::GetList(
        Array("SORT"=>"ASC"),
        Array("ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "IBLOCK_ID"=>21, "ID" => $arResult['SECTION']['UF_CONNECT']),
        false,
        false,
        array("NAME","ID","PROPERTY_TEXT","PROPERTY_TEXT_LV","PROPERTY_PRICE","PROPERTY_CONTRACT_PERIOD")
    );
    while($ob = $res->GetNextElement())
    {
       $arFields = $ob->GetFields();
	   $connects[]=$arFields;
    }
?>
            <div class="calc__details">
			  <div class="wp_calc__details">
              <!-- table-->
              <table class="calc__details-table" id="connect_block">
			    <tr class="connects_title_bl <?=$connects_class?>">
                  <td colspan=2 class="title_connect"><?=GetMessage('CONNECT')?></td>
				</tr>
   <?foreach($connects as $k=>$connect):
        if(!$LangRu){
            $connect['PROPERTY_TEXT_VALUE']=$connect['PROPERTY_TEXT_LV_VALUE'];
        }
?>
                <tr class="connect_item<?=$class_connect[$connect['ID']]?>">
                  <td><?=$connect['PROPERTY_TEXT_VALUE']?></td>
                  <td class="u-text-right u-nowrap">
                    <div class="price">
                      <div class="price__value"><?=$connect['PROPERTY_PRICE_VALUE']?></div>
                      <div class="price__suffix">€</div>
                    </div>
                    <div class="radio radio--alone">
                      <label>
                        <input type="radio"
                               name="detail_radio_connect"
                               id="<?=$connect['ID']?>"
                               value="<?=$connect['PROPERTY_PRICE_VALUE']?>"
                               data-group=""
                               data-period="<?= $connect["PROPERTY_CONTRACT_PERIOD_VALUE"] ?>"><i></i>
                      </label>
                    </div>
                  </td>
                </tr>
	<?endforeach;?>
              </table>
              <div class="calc__details-action">
                <div class="price u-size-h1">
				  <span><?=GetMessage('MONTHLY_PAYMENT')?>: </span>
                  <div class="price__value" id="total_sum">0</div>
                  <div class="price__suffix"><?=GetMessage('TOTAL_SUM')?></div>
                </div>
              </div>
              <div class="calc__details-action">
                <div class="price u-size-h1">
				  <span><?=GetMessage('ONE-TIME_MONTHLY_PAYMENT')?>: </span>
                  <div class="price__value" id="total_sum_1">0</div>
                  <div class="price__suffix"><?=GetMessage('TOTAL_SUM_A')?></div>
                </div>
              </div><br>
			  <input type="submit" class="btn btn--primary" value="<?if($_SESSION['ServiceType']=="business"):?><?=GetMessage('ORDER_FORM')?><?else:?><?=GetMessage('ORDER')?><?endif;?>">
             </div>
            </div>
<?endif;?>
            </div>
          </div>
		  </form>
         <script src="<?=SITE_TEMPLATE_PATH?>/scripts/calculator.js"></script>
<?endif;

$arr_gift=array();
if(count($arr_divice_section['GIFT'])>0){
	foreach($arr_divice_section['GIFT'] as $k => $gift){

		$arr_gift[]=$k;
	}
global $arrFilter;
$arrFilter['ID']=$arr_gift;
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"devices",
	array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"COMPONENT_TEMPLATE" => "devices",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_TEXT",
			2 => "PREVIEW_PICTURE",
			3 => "",
		),
		"FILTER_NAME" => "arrFilter",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "18",
		"IBLOCK_TYPE" => "devices",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "N",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "20",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "HTML_STANDART",
			2 => "TYPE_PRICE_RENT",
			3 => "TYPE_PRICE",
			4 => "OLD_RENT_PRICE",
			5 => "OLD_SELLING_PRICE",
			6 => "RENT_PRICE",
			7 => "SELLING_PRICE",
			8 => "",
		),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N"
	),
	false
);
}
?>
<?
if($arResult['SECTION']['~UF_TEXT_IN_BLOCK']!=""):
?>
          <div class="benefit benefit--centered module">
            <div class="benefit__inner">
                <?=$arResult['SECTION']['~UF_TEXT_IN_BLOCK']?>
            </div>
          </div>
<?endif;?>
<?if($arResult['SECTION']['UF_FORM']==1):?>
          <div class="more-info section section--fullwidth section--compact module">
            <div class="more-info__title headline u-text-center">
			<div class="h5"><?=$arResult['SECTION']['~UF_FORM_TEXT']?></div></div>
<?
global $sevice_name;
$sevice_name=$arResult['SECTION']['NAME'];
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:form.result.new",
	"service_order",
	array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "N",
		"CHAIN_ITEM_LINK" => "",
		"CHAIN_ITEM_TEXT" => "",
		"EDIT_URL" => "",
		"IGNORE_CUSTOM_TEMPLATE" => "N",
		"LIST_URL" => "",
		"SEF_MODE" => "N",
		"SUCCESS_URL" => "",
		"USE_EXTENDED_ERRORS" => "N",
		"WEB_FORM_ID" => "3",
		"COMPONENT_TEMPLATE" => "service_order",
		"VARIABLE_ALIASES" => array(
			"WEB_FORM_ID" => "WEB_FORM_ID",
			"RESULT_ID" => "RESULT_ID",
		),
		"AJAX_MODE" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_SHADOW" => "N",
		"AJAX_OPTION_STYLE" => "N",
	),
	false
);?>
          </div>
<?
endif;
if($arResult['SECTION']['UF_ADVAN']>0){
    $advantages_id=$arResult['SECTION']['UF_ADVAN'];
    $APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"advantages_business",
	array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => 15,
		"IBLOCK_TYPE" => "content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "N",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "20",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => $advantages_id,
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(
			0 => "IMAGE_ICON",
			1 => "",
		),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "ACTIVE_FROM",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "DESC",
		"STRICT_SECTION_CHECK" => "N",
		"COMPONENT_TEMPLATE" => "advantages_business"
	),
	false
   );
}
?>
<script type="text/javascript">
	var arr_ip={};
  <?foreach($arr_ip_product as $k => $ip):?>
 <?if($ip['SUM'] OR $ip['SUM']=="0"):?>
 <?
   if($ip['SUM']==0) $ip['SUM']="0";
 ?>
	arr_ip[<?=$k?>]={"sum":"<?=$ip['SUM']?>","text":"<?=(!$LangRu)? $ip['TEXT_LV'] : $ip['TEXT']?>"};
 <?endif;?>
  <?endforeach;?>
	$( document ).ready(function() {
		var loc = window.location.hash.replace("#","");
		if(loc != "")
			jQuery.scrollTo('#'+loc,300, {offset	:{left: 0, top:-110}});
	});
</script>