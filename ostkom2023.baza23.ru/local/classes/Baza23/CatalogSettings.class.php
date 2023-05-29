<?
namespace Baza23;

class CatalogSettings {

    public static function psf_getPriceCodes() {
        return ["BASE"];
    }

    public static function psf_getSectionLevels() {
        $arRet = [];

        $level = 0;
        $arVars = \Baza23\Settings::psf_settings_get(
                'page--catalog', ['section','level-' . $level]);

        while (!empty($arVars)) {
            foreach ($arVars as $key => $arItem) {
                $arRet["LEVEL_" . $level][$key] = $arItem["PREVIEW_TEXT"];
            }

            $level ++;
            $arVars = \Baza23\Settings::psf_settings_get(
                    'page--catalog', ['section','level-' . $level]);
        }

        return $arRet;
    }

    public static function psf_getSection($p_arSettings = false) {
        $arRet = [
            "USE_CAROUSEL"     => "N",

            "SHOW_TITLE"       => "N",
            "TITLE"            => "",

            "SHOW_EMPTY"       => "N",
            "TEXT_EMPTY"       => "",

            "BUTTON_LAZY_LOAD" => \Baza23\Settings::psf_settings_getText("catalog--section","btn-lazy-load"),
            "PROCESS_DESCRIPTION_VIDEO" => \Baza23\Settings::psf_settings_getText("catalog--section","process-description-video"),
        ];
        if ($p_arSettings) $arRet = array_merge($arRet, $p_arSettings);
        return $arRet;
    }

    public static function psf_getElement($p_arSettings = false) {
        $arRet = [
            "USE_IMAGE_CAROUSEL"      => "Y",

            "SHOW_WATERMARK"          => "Y",

            "SHOW_PRICE_TOTAL"        => "Y",
            "SHOW_ECONOMY"            => \Baza23\Settings::psf_settings_getText("catalog--element","show-economy"),
            "ECONOMY_TEXT"            => \Baza23\Settings::psf_settings_getText("catalog--element","economy-text"),

            "SHOW_BUTTON_BUY_1_CLICK" => \Baza23\Settings::psf_settings_getText("catalog--element","show-btn-buy-1-click"),
            "BUTTON_BUY_1_CLICK"      => \Baza23\Settings::psf_settings_getText("catalog--element","btn-buy-1-click"),

            "IMAGE_DELIVERY_FREE"     => \Baza23\Settings::psf_icon_getImage("image-delivery-free"),
            //"CHARACTERISTICS" => \Baza23\Data::psf_getCharacteristics(),
        ];

        $showTabPane = (\Baza23\Settings::psf_settings_getText("catalog--element","show-tab-pane") == "Y");
        $arTabList = explode(',', \Baza23\Settings::psf_settings_getText("catalog--element","tab-order"));

        if ($showTabPane && !empty($arTabList)) {
            $arRet["TABS"] = [];

            $index = 0;
            foreach ($arTabList as $code) {
                $code = strtolower(trim($code));

                $arAttrs = \Baza23\Settings::psf_settings_get("catalog--element",["tabs",$code]);
                if (empty($arAttrs)) continue;

                foreach ($arAttrs as $key => $arItem) {
                    $arRet["TABS"][$code][$key] = $arItem["PREVIEW_TEXT"];
                }

                if ($arRet["TABS"][$code]['show'] == "Y") $index ++;
            }

            $arRet["SHOW_TAB_PANE"] = ($index > 0 ? "Y" : "N");
        }

        if ($p_arSettings) $arRet = array_merge($arRet, $p_arSettings);
        return $arRet;
    }

    public static function psf_getBillboard($p_arSettings = false) {
        $arRet = [
            "LAZY_LOAD_IMAGE" => "Y",
            "SHOW_WATERMARK"  => "N",
        ];
        if ($p_arSettings) $arRet = array_merge($arRet, $p_arSettings);
        return $arRet;
    }

    public static function psf_getItem($p_arSettings = false) {
        $arRet = [
            "SHOW_HOVER_PANEL" => "Y",
            "SHOW_PROPERTIES"  => "Y",
            "SHOW_EQUIPMENT"   => "N",
            "SHOW_PRICE_TOTAL" => "N",

            "SHOW_WATERMARK"   => "N",

            "PRICE_TOTAL_LABEL"  => \Baza23\Settings::psf_settings_getText("catalog--item","label-price-total"),
            "BUTTON_MINUS_TITLE" => \Baza23\Settings::psf_settings_getText("catalog--item","btn-minus-title"),
            "BUTTON_PLUS_TITLE"  => \Baza23\Settings::psf_settings_getText("catalog--item","btn-plus-title"),
        ];
        if ($p_arSettings) $arRet = array_merge($arRet, $p_arSettings);
        return $arRet;
    }

