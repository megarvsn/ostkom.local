<?

namespace Baza23;

use Bitrix\Main\EventManager,
    Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc;

class CatalogFavourites {
    const MODULE = "favourites";
    const PRODUCT_VARIABLE_ID = "id";

    const CATALOG_IBLOCK_CODE = "catalog";
    const CATALOG_FAVOURITE_LIST = "CATALOG_FAVOURITE_LIST";

    const COOKIE_NAME = "CATALOG_FAVOURITE_LIST_1";
    const USER_FIELD_CODE = "UF_FAVOURITES";

    public function initCheckFavourites() {
        $EventManager = EventManager::getInstance();
        $EventManager->addEventHandler('main', 'OnAfterUserAuthorize', ["\Baza23\CatalogFavourites", "OnAfterUserAuthorize"]);
    }

    public static function OnAfterUserAuthorize($p_arUser) {
        unset($_SESSION[self::CATALOG_FAVOURITE_LIST]);

        $arRet = self::psf_loadCookie();
        if (!empty($arRet)) {
            self::psf_addProductList($arRet);
        }
        return true;
    }

    public static function psf_checkElements($p_iblockId) {
        $arFavourites = self::psf_getProductIds();
        if (empty($arFavourites)) return [];

        $arExistsElements = self::psf_findElements($p_iblockId, $arFavourites);
        if (count($arFavourites) == count($arExistsElements)) return $arFavourites;

        $arRet = array_keys($arExistsElements);
        self::psf_updateFavourites($arRet);
        return $arRet;
    }

    public static function psf_findElements($p_iblockId, $p_arProductIds) {
        if (empty($p_arProductIds)) return false;

        $arRet = false;
        if (Loader::includeModule('iblock')) {
            $arSelect = array(
                    "ID",
                    "IBLOCK_ID",
                    "IBLOCK_SECTION_ID",
                    "NAME",
            );
            $arFilter = array(
                    "ID" => $p_arProductIds,
                    "IBLOCK_ID" => $p_iblockId,
                    "IBLOCK_LID" => SITE_ID,
                    "IBLOCK_ACTIVE" => "Y",
                    "ACTIVE_DATE" => "Y",
                    "ACTIVE" => "Y",
                    "CHECK_PERMISSIONS" => "Y",
                    "MIN_PERMISSION" => "R"
            );
            $dbElements = \CIBlockElement::GetList(
                    array(),
                    $arFilter,
                    false,
                    ["nTopCount" => count($p_arProductIds)],
                    $arSelect
            );
            while ($arElement = $dbElements -> fetch()) {
                $arRet[$arElement["ID"]] = $arElement;
            }
        }
        return $arRet;
    }

    public static function psf_copyToUserField() {
        global $USER;
        if (! $USER -> IsAuthorized()) {
            return array("success" => true, "numberOfAddedItems" => 0);
        }

        $arCookieProductIds = self::psf_loadCookie();
        if (! is_array($arCookieProductIds) || empty($arCookieProductIds)) {
            return array("success" => true, "numberOfAddedItems" => 0);
        }

        $numberOfAddedItems = 0;

        self::psf_saveCookie(array());

        $idUser = $USER -> GetID();
        $dbUser = \CUser::GetByID($idUser);
        $arUser = $dbUser -> Fetch();
        $arUserProductIds = $arUser[self::USER_FIELD_CODE];
        if (! is_array($arUserProductIds) || empty($arUserProductIds)) {
            $arUserProductIds = $arCookieProductIds;
            $numberOfAddedItems = count($arCookieProductIds);
        } else {
            foreach ($arCookieProductIds as $productId) {
                if (! in_array($productId, $arUserProductIds)) {
                    $arUserProductIds[] = $productId;
                    $numberOfAddedItems ++;
                }
            }
        }

        $arRes = self::psf_updateFavourites($arUserProductIds);
        $arRes["numberOfAddedItems"] = $numberOfAddedItems;
        $arRes["productIds"] = $arUserProductIds;
        return $arRes;
    }

