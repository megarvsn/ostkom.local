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

$productID = 0;

$action = ToUpper($_REQUEST[\Baza23\CatalogFavourites::ACTION_VARIABLE]);

if ($action == "ADD_TO_FAVOURITE_LIST") {
    $found = true;
    if (isset($_REQUEST[\Baza23\CatalogFavourites::PRODUCT_VARIABLE_ID])) {
        $productID = (int) $_REQUEST[\Baza23\CatalogFavourites::PRODUCT_VARIABLE_ID];
    }

    if ($productID <= 0) {
        $arRes = array(
                'success' => false,
                'code' => 'ERROR_EMPTY_PRODUCT',
        );
    } elseif ($found) {
        $arRes = \Baza23\CatalogFavourites::psf_addProduct($productID);

    } else {
        $arRes = array(
                'success' => false,
                'code' => 'ERROR_PRODUCT_NOT_FOUND',
        );
    }

} elseif ($action == "DELETE_FROM_FAVOURITE_LIST") {
    $productID = 0;
    if (isset($_REQUEST[\Baza23\CatalogFavourites::PRODUCT_VARIABLE_ID])) {
        $productID = (int) $_REQUEST[\Baza23\CatalogFavourites::PRODUCT_VARIABLE_ID];
    }

    if ($productID <= 0) {
        $arRes = array(
                'success' => false,
                'code' => 'ERROR_EMPTY_PRODUCT',
        );
    } else {
        $arRes = \Baza23\CatalogFavourites::psf_removeProduct($productID);
    }

} elseif ($action == "GET_FAVOURITE_LIST") {
    $arProductIds = \Baza23\CatalogFavourites::psf_getProductIds();
    $arRes = array(
            'success' => true,
            'productIds' => $arProductIds,
    );

} elseif ($action == "SAVE_FAVOURITE_LIST") {
    $arRes = \Baza23\CatalogFavourites::psf_copyToUserField();

} else {
    $arRes = array(
            'success' => false,
            'code' => 'ERROR_UNDEFINED_ACTION',
    );
}

$arRes["action"] = $action;
$arRes['productId'] = $productID;

if (!$arRes['success'] && !$arRes['text'] && $arRes["code"]) {
    $arRes['text'] = \Baza23\CatalogFavourites::psf_getErrorMessage($arRes["code"]);
}

$APPLICATION->RestartBuffer();
\Baza23\CatalogFavourites::psf_jsonRespond($arRes);
die();