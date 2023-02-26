<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

?><script>let jsv_tariffs = <?= CUtil::PhpToJSObject($arParams["~U_PARAMS"]["TARIFFS"]) ?>;</script><?

Bitrix\Main\Loader::includeModule('iblock');

$arFilter = array(
    'IBLOCK_ID' => $arParentSection['IBLOCK_ID'],
    '>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],
    '<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],
    '>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL']
); // выберет потомков без учета активности

$rsSect = CIBlockSection::GetList(
    Array("SORT"=>"ASC"),
    Array(
        "IBLOCK_ID" => $arParams['IBLOCK_ID'],
        "CODE" => $arResult['VARIABLES']['SECTION_CODE']
    ),
    false,
    Array("ID","UF_TRUE_SERV")
);
$arSect = $rsSect->GetNext();

global $arrParamsSection;
$arrParamsSection=$arParams;

$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list",
	"home_internet_tv",
	array(
		"ADD_SECTIONS_CHAIN" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "N",
		"COUNT_ELEMENTS" => "N",
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"IBLOCK_TYPE" => "services",
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"SECTION_FIELDS" => array(
			0 => "ID",
			1 => "CODE",
			2 => "XML_ID",
			3 => "NAME",
			4 => "SORT",
			5 => "DESCRIPTION",
			6 => "PICTURE",
			7 => "DETAIL_PICTURE",
			8 => "IBLOCK_TYPE_ID",
			9 => "IBLOCK_ID",
			10 => "IBLOCK_CODE",
			11 => "IBLOCK_EXTERNAL_ID",
			12 => "DATE_CREATE",
			13 => "CREATED_BY",
			14 => "TIMESTAMP_X",
			15 => "MODIFIED_BY",
			16 => "",
		),
		"SECTION_ID" => $arSect["ID"],
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "UF_IMAGE",
			2 => "UF_ADD_TEXT",
			3 => "UF_ANONS",
			4 => "UF_LIST_TITLE",
			5 => "UF_DEVICES",
			6 => "UF_IP",
			7 => "UF_DESC_IP",
			8 => "UF_DEV_GIFT",
			9 => "UF_CONNECT",
			10 => "UF_LAYOUT",
			11 => "UF_FEATURES",
			12 => "UF_ADD_TV",
			13 => "UF_TEXT_IN_BLOCK",
			14 => "UF_ADVAN",
			15 => "UF_FORM_TEXT",
			16 => "UF_FORM",
			17 => "UF_SEC_NAME_LV",
			18 => "UF_SEC_DESC_LV",
			19 => "UF_SEC_KEYWORDS_LV",
			20 => "UF_TITLE_LV",
			21 => "UF_ANONS_TEXT_LV",
			22 => "UF_ANONS_TITLE_LV",
			23 => "UF_TOP_TEXT_LV",
			24 => "UF_TEXT_IP_LV",
			25 => "UF_NAME_LIST_LV",
			26 => "UF_NAME_LV",
			27 => "UF_FORM_TEXT_LV",
			28 => "UF_TEXT_IN_BLOCK_LV",
			29 => "UF_TEXT_IP_LV",
			30 => "UF_TOPBLOCK1",
			31 => "UF_TOPBLOCK2",
			32 => "UF_TOPBLOCK3",
			33 => "UF_TOPBLOCK4",
			34 => "UF_TOPBLOCK1_LV",
			35 => "UF_TOPBLOCK2_LV",
			36 => "UF_TOPBLOCK3_LV",
			37 => "UF_TOPBLOCK4_LV",
		),
		"SHOW_PARENT_NAME" => "Y",
		"TOP_DEPTH" => "1",
		"VIEW_MODE" => "LINE",
		"COMPONENT_TEMPLATE" => "home_internet_tv",

        "U_PARAMS" => $arParams["~U_PARAMS"]
	),
	false
);

// offers
global $arrFilter;

$arrFilter=array();
if($arParams['IBLOCK_ID']==25) $arrFilter=Array("PROPERTY_BUSINESS" => $arSect['ID']);
else $arrFilter=Array("PROPERTY_HOME" => $arSect['ID']);

