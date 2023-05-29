<?php

namespace Baza23;

class Data {

    public static function psf_getAllSortingTypes() {
        $arSortingTypes = \Baza23\DataUtils::psf_getAllElements(
                "product-sorting-types",
                false,
                false,
                ["PROPERTY_UP_IBLOCK_FIELD_CODE",
                 "PROPERTY_UP_IBLOCK_PROPERTY_CODE",
                 "PROPERTY_UP_ICON_SVG",
                 "PROPERTY_UP_ORDER",
                 "PROPERTY_UP_DEFAULT"]
        );
        if (empty($arSortingTypes)) return [];

        $allOrderEnums = \Baza23\DataUtils::psf_getPropertyEnum(
                "product-sorting-types", "UP_ORDER");
        $arOrderEnums = [];
        if (!empty($allOrderEnums)) {
            foreach ($allOrderEnums as $key => $arEnum) {
                $arOrderEnums[$arEnum["ID"]] = $key;
            }
        }

        $arRet = [];
        foreach ($arSortingTypes as $key => $arItem) {
            $field = $arItem["PROPERTY_UP_IBLOCK_FIELD_CODE_VALUE"];
            if (!$field) {
                $property = $arItem["PROPERTY_UP_IBLOCK_PROPERTY_CODE_VALUE"];
                if ($property) $field = "PROPERTY_" . $property;
            }

            $iconSvg = $arItem["PROPERTY_UP_ICON_SVG_VALUE"];
            if (is_array($iconSvg)) $iconSvg = $iconSvg["TEXT"];

            $order = $arOrderEnums[$arItem["PROPERTY_UP_ORDER_ENUM_ID"]];

            $arType = [
                "ID"   => $arItem["ID"],
                "CODE" => $arItem["CODE"],
                "NAME" => $arItem["NAME"],

                "FIELD_CODE" => $field,
                "ORDER"      => $order,
                "ICON_SVG"   => $iconSvg,

                "DEFAULT" => ($arItem["PROPERTY_UP_DEFAULT_VALUE"] == "Y" ? "Y" : "N")
            ];

            $arRet[$arType["ID"]] = $arType;
        }
        return $arRet;
    }

    public static function psf_getCharacteristics() {
        $arRet = [];
        $arCharacteristics = \Baza23\DataUtils::psf_getAllElements('product-properties');
        foreach ($arCharacteristics as $arItem) {
            $arRet[] = $arItem["CODE"];
        }
        return $arRet;
    }

    protected static $s_uf = false;
    protected static function psf_uf_get() {
        if (!self::$s_uf) {
            self::$s_uf = \Baza23\DataUtils::psf_hierarchy_findSectionUF('catalog',
                    ["UF_DESKTOP_PROPERTIES", "UF_MOBILE_PROPERTIES", "UF_SORTING_TYPES",
                     "UF_INSTRUCTIONS", "UF_CATALOGS", "UF_INFOGRAPHICS",
                     "UF_GUARANTEE", "UF_MONTAGE", "UF_FREE_DELIVERY"]);
        }
        return self::$s_uf;
    }

    public static function psf_uf_getDesktopProperties($p_sectionId, $p_sectionCode = false) {
        $arAll = self::psf_uf_get();
        if (!$p_sectionId && $p_sectionCode) $p_sectionId = $arAll["SECTION_CODES"][$p_sectionCode];
        if (!$p_sectionId) return false;

        $id = $arAll["UF_DESKTOP_PROPERTIES"]["SECTIONS"][$p_sectionId];
        $arIds = $arAll["UF_DESKTOP_PROPERTIES"]["VALUES"][$id];
        if (empty($arIds)) return false;

        $arAllElements = \Baza23\DataUtils::psf_getAllElements('product-properties');
        if (empty($arAllElements)) return false;

        $arRet = [];
        foreach ($arIds as $id) {
            if ($code = $arAllElements[$id]["CODE"]) $arRet[] = $code;
        }
        return $arRet;
    }

    public static function psf_uf_getMobileProperties($p_sectionId, $p_sectionCode = false) {
        $arAll = self::psf_uf_get();
        if (!$p_sectionId && $p_sectionCode) $p_sectionId = $arAll["SECTION_CODES"][$p_sectionCode];
        if (!$p_sectionId) return false;

        $id = $arAll["UF_MOBILE_PROPERTIES"]["SECTIONS"][$p_sectionId];
        $arIds = $arAll["UF_MOBILE_PROPERTIES"]["VALUES"][$id];
        if (empty($arIds)) return false;

        $arAllElements = \Baza23\DataUtils::psf_getAllElements('product-properties');
        if (empty($arAllElements)) return false;

        $arRet = [];
        foreach ($arIds as $id) {
            if ($code = $arAllElements[$id]["CODE"]) $arRet[] = $code;
        }
        return $arRet;
    }