    public static function psf_getOffers($p_arSettings = false) {
        $arRet = [
            "SHOW_OFFERS" => "N",
        ];
        if ($p_arSettings) $arRet = array_merge($arRet, $p_arSettings);
        return $arRet;
    }

    public static function psf_getVideo($p_arSettings = false) {
        $arRet = [
            "SHOW_MODAL" => \Baza23\Settings::psf_settings_getText("section--video", "show-video-in-modal"),
        ];
        if ($p_arSettings) $arRet = array_merge($arRet, $p_arSettings);
        return $arRet;
    }

    public static function psf_getStickers($p_arSettings = false) {
        $arRet = [
            "USE_STICKERS"     => "Y",
        ];
        if ($p_arSettings) $arRet = array_merge($arRet, $p_arSettings);
        return $arRet;
    }

    public static function psf_getPrint($p_arSettings = false) {
        $arRet = [
            "USE_PRINT"   => "Y",
            "ICON_PRINT"   => \Baza23\Settings::psf_icon_getText("icon-print"),
            "BUTTON_PRINT" => \Baza23\Settings::psf_settings_getText("catalog--element","btn-print"),
        ];
        if ($p_arSettings) $arRet = array_merge($arRet, $p_arSettings);
        return $arRet;
    }

    public static function psf_getFavourites($p_arSettings = false) {
        $arRet = [
            "USE_FAVOURITES" => \Baza23\Settings::psf_settings_getText("catalog--defaults","use-favourites"),
            "BUTTON_ADD" => \Baza23\Settings::psf_settings_getText("catalog--favourites-item","btn-add"),
            "BUTTON_REMOVE" => \Baza23\Settings::psf_settings_getText("catalog--favourites-item","btn-remove"),
        ];
        if ($p_arSettings) $arRet = array_merge($arRet, $p_arSettings);
        return $arRet;
    }

    public static function psf_getCompare($p_arSettings = false) {
        $arRet = [
            "USE_COMPARE" => \Baza23\Settings::psf_settings_getText("catalog--defaults","use-compare"),
            //"ICON_EMPTY" => \Baza23\Settings::psf_icon_getText("icon-compare"),
            //"ICON_FILL" => \Baza23\Settings::psf_icon_getText("icon-compare-fill"),
            "BUTTON_ADD" => \Baza23\Settings::psf_settings_getText("catalog--compare-item","btn-add"),
            "BUTTON_REMOVE" => \Baza23\Settings::psf_settings_getText("catalog--compare-item","btn-remove"),

            "RESULT" => array(
                "PAGE_TITLE" => \Baza23\Settings::psf_page_getText(["compare","title"]),

                "SHOW_PROPERTY_FILTER" => "N",
                "TITLE_DISPLAY" => \Baza23\Settings::psf_settings_getText("catalog--compare-result","title-display"),
                "BUTTON_DISPLAY_ALL" => \Baza23\Settings::psf_settings_getText("catalog--compare-result","btn-display-all"),
                "BUTTON_DISPLAY_DIFFERENT" => \Baza23\Settings::psf_settings_getText("catalog--compare-result","btn-display-different"),
                "TITLE_PROPERTY_FILTER" => \Baza23\Settings::psf_settings_getText("catalog--compare-result","title-property-filter"),
                "SHOW_BUTTON_REMOVE_ALL" => \Baza23\Settings::psf_settings_getText("catalog--compare-result","show-btn-remove-all"),
                "BUTTON_REMOVE_ALL" => \Baza23\Settings::psf_settings_getText("catalog--compare-result","btn-remove-all"),
                "BUTTON_REMOVE" => \Baza23\Settings::psf_settings_getText("catalog--compare-result","btn-remove"),
                "EMPTY_TEXT" => \Baza23\Settings::psf_settings_getText("catalog--compare-result","empty-text"),
                "EQUIPMENT" => array(
                    "SHOW_EQUIPMENT" => \Baza23\Settings::psf_settings_getText("catalog--compare-result","show-equipment"),
                    "SHOW_INACTIVE" => "Y",
                    "SHOW_EMPTY" => "Y",
                    "SHOW_EMPTY_TEXT" => "N",
                ),
            ),
        ];
        if ($p_arSettings) $arRet = array_merge($arRet, $p_arSettings);
        return $arRet;
    }