    public static function psf_getProductIds() {
        if (isset($_SESSION[self::CATALOG_FAVOURITE_LIST])) {
            $iblockId = \Baza23\Site::psf_getIBlockId(self::CATALOG_IBLOCK_CODE);
            $arRet = $_SESSION[self::CATALOG_FAVOURITE_LIST][$iblockId]['ITEMS'];
            return $arRet;
        }

        global $USER;
        if (! $USER -> IsAuthorized()) {
            $arRet = self::psf_loadCookie();
        } else {
            $idUser = $USER -> GetID();
            $dbUser = \CUser::GetByID($idUser);
            $arUser = $dbUser -> Fetch();
            $arRet = $arUser[self::USER_FIELD_CODE];
        }
        if (! is_array($arRet)) $arRet = array();
        return $arRet;
    }

    public static function psf_addProduct($p_productId) {
        $arProductIds = self::psf_getProductIds();
        if (in_array($p_productId, $arProductIds)) {
            $arRes = array(
                    'success' => false,
                    'code' => 'ERROR_PRODUCT_ALREADY_ADDED',
                    'productId' => $p_productId,
                    "productIds" => $arProductIds
            );
            return $arRes;
        }

        $arProductIds[] = $p_productId;
        $arRes = self::psf_updateFavourites($arProductIds);
        return $arRes;
    }

    public static function psf_addProductList($p_arProductIds) {
        if (empty($p_arProductIds)) return false;

        $count = 0;
        $arProductIds = self::psf_getProductIds();

        foreach ($p_arProductIds as $id) {
            if (in_array($id, $arProductIds)) continue;

            $arProductIds[] = $id;
            $count ++;
        }

        if ($count <= 0) {
            $arRes = array(
                    "success" => true,
                    'code' => 'ALL_PRODUCTS_IS_ALREADY_ADDED',
                    "productIds" => $p_arProductIds
            );
            return $arRes;
        }

        $arRes = self::psf_updateFavourites($arProductIds);
        $arRes["count"] = $count;
        return $arRes;
    }

    public static function psf_removeProduct($p_productId) {
        $arProductIds = self::psf_getProductIds();
        if (! in_array($p_productId, $arProductIds)) {
            $arRes = array(
                    'success' => false,
                    'code' => 'ERROR_PRODUCT_WAS_NOT_ADDED',
                    'productId' => $p_productId,
                    "productIds" => $arProductIds
            );
            return $arRes;
        }

        $key = array_search($p_productId, $arProductIds);
        unset($arProductIds[$key]);

        $arRes = self::psf_updateFavourites($arProductIds);
        return $arRes;
    }

    public static function psf_updateFavourites($p_arProductIds) {
        global $USER;

        $iblockId = \Baza23\Site::psf_getIBlockId(self::CATALOG_IBLOCK_CODE);
        $_SESSION[self::CATALOG_FAVOURITE_LIST][$iblockId]['ITEMS'] = $p_arProductIds;

        $arRes = array(
                "success" => true,
                "productIds" => $p_arProductIds
        );
        if ($USER -> IsAuthorized()) {
            $idUser = $USER -> GetID();

            $obj_user = new \CUser;
            if (!$obj_user -> Update($idUser, Array(self::USER_FIELD_CODE => $p_arProductIds))) {
                $arRes = array(
                        "success" => false,
                        'code' => 'ERROR_UPDATE_USER',
                        "error" => $obj_user -> LAST_ERROR
                );
            }
        } else {
            self::psf_saveCookie($p_arProductIds);
        }
        return $arRes;
    }

    protected static function psf_saveCookie($p_arProductIds) {
        \Baza23\Site::psf_saveCookie(self::COOKIE_NAME, serialize($p_arProductIds));
    }

    protected static function psf_loadCookie() {
        $ret = \Baza23\Site::psf_loadCookie(self::COOKIE_NAME);
        return unserialize($ret);
    }

    const ACTION_VARIABLE = "action";

    const MESSAGE_PREFIX = "U_AJAX_";
    protected $is_debug = false;

    public static function psf_jsonRespond($p_arReturn) {
        ob_start();
        header('Content-Type: application/json; charset=utf8');
        echo json_encode($p_arReturn);
        ob_end_flush();
        return true;
    }

    public static function psf_getErrorMessage($p_errorId) {
        $ret = Loc::getMessage(self::MESSAGE_PREFIX . $p_errorId);
        return $ret;
    }
}