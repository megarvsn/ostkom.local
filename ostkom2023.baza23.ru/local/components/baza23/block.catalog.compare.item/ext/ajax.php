<? if (!defined('PUBLIC_AJAX_MODE')) define('PUBLIC_AJAX_MODE', true);

if (!defined('SITE_ID') && !empty($_REQUEST['SITE_ID'])) {
    $siteId = $_REQUEST['SITE_ID'];
    if (ctype_alnum($siteId) && strlen($siteId) == 2) define('SITE_ID', $siteId);
}

// Load Bitrix classes
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

global $APPLICATION;

$action = ToUpper($_REQUEST[\Baza23\CatalogCompare::ACTION_VARIABLE]);

if ($action == "ADD_TO_COMPARE_LIST"
        || $action == "DELETE_FROM_COMPARE_LIST") {

    $APPLICATION->IncludeComponent(
        "bitrix:catalog.compare.list",
        "",
        Array(
            "AJAX_MODE" => "Y",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "N",
            "AJAX_OPTION_HISTORY" => "N",
            "IBLOCK_TYPE" => '',
            "IBLOCK_ID" => \Baza23\Site::psf_getIBlockId("catalog"),
            "DETAIL_URL" => "",
            "COMPARE_URL" => \Baza23\Settings::psf_getUrl("page-catalog-compare"),
            "NAME" => \Baza23\CatalogCompare::CATALOG_COMPARE_LIST,
            "ACTION_VARIABLE" => \Baza23\CatalogCompare::ACTION_VARIABLE,
            "PRODUCT_ID_VARIABLE" => \Baza23\CatalogCompare::PRODUCT_VARIABLE_ID,
            "POSITION_FIXED" => "N",
            "POSITION" => "",
        )
    );

    $arProductIds = \Baza23\CatalogCompare::psf_getProductIds();
    $arRes = array(
            "success" => true,
            "productIds" => array_keys($arProductIds),
    );

} elseif ($action == "GET_COMPARE_LIST") {
    $arProductIds = \Baza23\CatalogCompare::psf_getProductIds();
    $arRes = array(
            'success' => true,
            'productIds' => array_keys($arProductIds),
    );

} else {
    $arRes = array(
            'success' => false,
            'code' => 'ERROR_UNDEFINED_ACTION',
    );
}

$arRes["action"] = $action;

if (!$arRes['success'] && !$arRes['text'] && $arRes["code"]) {
    $arRes['text'] = \Baza23\CatalogCompare::psf_getErrorMessage($arRes["code"]);
}

$APPLICATION->RestartBuffer();
\Baza23\CatalogCompare::psf_jsonRespond($arRes);
die();