    public static function psf_getProductStatuses($p_arSettings = false) {
        $arStatusList = \Baza23\Settings::psf_settings_all("catalog--product-statuses");
        foreach ($arStatusList as $code => $arItem) {
            unset($arStatusList[$code]["icon"]);

            foreach ($arItem as $key => $arValue) {
                if ($key == 'icon') continue;

                $arStatusList[$code][$key] = $arValue["PREVIEW_TEXT"];
            }
        }

        $arStatusList["in-basket"]["url"] = \Baza23\Settings::psf_getUrl("page-basket");

        $arRet = [
            "USE_STATUSES" => "Y",

            "CHECK_QUANTITY" => "Y",
            "LABEL_USE_STATUS_IN_BASKET" => "Y",
            "BUTTON_USE_STATUS_IN_BASKET" => "Y",
            "QUANTITY_USE_STATUS_IN_BASKET" => "Y",

            "STATUS_AVAILABLE_CODE" => "",
            "STATUS_IN_BASKET_CODE" => "",
            "STATUS_NOT_AVAILABLE_CODE" => "receipt",

            "STATUS_LIST" => $arStatusList,
        ];
        if ($p_arSettings) $arRet = array_merge($arRet, $p_arSettings);
        return $arRet;
    }

    public static function psf_getEquipment($p_arSettings = false) {
        $arRet = [
            "SHOW_EQUIPMENT" => "N",

            "SHOW_EMPTY" => "Y",
            "SHOW_EMPTY_TEXT" => "Y",
            "EMPTY_TEXT" => \Baza23\Settings::psf_settings_getText("catalog--equipment","empty-text"),
            "SHOW_INACTIVE" => "Y",
            "SHOW_TITLE" => "Y",
            "TITLE" => \Baza23\Settings::psf_settings_getText("catalog--equipment","title"),
        ];
        if ($p_arSettings) $arRet = array_merge($arRet, $p_arSettings);
        return $arRet;
    }

    public static function psf_getCarousel($p_arSettings = false) {
        $arRet = [
            //"ICON_PREV" => \Baza23\Settings::psf_icon_getText("icon-caro-prev"),
            //"ICON_NEXT" => \Baza23\Settings::psf_icon_getText("icon-caro-next"),
        ];
        if ($p_arSettings) $arRet = array_merge($arRet, $p_arSettings);
        return $arRet;
    }

    public static function psf_getSearch($p_arSettings = false) {
        $arRet = [
            "PAGE_TITLE" => \Baza23\Settings::psf_page_getText(["search","title"]),

            "TEXT_NOT_FOUND" => \Baza23\Settings::psf_settings_getText("catalog--search","text-not-found"),
            "TEXT_EMPTY"     => \Baza23\Settings::psf_settings_getText("catalog--search","text-empty"),
            "BUTTON_SEARCH"  => \Baza23\Settings::psf_settings_getText("catalog--search","btn-search"),
        ];
        if ($p_arSettings) $arRet = array_merge($arRet, $p_arSettings);
        return $arRet;
    }

    public static function psf_addWaterMark(&$p_arImage,
            $p_width = false, $p_height = false) {

        $width = false;
        if ($p_width) $width = $p_width;
        elseif ($p_arImage["WIDTH"]) $width = $p_arImage["WIDTH"];
        elseif ($p_arImage["width"]) $width = $p_arImage["width"];

        $height = false;
        if ($p_height) $height = $p_height;
        elseif ($p_arImage["HEIGHT"]) $height = $p_arImage["HEIGHT"];
        elseif ($p_arImage["height"]) $height = $p_arImage["height"];

        if ($width && $height) {
            if (isset($p_arImage["ID"])) {
                $image = $p_arImage["ID"];
            } else {
                $image = $p_arImage;
            }

            $arWImage = \CFile::ResizeImageGet(
                    $image,
                    ['width' => $width, 'height' => $height],
                    BX_RESIZE_IMAGE_PROPORTIONAL,
                    true,
                    [self::psf_icon_getWaterMark()],
                    false,
                    100
            );
            if ($arWImage["src"]) $p_arImage["SRC"] = $arWImage["src"];
        }
    }

    public static function psf_icon_getWaterMark() {
        $arWaterMark = [
            'name' => 'watermark',
            'position' => 'center',
            'type' => 'image',
            'size' => 'real',
            'file' => $_SERVER['DOCUMENT_ROOT'] . '/local/images/watermark-tula.png',
            'fill' => 'repeat',
        ];
        return $arWaterMark;
    }
}