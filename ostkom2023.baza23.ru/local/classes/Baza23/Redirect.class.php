<?

namespace Baza23;

class Redirect {
    public static function psf_redirect() {
        $iblockId = \Baza23\Site::psf_getIBlockId("redirect");
        if (!$iblockId || !\Bitrix\Main\Loader::includeModule('iblock')) return false;

        $redirect = array();

        $requestUri = $_SERVER['REQUEST_URI'];
        if ($requestUri != '/') {
            $dbElement = \CIBlockElement::GetList(
                Array(),
                Array(
                    "IBLOCK_ID" => $iblockId,
                    "ACTIVE" => "Y",
                    "PROPERTY_UP_URL_SOURCE" => $requestUri
                ),
                false,
                false,
                Array("NAME", "PROPERTY_UP_URL_SOURCE")
            );
            while ($arElement = $dbElement->fetch()) {
                $arElement["NAME"] = trim($arElement["NAME"]);
                /*
                if (substr($arElement["NAME"], -1) != '/'
                        && strpos($arElement["NAME"], '/?') === false) {
                    $arElement["NAME"] .= '/';
                }
                */

                $redirect[$arElement["PROPERTY_UP_URL_SOURCE_VALUE"]] = $arElement["NAME"];
            }
        }

        if (isset($redirect[$requestUri])) {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: " . $redirect[$requestUri]);
            exit();
        }
    }
}