    public static function psf_uf_getSortingTypeIds($p_sectionId, $p_sectionCode = false) {
        $arAll = self::psf_uf_get();
        if (!$p_sectionId && $p_sectionCode) $p_sectionId = $arAll["SECTION_CODES"][$p_sectionCode];
        if (!$p_sectionId) return false;

        $id = $arAll["UF_SORTING_TYPES"]["SECTIONS"][$p_sectionId];
        $arRet = $arAll["UF_SORTING_TYPES"]["VALUES"][$id];
        return $arRet;
    }

    public static function psf_uf_getGuaranteeIds($p_sectionId, $p_sectionCode = false) {
        $arAll = self::psf_uf_get();
        if (!$p_sectionId && $p_sectionCode) $p_sectionId = $arAll["SECTION_CODES"][$p_sectionCode];
        if (!$p_sectionId) return false;

        $id = $arAll["UF_GUARANTEE"]["SECTIONS"][$p_sectionId];
        $arRet = $arAll["UF_GUARANTEE"]["VALUES"][$id];
        return $arRet;
    }

    public static function psf_uf_getInstructionIds($p_sectionId, $p_sectionCode = false) {
        $arAll = self::psf_uf_get();
        if (!$p_sectionId && $p_sectionCode) $p_sectionId = $arAll["SECTION_CODES"][$p_sectionCode];
        if (!$p_sectionId) return false;

        $id = $arAll["UF_INSTRUCTIONS"]["SECTIONS"][$p_sectionId];
        $arRet = $arAll["UF_INSTRUCTIONS"]["VALUES"][$id];
        return $arRet;
    }

    public static function psf_uf_getCatalogIds($p_sectionId, $p_sectionCode = false) {
        $arAll = self::psf_uf_get();
        if (!$p_sectionId && $p_sectionCode) $p_sectionId = $arAll["SECTION_CODES"][$p_sectionCode];
        if (!$p_sectionId) return false;

        $id = $arAll["UF_CATALOGS"]["SECTIONS"][$p_sectionId];
        $arRet = $arAll["UF_CATALOGS"]["VALUES"][$id];
        return $arRet;
    }

    public static function psf_uf_getInfographicIds($p_sectionId, $p_sectionCode = false) {
        $arAll = self::psf_uf_get();
        if (!$p_sectionId && $p_sectionCode) $p_sectionId = $arAll["SECTION_CODES"][$p_sectionCode];
        if (!$p_sectionId) return false;

        $id = $arAll["UF_INFOGRAPHICS"]["SECTIONS"][$p_sectionId];
        $arRet = $arAll["UF_INFOGRAPHICS"]["VALUES"][$id];
        return $arRet;
    }

    public static function psf_uf_getMontageIds($p_sectionId, $p_sectionCode = false) {
        $arAll = self::psf_uf_get();
        if (!$p_sectionId && $p_sectionCode) $p_sectionId = $arAll["SECTION_CODES"][$p_sectionCode];
        if (!$p_sectionId) return false;

        $id = $arAll["UF_MONTAGE"]["SECTIONS"][$p_sectionId];
        $arRet = $arAll["UF_MONTAGE"]["VALUES"][$id];
        return $arRet;
    }

    public static function psf_uf_getFreeDelivery($p_sectionId, $p_sectionCode = false) {
        $arAll = self::psf_uf_get();
        if (!$p_sectionId && $p_sectionCode) $p_sectionId = $arAll["SECTION_CODES"][$p_sectionCode];
        if (!$p_sectionId) return false;

        $id = $arAll["UF_FREE_DELIVERY"]["SECTIONS"][$p_sectionId];
        $arRet = $arAll["UF_FREE_DELIVERY"]["VALUES"][$id];
        return $arRet;
    }

    public static function psf_stickers_getRecommendedId() {
        $arElements = \Baza23\DataUtils::psf_getElementsByCode(
                \Baza23\Site::psf_getIBlockId('product-stickers'),
                'recommend');
        $ret = false;
        if (!empty($arElements)) {
            $ret = reset($arElements);
        }
        return $ret;
    }
}