if($_SESSION['Lcity']['ID']>0){
   $arrFilter["PROPERTY_CITY"]=$_SESSION['Lcity']['ID'];
}

$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"actions",
	array(
		"ACTIVE_DATE_FORMAT" => "j F Y",
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
		"DETAIL_URL" => "/actions//#ELEMENT_CODE#/",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_NAME" => "arrFilter",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "2",
		"IBLOCK_TYPE" => "actions",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "N",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "2",
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
			0 => "NAME_LV",
			1 => "ANONS_LV",
			2 => "",
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
		"STRICT_SECTION_CHECK" => "N",
		"COMPONENT_TEMPLATE" => "actions"
	),
	false
);

if ($arSect["UF_TRUE_SERV"] > 0) {
    $APPLICATION->IncludeComponent(
        "bitrix:news.detail",
        "true_service",
        array(
            "ACTIVE_DATE_FORMAT" => "d.m.Y",
            "ADD_ELEMENT_CHAIN" => "N",
            "ADD_SECTIONS_CHAIN" => "N",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "BROWSER_TITLE" => "-",
            "CACHE_GROUPS" => "Y",
            "CACHE_TIME" => "36000000",
            "CACHE_TYPE" => "A",
            "CHECK_DATES" => "Y",
            "DETAIL_URL" => "",
            "DISPLAY_BOTTOM_PAGER" => "N",
            "DISPLAY_DATE" => "N",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "N",
            "DISPLAY_PREVIEW_TEXT" => "Y",
            "DISPLAY_TOP_PAGER" => "N",
            "ELEMENT_CODE" => "",
            "ELEMENT_ID" => $arSect["UF_TRUE_SERV"],
            "FIELD_CODE" => array(
                0 => "ID",
                1 => "CODE",
                2 => "XML_ID",
                3 => "NAME",
                4 => "TAGS",
                5 => "SORT",
                6 => "PREVIEW_TEXT",
                7 => "PREVIEW_PICTURE",
                8 => "DETAIL_TEXT",
                9 => "DETAIL_PICTURE",
                10 => "DATE_ACTIVE_FROM",
                11 => "ACTIVE_FROM",
                12 => "DATE_ACTIVE_TO",
                13 => "ACTIVE_TO",
                14 => "SHOW_COUNTER",
                15 => "SHOW_COUNTER_START",
                16 => "IBLOCK_TYPE_ID",
                17 => "IBLOCK_ID",
                18 => "IBLOCK_CODE",
                19 => "IBLOCK_NAME",
                20 => "IBLOCK_EXTERNAL_ID",
                21 => "DATE_CREATE",
                22 => "CREATED_BY",
                23 => "CREATED_USER_NAME",
                24 => "TIMESTAMP_X",
                25 => "MODIFIED_BY",
                26 => "USER_NAME",
                27 => "",
            ),
            "IBLOCK_ID" => "19",
            "IBLOCK_TYPE" => "content",
            "IBLOCK_URL" => "",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
            "MESSAGE_404" => "",
            "META_DESCRIPTION" => "-",
            "META_KEYWORDS" => "-",
            "PAGER_BASE_LINK_ENABLE" => "N",
            "PAGER_SHOW_ALL" => "N",
            "PAGER_TEMPLATE" => ".default",
            "PAGER_TITLE" => "Страница",
            "PROPERTY_CODE" => array(
                0 => "TITLE",
                1 => "SVG",
                2 => "",
            ),
            "SET_BROWSER_TITLE" => "N",
            "SET_CANONICAL_URL" => "N",
            "SET_LAST_MODIFIED" => "N",
            "SET_META_DESCRIPTION" => "N",
            "SET_META_KEYWORDS" => "N",
            "SET_STATUS_404" => "N",
            "SET_TITLE" => "N",
            "SHOW_404" => "N",
            "STRICT_SECTION_CHECK" => "N",
            "USE_PERMISSIONS" => "N",
            "USE_SHARE" => "N",
            "COMPONENT_TEMPLATE" => "true_service"
        ),
        false
    );
}