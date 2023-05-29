<?

namespace Baza23;

class CatalogCompare {
    const MODULE = "compare";
    const PRODUCT_VARIABLE_ID = "id";

    const CATALOG_IBLOCK_CODE = "catalog";
    const CATALOG_COMPARE_LIST = "CATALOG_COMPARE_LIST";

    public static function psf_getProductIds() {
        $arRet = false;

        if (!empty($_SESSION[self::CATALOG_COMPARE_LIST])) {
            $iblockId = \Baza23\Site::psf_getIBlockId(self::CATALOG_IBLOCK_CODE);
            $arRet = $_SESSION[self::CATALOG_COMPARE_LIST][$iblockId]['ITEMS'];
        }

        if (! is_array($arRet)) $arRet = array();
        return $arRet;
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