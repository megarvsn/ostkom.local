<?php

namespace Baza23;

use Bitrix\Main\Application,
    Bitrix\Main\Data\Cache,
    Bitrix\Main\Loader;

class DataUtils {
    const DEFAULT_CACHE_ENABLED = "Y"; // "A"; "Y"; "N";

    public static function psf_basket_getProducts() {
        if (!Loader::includeModule("catalog")
                || !Loader::includeModule("sale")
                || !Loader::includeModule('currency')) return false;

        $arRet = [];

        $userId = \Bitrix\Sale\Fuser::getId();
        $siteId = \Bitrix\Main\Context::getCurrent()->getSite();
        $basket = \Bitrix\Sale\Basket::loadItemsForFUser($userId, $siteId);
        $baseCurrency = \Bitrix\Currency\CurrencyManager::getBaseCurrency();

        foreach ($basket as $basketItem) {
            $arRet[$basketItem->getProductId()] = [
                "BASKET_ID"      => $basketItem->getId(),
                "PRODUCT_ID"     => $basketItem->getProductId(),
                "PRICE"          => $basketItem->getPrice(),
                "PRICE_FORMATED" => \CCurrencyLang::CurrencyFormat($basketItem->getPrice(), $baseCurrency),
                "CURRENCY"       => $baseCurrency,
                "NAME"           => $basketItem->getField('NAME'),
                "QUANTITY"       => $basketItem->getField('QUANTITY'),
            ];
        }
        return $arRet;
    }

    public static function psf_basket_remove($p_arIds) {
        if (empty($p_arIds)
                || !Loader::includeModule("catalog")
                || !Loader::includeModule("sale")) return false;

        $ret = 0;
        $userId = \Bitrix\Sale\Fuser::getId();
        $siteId = \Bitrix\Main\Context::getCurrent()->getSite();
        $basket = \Bitrix\Sale\Basket::loadItemsForFUser($userId, $siteId);

        foreach ($p_arIds as $id) {
            $basketItem = $basket->getItemById($id);
            if ($basketItem == null) continue;

            $basketItem->delete();
            $ret ++;
        }
        $basket->save();
		return $ret;
	}

	public static function psf_getElementsByCode(
            $p_iblockCode, $p_needle, $p_cacheTime = false, $p_arFilter = false) {

        if (empty($p_needle)) return false;

        if (is_numeric($p_iblockCode)) $iblockId = IntVal($p_iblockCode);
        else $iblockId = Site::psf_getIBlockId($p_iblockCode);
        if (!$iblockId) return false;

        if (!self::psf_isCacheEnabled() || $p_cacheTime === 0) {
            if (Loader::includeModule('iblock')) {
                $arRet = self::psf_getElementsByCode_query($iblockId, $p_needle, $p_arFilter);
            }

        } else {
            $cacheId = 'iblock-' . $iblockId . '-' . $p_iblockCode . '_elems';
            if (!empty($p_arFilter)) $cacheId .= '_' . serialize($p_arFilter);
            $cacheTtl = ($p_cacheTime !== false ? $p_cacheTime : 31536000);
            $cachePath = '/iblock_' . $iblockId;

            $cache = Cache::createInstance();
            if ($cache->initCache($cacheTtl, $cacheId, $cachePath)) {
                $arRet = $cache->getVars();

            } elseif ($cache->startDataCache() && Loader::includeModule('iblock')) {
                $arRet = self::psf_getElementsByCode_query($iblockId, $p_needle, $p_arFilter);

                $cache_manager = Application::getInstance()->getTaggedCache();
                $cache_manager->startTagCache($cachePath);
                $cache_manager->registerTag('iblock_id_' . $iblockId);
                $cache_manager->endTagCache();

                $cache->endDataCache($arRet);
            }
        }
		return $arRet;
	}

	public static function psf_getElementsByCode_query(
            $p_iblockId, $p_needle, $p_arFilter = false) {

        if (empty($p_needle)) return false;

        $arRet = [];

        $arFilter = ["IBLOCK_ID" => $p_iblockId, "ACTIVE" => "Y", "=CODE" => $p_needle];
        if (!empty($p_arFilter)) $arFilter = array_merge($arFilter, $p_arFilter);

        $dbElements = \CIBlockElement::GetList(
            ["SORT" => "ASC", "NAME" => "ASC"],
            $arFilter,
            false,
            false,
            ["ID", "CODE"]
        );
        while ($arElement = $dbElements->Fetch()) {
            $arRet[$arElement["CODE"]] = $arElement["ID"];
        }
        return $arRet;
    }

	public static function psf_getAllElements(
            $p_iblockCode, $p_cacheTime = false, $p_arFilter = false, $p_arSelect = false) {

        if (is_numeric($p_iblockCode)) $iblockId = IntVal($p_iblockCode);
        else $iblockId = Site::psf_getIBlockId($p_iblockCode);
        if (!$iblockId) return false;

        if (!self::psf_isCacheEnabled() || $p_cacheTime === 0) {
            if (Loader::includeModule('iblock')) {
                $arRet = self::psf_getAllElements_query($iblockId, $p_arFilter, $p_arSelect);
            }

        } else {
            $cacheId = 'iblock-' . $iblockId . '-' . $p_iblockCode . '_elems';
            if (!empty($p_arFilter)) $cacheId .= '_' . serialize($p_arFilter);
            if (!empty($p_arSelect)) $cacheId .= '_' . serialize($p_arSelect);
            $cacheTtl = ($p_cacheTime !== false ? $p_cacheTime : 31536000);
            $cachePath = '/iblock_' . $iblockId;

            $cache = Cache::createInstance();
            if ($cache->initCache($cacheTtl, $cacheId, $cachePath)) {
                $arRet = $cache->getVars();

            } elseif ($cache->startDataCache() && Loader::includeModule('iblock')) {
                $arRet = self::psf_getAllElements_query($iblockId, $p_arFilter, $p_arSelect);

                $cache_manager = Application::getInstance()->getTaggedCache();
                $cache_manager->startTagCache($cachePath);
                $cache_manager->registerTag('iblock_id_' . $iblockId);
                $cache_manager->endTagCache();

                $cache->endDataCache($arRet);
            }
        }
		return $arRet;
	}

	protected static function psf_getAllElements_query(
            $p_iblockId, $p_arFilter = false, $p_arSelect = false) {

        $arRet = [];

        $arFilter = ["IBLOCK_ID" => $p_iblockId, "ACTIVE" => "Y"];
        if (!empty($p_arFilter)) $arFilter = array_merge($arFilter, $p_arFilter);

        $arSelect = ["IBLOCK_ID", "ID", "NAME", "CODE"];
        if (!empty($p_arSelect)) $arSelect = array_merge($arSelect, $p_arSelect);

        $dbElements = \CIBlockElement::GetList(
            ["SORT" => "ASC", "NAME" => "ASC"],
            $arFilter,
            false,
            false,
            $arSelect
        );
        if (in_array("DETAIL_PAGE_URL", $arSelect)) {
            while ($arElement = $dbElements->GetNext(false, false)) {
                self::psf_processElement($arElement);
                $arRet[$arElement["ID"]] = $arElement;
            }
        } else {
            while ($arElement = $dbElements->Fetch()) {
                self::psf_processElement($arElement);
                $arRet[$arElement["ID"]] = $arElement;
            }
        }
        return $arRet;
    }

	public static function psf_getSectionsByCode(
            $p_iblockCode, $p_needle, $p_cacheTime = false) {

        if (empty($p_needle)) return false;

        $arSections = self::psf_getAllSections($p_iblockCode, $p_cacheTime);
        if (empty($arSections)) return false;

        if (!is_array($p_needle)) $p_needle = [$p_needle];

        $arRet = [];
        foreach ($arSections as $id => $arItem) {
            if (in_array($arItem["CODE"], $p_needle)) $arRet[$arItem["CODE"]] = $id;
        }
		return $arRet;
    }

	public static function psf_countElements(
            $p_iblockCode, $p_cacheTime, $p_arFilter = false) {

        if (is_numeric($p_iblockCode)) $iblockId = IntVal($p_iblockCode);
        else $iblockId = Site::psf_getIBlockId($p_iblockCode);
        if (!$iblockId) return false;

        if (!self::psf_isCacheEnabled() || $p_cacheTime === 0) {
            if (Loader::includeModule('iblock')) {
                $arRet = self::psf_countElements_query($iblockId, $p_arFilter);
            }

        } else {
            $cacheId = 'iblock-' . $iblockId . '-' . $p_iblockCode . '_elems_count';
            if (!empty($p_arFilter)) $cacheId .= '_' . serialize($p_arFilter);
            $cacheTtl = ($p_cacheTime !== false ? $p_cacheTime : 31536000);
            $cachePath = '/iblock_' . $iblockId;

            $cache = Cache::createInstance();
            if ($cache->initCache($cacheTtl, $cacheId, $cachePath)) {
                $arRet = $cache->getVars();

            } elseif ($cache->startDataCache() && Loader::includeModule('iblock')) {
                $arRet = self::psf_countElements_query($iblockId, $p_arFilter);

                $cache_manager = Application::getInstance()->getTaggedCache();
                $cache_manager->startTagCache($cachePath);
                $cache_manager->registerTag('iblock_id_' . $iblockId);
                $cache_manager->endTagCache();

                $cache->endDataCache($arRet);
            }
        }
		return $arRet;
	}

	protected static function psf_countElements_query($p_iblockId, $p_arFilter = false) {
        $arFilter = ["IBLOCK_ID" => $p_iblockId, "ACTIVE" => "Y"];
        if (!empty($p_arFilter)) $arFilter = array_merge($arFilter, $p_arFilter);

        $count = \CIBlockElement::GetList(
            [],
            $arFilter,
            [],
            false,
            []
        );
        return $count;
    }

	public static function psf_getAllDates(
            $p_iblockCode, $p_cacheTime, $p_arFilter = false) {

        if (is_numeric($p_iblockCode)) $iblockId = IntVal($p_iblockCode);
        else $iblockId = Site::psf_getIBlockId($p_iblockCode);
        if (!$iblockId) return false;

        if (!self::psf_isCacheEnabled() || $p_cacheTime === 0) {
            if (Loader::includeModule('iblock')) {
                $arRet = self::psf_getAllDates_query($iblockId, $p_arFilter);
            }

        } else {
            $cacheId = 'iblock-' . $iblockId . '-' . $p_iblockCode . '_dates';
            if (!empty($p_arFilter)) $cacheId .= '_' . serialize($p_arFilter);
            $cacheTtl = ($p_cacheTime !== false ? $p_cacheTime : 31536000);
            $cachePath = '/iblock_' . $iblockId;

            $cache = Cache::createInstance();
            if ($cache->initCache($cacheTtl, $cacheId, $cachePath)) {
                $arRet = $cache->getVars();

            } elseif ($cache->startDataCache() && Loader::includeModule('iblock')) {
                $arRet = self::psf_getAllDates_query($iblockId, $p_arFilter);

                $cache_manager = Application::getInstance()->getTaggedCache();
                $cache_manager->startTagCache($cachePath);
                $cache_manager->registerTag('iblock_id_' . $iblockId);
                $cache_manager->endTagCache();

                $cache->endDataCache($arRet);
            }
        }
		return $arRet;
	}

    protected static function psf_getAllDates_query($p_iblockId, $p_arFilter = false) {
        $arRet = [];

        $arFilter = ["IBLOCK_ID" => $p_iblockId, "ACTIVE" => "Y"];
        if (!empty($p_arFilter)) $arFilter = array_merge($arFilter, $p_arFilter);

        $dbElements = \CIBlockElement::GetList(
            ["DATE_ACTIVE_FROM" => "DESC"],
            $arFilter,
            false,
            false,
            ["IBLOCK_ID", "ID", "DATE_ACTIVE_FROM"]
        );
        while ($arElement = $dbElements->Fetch()) {
            $key = ConvertDateTime($arElement["DATE_ACTIVE_FROM"], 'YYYY-m');
            if (!$arRet[$key]) {
                $arRet[$key] = FormatDateFromDB($arElement["DATE_ACTIVE_FROM"], 'f YYYY');
                //ConvertDateTime($arElement["DATE_ACTIVE_FROM"], 'f YYYY');
            }
        }
        return $arRet;
    }

	public static function psf_getProperty(
            $p_iblockCode, $p_propertyCode, $p_cacheTime = false, $p_arFilter = false) {

        if (is_numeric($p_iblockCode)) $iblockId = IntVal($p_iblockCode);
        else $iblockId = Site::psf_getIBlockId($p_iblockCode);
        if (!$iblockId || !$p_propertyCode) return false;

        if (!self::psf_isCacheEnabled() || $p_cacheTime === 0) {
            if (Loader::includeModule('iblock')) {
                $arRet = self::psf_getProperty_query($iblockId, $p_propertyCode, $p_arFilter);
            }

        } else {
            $cacheId = 'iblock-' . $iblockId . '-' . $p_iblockCode . '_prop-' . $p_propertyCode;
            if (!empty($p_arFilter)) $cacheId .= '_' . serialize($p_arFilter);
            $cacheTtl = ($p_cacheTime !== false ? $p_cacheTime : 31536000);
            $cachePath = '/iblock_' . $iblockId;

            $cache = Cache::createInstance();
            if ($cache->initCache($cacheTtl, $cacheId, $cachePath)) {
                $arRet = $cache->getVars();

            } elseif ($cache->startDataCache() && Loader::includeModule('iblock')) {
                $arRet = self::psf_getProperty_query($iblockId, $p_propertyCode, $p_arFilter);

                $cache_manager = Application::getInstance()->getTaggedCache();
                $cache_manager->startTagCache($cachePath);
                $cache_manager->registerTag('iblock_id_' . $iblockId);
                $cache_manager->endTagCache();

                $cache->endDataCache($arRet);
            }
        }
		return $arRet;
	}

	protected static function psf_getProperty_query(
            $p_iblockId, $p_propertyCode, $p_arFilter = false) {

        $arRet = [];

        $arFilter = ["IBLOCK_ID" => $p_iblockId, "ACTIVE" => "Y"];
        if (!empty($p_arFilter)) $arFilter = array_merge($arFilter, $p_arFilter);

        $property = "PROPERTY_" . $p_propertyCode . "_VALUE";

        $dbElements = \CIBlockElement::GetList(
            ["SORT" => "ASC", "NAME" => "ASC"],
            $arFilter,
            false,
            false,
            ["IBLOCK_ID", "ID", "PROPERTY_" . $p_propertyCode]
        );
        while ($arElement = $dbElements->Fetch()) {
            if (empty($arElement[$property])) continue;

            if (is_array($arElement[$property])) {
                foreach ($arElement[$property] as $value) {
                    if (!in_array($value, $arRet)) $arRet[] = $value;
                }
            } elseif (!in_array($arElement[$property], $arRet)) {
                $arRet[] = $arElement[$property];
            }
        }
        return $arRet;
    }

	public static function psf_getUserField(
            $p_iblockCode, $p_fieldCode, $p_cacheTime = false, $p_arFilter = false) {

        if (is_numeric($p_iblockCode)) $iblockId = IntVal($p_iblockCode);
        else $iblockId = Site::psf_getIBlockId($p_iblockCode);
        if (!$iblockId || !$p_fieldCode) return false;

        if (!self::psf_isCacheEnabled() || $p_cacheTime === 0) {
            if (Loader::includeModule('iblock')) {
                $arRet = self::psf_getUserField_query($iblockId, $p_fieldCode, $p_arFilter);
            }

        } else {
            $cacheId = 'iblock-' . $iblockId . '-' . $p_iblockCode . '_uf-' . $p_fieldCode;
            if (!empty($p_arFilter)) $cacheId .= '_' . serialize($p_arFilter);
            $cacheTtl = ($p_cacheTime !== false ? $p_cacheTime : 31536000);
            $cachePath = '/iblock_' . $iblockId;

            $cache = Cache::createInstance();
            if ($cache->initCache($cacheTtl, $cacheId, $cachePath)) {
                $arRet = $cache->getVars();

            } elseif ($cache->startDataCache() && Loader::includeModule('iblock')) {
                $arRet = self::psf_getUserField_query($iblockId, $p_fieldCode, $p_arFilter);

                $cache_manager = Application::getInstance()->getTaggedCache();
                $cache_manager->startTagCache($cachePath);
                $cache_manager->registerTag('iblock_id_' . $iblockId);
                $cache_manager->endTagCache();

                $cache->endDataCache($arRet);
            }
        }
		return $arRet;
	}

	protected static function psf_getUserField_query(
            $p_iblockId, $p_fieldCode, $p_arFilter = false) {

        $arRet = [];

        $arFilter = ["IBLOCK_ID" => $p_iblockId, "ACTIVE" => "Y"];
        if (!empty($p_arFilter)) $arFilter = array_merge($arFilter, $p_arFilter);

        $dbSections = \CIBlockSection::GetList(
            ["SORT" => "ASC", "NAME" => "ASC"],
            $arFilter,
            false,
            ["IBLOCK_ID", "ID", $p_fieldCode]
        );
        while ($arSection = $dbSections->Fetch()) {
            if (is_array($arSection[$p_fieldCode])) {
                foreach ($arSection[$p_fieldCode] as $value) {
                    if (!in_array($value, $arRet)) $arRet[] = $value;
                }
            } elseif (!in_array($arSection[$p_fieldCode], $arRet)) {
                $arRet[] = $arSection[$p_fieldCode];
            }
        }
        return $arRet;
    }

	public static function psf_getPropertyEnum(
            $p_iblockCode, $p_propertyCode, $p_cacheTime = false) {

        if (is_numeric($p_iblockCode)) $iblockId = IntVal($p_iblockCode);
        else $iblockId = Site::psf_getIBlockId($p_iblockCode);
        if (!$iblockId || !$p_propertyCode) return false;

        if (!self::psf_isCacheEnabled() || $p_cacheTime === 0) {
            if (Loader::includeModule('iblock')) {
                $arRet = self::psf_getPropertyEnum_query($iblockId, $p_propertyCode);
            }

        } else {
            $cacheId = 'iblock-' . $iblockId . '_prop-enum-' . $p_propertyCode;
            $cacheTtl = ($p_cacheTime !== false ? $p_cacheTime : 31536000);
            $cachePath = '/iblock_' . $iblockId;

            $cache = Cache::createInstance();
            if ($cache->initCache($cacheTtl, $cacheId, $cachePath)) {
                $arRet = $cache->getVars();

            } elseif ($cache->startDataCache() && Loader::includeModule('iblock')) {
                $arRet = self::psf_getPropertyEnum_query($iblockId, $p_propertyCode);

                $cache->endDataCache($arRet);
            }
        }
		return $arRet;
	}

	protected static function psf_getPropertyEnum_query($p_iblockId, $p_propertyCode) {
        $arRet = [];

        $dbPropEnums = \CIBlockPropertyEnum::GetList(
                ["SORT" => "ASC"],
                ["IBLOCK_ID" => $p_iblockId, "CODE" => $p_propertyCode]
        );
        while ($arPropEnum = $dbPropEnums->Fetch()) {
            $arRet[$arPropEnum["XML_ID"]] = $arPropEnum;
        }
		return $arRet;
    }

	public static function psf_getFirstElementId(
            $p_iblockCode, $p_arFilter, $p_cacheTime = false) {


        if (is_numeric($p_iblockCode)) $iblockId = IntVal($p_iblockCode);
        else $iblockId = Site::psf_getIBlockId($p_iblockCode);
        if (!$iblockId) return false;

        if (!self::psf_isCacheEnabled() || $p_cacheTime === 0) {
            if (Loader::includeModule('iblock')) {
                $ret = self::psf_getFirstElementId_query($iblockId, $p_arFilter);
            }

        } else {
            $cacheId = 'iblock-' . $iblockId . '-' . $p_iblockCode . '_element';
            if (!empty($p_arFilter)) $cacheId .= '_' . serialize($p_arFilter);
            $cacheTtl = ($p_cacheTime !== false ? $p_cacheTime : 7200);
            $cachePath = '/iblock_' . $iblockId;

            $cache = Cache::createInstance();
            if ($cache->initCache($cacheTtl, $cacheId, $cachePath)) {
                $ret = $cache->getVars();

            } elseif ($cache->startDataCache() && Loader::includeModule('iblock')) {
                $ret = self::psf_getFirstElementId_query($iblockId, $p_arFilter);

                $cache_manager = Application::getInstance()->getTaggedCache();
                $cache_manager->startTagCache($cachePath);
                $cache_manager->registerTag('iblock_id_' . $iblockId);
                $cache_manager->endTagCache();

                $cache->endDataCache($ret);
            }
        }
		return $ret;
	}

    protected static function psf_getFirstElementId_query($p_iblockId, $p_arFilter) {
        $ret = [];

        $arFilter = ["IBLOCK_ID" => $p_iblockId, "ACTIVE" => "Y"];
        if (!empty($p_arFilter)) $arFilter = array_merge($arFilter, $p_arFilter);

        $dbElements = \CIBlockElement::GetList(
            ["DATE_ACTIVE_FROM" => "DESC", "SORT" => "ASC", "ID" => "DESC"],
            $arFilter,
            false,
            ["nTopCount" => 1],
            ["ID", "NAME", "CODE", "DATE_ACTIVE_FROM"]
        );
        if ($arElement = $dbElements->Fetch()) {
            $ret = $arElement["ID"];
        }
		return $ret;
    }

    public static function psf_hierarchy_getParentSections(
            $p_iblockCode, $p_sectionCode, $p_cacheTime) {

        if (is_numeric($p_iblockCode)) $iblockId = IntVal($p_iblockCode);
        else $iblockId = Site::psf_getIBlockId($p_iblockCode);
        if (!$iblockId || !$p_sectionCode) return false;

        if (!self::psf_isCacheEnabled() || $p_cacheTime === 0) {
            if (Loader::includeModule('iblock')) {
                $ret = self::psf_hierarchy_getParentSections_query($iblockId, $p_sectionCode);
            }

        } else {
            $cacheId = 'iblock-' . $iblockId . '-' . $p_iblockCode . '_section-' . $p_sectionCode . '_hierarchy';
            if (!empty($p_arFilter)) $cacheId .= '_' . serialize($p_arFilter);
            $cacheTtl = ($p_cacheTime !== false ? $p_cacheTime : 31536000);
            $cachePath = '/iblock_' . $iblockId;

            $cache = Cache::createInstance();
            if ($cache->initCache($cacheTtl, $cacheId, $cachePath)) {
                $ret = $cache->getVars();

            } elseif ($cache->startDataCache() && Loader::includeModule('iblock')) {
                $ret = self::psf_hierarchy_getParentSections_query($iblockId, $p_sectionCode);

                $cache_manager = Application::getInstance()->getTaggedCache();
                $cache_manager->startTagCache($cachePath);
                $cache_manager->registerTag('iblock_id_' . $iblockId);
                $cache_manager->endTagCache();

                $cache->endDataCache($ret);
            }
        }
		return $ret;
    }

    protected static function psf_hierarchy_getParentSections_query(
            $p_iblockId, $p_sectionCode) {

        $arSection = false;

        $arFilter = [
            "IBLOCK_ID"     => $p_iblockId,
            "GLOBAL_ACTIVE" => "Y"
        ];

        if (is_numeric($p_sectionCode)) $arFilter["=ID"] = $p_sectionCode;
        else $arFilter["=CODE"] = $p_sectionCode;

        $dbSections = \CIBlockSection::GetList(
            [],
            $arFilter,
            false,
            ["ID", "DEPTH_LEVEL", "LEFT_MARGIN", "RIGHT_MARGIN"]
        );
        if ($arSection = $dbSections->Fetch()) {
        }
        if (empty($arSection)) return [];

        $arRet = [];
        $dbSections = \CIBlockSection::GetList(
            ['left_margin' => 'asc'],
            [
                'IBLOCK_ID'      => $p_iblockId,
                "<=LEFT_BORDER"  => $arSection["LEFT_MARGIN"],
                ">=RIGHT_BORDER" => $arSection["RIGHT_MARGIN"],
                "<DEPTH_LEVEL"   => $arSection["DEPTH_LEVEL"]
            ],
            false,
            ["ID", "NAME", "SECTION_PAGE_URL", "DEPTH_LEVEL"]
        );
        while ($arSection = $dbSections->GetNext(false, false)) {
            $arRet[$arSection['DEPTH_LEVEL']] = $arSection;
        }
        return $arRet;
    }

	public static function psf_getSectionId(
            $p_iblockCode, $p_sectionCode, $p_cacheTime) {

        if (is_numeric($p_iblockCode)) $iblockId = IntVal($p_iblockCode);
        else $iblockId = Site::psf_getIBlockId($p_iblockCode);
        if (!$iblockId || !$p_sectionCode) return false;

        if (!self::psf_isCacheEnabled() || $p_cacheTime === 0) {
            if (Loader::includeModule('iblock')) {
                $ret = self::psf_getSectionId_query($iblockId, $p_sectionCode);
            }

        } else {
            $cacheId = 'iblock-' . $iblockId . '-' . $p_iblockCode . '_section-' . $p_sectionCode;
            if (!empty($p_arFilter)) $cacheId .= '_' . serialize($p_arFilter);
            $cacheTtl = ($p_cacheTime !== false ? $p_cacheTime : 31536000);
            $cachePath = '/iblock_' . $iblockId;

            $cache = Cache::createInstance();
            if ($cache->initCache($cacheTtl, $cacheId, $cachePath)) {
                $ret = $cache->getVars();

            } elseif ($cache->startDataCache() && Loader::includeModule('iblock')) {
                $ret = self::psf_getSectionId_query($iblockId, $p_sectionCode);

                $cache_manager = Application::getInstance()->getTaggedCache();
                $cache_manager->startTagCache($cachePath);
                $cache_manager->registerTag('iblock_id_' . $iblockId);
                $cache_manager->endTagCache();

                $cache->endDataCache($ret);
            }
        }
		return $ret;
	}

	protected static function psf_getSectionId_query($p_iblockId, $p_sectionCode) {
        $ret = false;

        $dbSections = \CIBlockSection::GetList(
            [],
            ["IBLOCK_ID" => $p_iblockId, "ACTIVE" => "Y", "CODE" => $p_sectionCode],
            false,
            ["ID"]
        );
        if ($arSection = $dbSections->Fetch()) {
            $ret = $arSection["ID"];
        }
        return $ret;
    }

	public static function psf_getAllSections(
            $p_iblockCode, $p_cacheTime = false, $p_arFilter = false, $p_arSelect = false) {

        if (is_numeric($p_iblockCode)) $iblockId = IntVal($p_iblockCode);
        else $iblockId = Site::psf_getIBlockId($p_iblockCode);
        if (!$iblockId) return false;

        if (!self::psf_isCacheEnabled() || $p_cacheTime === 0) {
            if (Loader::includeModule('iblock')) {
                $arRet = self::psf_getAllSections_query($iblockId, $p_arFilter, $p_arSelect);
            }

        } else {
            $cacheId = 'iblock-' . $iblockId . '-' . $p_iblockCode . '_all-sections';
            if (!empty($p_arFilter)) $cacheId .= '_' . serialize($p_arFilter);
            if (!empty($p_arSelect)) $cacheId .= '_' . serialize($p_arSelect);
            $cacheTtl = ($p_cacheTime !== false ? $p_cacheTime : 31536000);
            $cachePath = '/iblock_' . $iblockId;

            $cache = Cache::createInstance();
            if ($cache->initCache($cacheTtl, $cacheId, $cachePath)) {
                $arRet = $cache->getVars();

            } elseif ($cache->startDataCache() && Loader::includeModule('iblock')) {
                $arRet = self::psf_getAllSections_query($iblockId, $p_arFilter, $p_arSelect);

                $cache_manager = Application::getInstance()->getTaggedCache();
                $cache_manager->startTagCache($cachePath);
                $cache_manager->registerTag('iblock_id_' . $iblockId);
                $cache_manager->endTagCache();

                $cache->endDataCache($arRet);
            }
        }
		return $arRet;
	}

	protected static function psf_getAllSections_query(
            $p_iblockId, $p_arFilter = false, $p_arSelect = false) {

        $arRet = false;

        $arFilter = ["IBLOCK_ID" => $p_iblockId, "ACTIVE" => "Y"];
        if (!empty($p_arFilter)) $arFilter = array_merge($arFilter, $p_arFilter);

        $arSelect = ["IBLOCK_ID", "ID", "NAME", "CODE"];
        if (!empty($p_arSelect)) $arSelect = array_merge($arSelect, $p_arSelect);

        $dbSections = \CIBlockSection::GetList(
            ["SORT" => "ASC"],
            $arFilter,
            false,
            $arSelect
        );
        if (in_array("SECTION_PAGE_URL", $arSelect)) {
            while ($arSection = $dbSections->GetNext(false, false)) {
                self::psf_processSection($arSection);
                $arRet[$arSection["ID"]] = $arSection;
            }
        } else {
            while ($arSection = $dbSections->Fetch()) {
                self::psf_processSection($arSection);
                $arRet[$arSection["ID"]] = $arSection;
            }
        }
        return $arRet;
    }

    /**
     *
     * @param type $p_iblockCode
     * @param type $p_arUserFields
     * @param type $p_cacheTime
     * @return array
     *         SECTION_CODES => SECTION_ID
     *         USER_FIELD_CODE => [
     *             VALUES   => [SECTION_ID => USER_FIELD_VALUE]
     *             SECTIONS => [SECTION_ID => SECTION_ID from VALUES]
     */
	public static function psf_hierarchy_findSectionUF(
            $p_iblockCode, $p_arUserFields, $p_cacheTime = false) {

        if (is_numeric($p_iblockCode)) $iblockId = IntVal($p_iblockCode);
        else $iblockId = Site::psf_getIBlockId($p_iblockCode);
        if (!$iblockId) return false;

        if (!is_array($p_arUserFields)) $p_arUserFields = [$p_arUserFields];
        if (empty($p_arUserFields)) return false;

        if (!self::psf_isCacheEnabled() || $p_cacheTime === 0) {
            if (Loader::includeModule('iblock')) {
                $arRet = self::psf_hierarchy_findSectionUF_query($iblockId, $p_arUserFields);
            }

        } else {
            $cacheId = 'iblock-' . $iblockId . '-' . $p_iblockCode . '_find-section-uf';
            if (!empty($p_arUserFields)) $cacheId .= '_' . serialize($p_arUserFields);
            $cacheTtl = ($p_cacheTime !== false ? $p_cacheTime : 31536000);
            $cachePath = '/iblock_' . $iblockId;

            $cache = Cache::createInstance();
            if ($cache->initCache($cacheTtl, $cacheId, $cachePath)) {
                $arRet = $cache->getVars();

            } elseif ($cache->startDataCache() && Loader::includeModule('iblock')) {
                $arRet = self::psf_hierarchy_findSectionUF_query($iblockId, $p_arUserFields);

                $cache_manager = Application::getInstance()->getTaggedCache();
                $cache_manager->startTagCache($cachePath);
                $cache_manager->registerTag('iblock_id_' . $iblockId);
                $cache_manager->endTagCache();

                $cache->endDataCache($arRet);
            }
        }
		return $arRet;
	}

	protected static function psf_hierarchy_findSectionUF_query(
            $p_iblockId, $p_arUserFields) {
        $arSelect = array_merge(["ID", "CODE", "IBLOCK_SECTION_ID"], $p_arUserFields);

        $arAllSections = \Baza23\DataUtils::psf_getAllSections(
                $p_iblockId,
                0,
                false,
                $arSelect
        );
        if (empty($arAllSections)) return false;

        $arRet = [
            'SECTION_CODES' => [],
        ];
        foreach ($p_arUserFields as $uf) {
            $arRet[$uf] = [
                'VALUES'   => [],
                'SECTIONS' => [],
            ];
        }
        $arParentSectionIds = [];

        foreach ($arAllSections as $id => $arSection) {
            $arRet["SECTION_CODES"][$arSection["CODE"]] = $arSection["ID"];

            foreach ($p_arUserFields as $uf) {
                if (!empty($arSection[$uf])) {
                    $arRet[$uf]['VALUES'][$id] = $arSection[$uf];
                }
            }

            $arParentSectionIds[$id] = $arSection['IBLOCK_SECTION_ID'];
        }

        foreach ($p_arUserFields as $uf) {
            $arRet[$uf]['SECTIONS'] = self::psf_hierarchy_findSectionUF_search(
                    $arRet[$uf]['VALUES'], $arParentSectionIds);
        }
        return $arRet;
    }

    protected static function psf_hierarchy_findSectionUF_search(
            $p_arValues, $p_arParentSectionIds) {
        if (empty($p_arValues)) return false;

        $arRet = [];
        foreach ($p_arParentSectionIds as $sectionId => $parentId) {
            if ($arRet[$sectionId]) {
                continue;

            } elseif ($p_arValues[$sectionId]) {
                $arRet[$sectionId] = $sectionId;
                continue;
            }

            $arIds = [$sectionId];
            $curId = $parentId;
            $foundId = false;

            while (!$foundId && $curId) {
                $foundId = $arRet[$curId];
                if (!$foundId) {
                    if ($p_arValues[$curId]) $foundId = $curId;
                    else $curId = $p_arParentSectionIds[$curId];

                    $arIds[] = $curId;
                }
            }

            if ($foundId) {
                foreach ($arIds as $id) {
                    $arRet[$id] = $foundId;
                }
            }
        }
        return $arRet;
    }

	public static function psf_getFile($p_iblockCode, $p_elementId, $p_propertyCode) {
        if (is_numeric($p_iblockCode)) $iblockId = IntVal($p_iblockCode);
        else $iblockId = Site::psf_getIBlockId($p_iblockCode);
        if (!$iblockId || !$p_elementId || !$p_propertyCode) return false;

        $arRet = false;

        if (Loader::includeModule('iblock')) {
            $dbElements = \CIBlockElement::GetList(
                [],
                ["IBLOCK_ID" => $iblockId, "ACTIVE" => "Y", "ID" => $p_elementId],
                false,
                false,
                ["IBLOCK_ID", "ID", "PROPERTY_" . $p_propertyCode]
            );
            if ($arElement = $dbElements->Fetch()) {
                $arRet = \CFile::GetFileArray($arElement["PROPERTY_" . $p_propertyCode . "_VALUE"]);
            }
        }
        return $arRet;
    }

	public static function psf_searchElements(
            $p_iblockCode, $p_query, $p_arSort, $p_cacheTime = false) {

        if (is_numeric($p_iblockCode)) $iblockId = IntVal($p_iblockCode);
        else $iblockId = Site::psf_getIBlockId($p_iblockCode);
        if (!$iblockId || empty($p_query) || mb_strlen($p_query) < 3) return false;

        if (empty($p_arSort)) $p_arSort = ["RANK" => "ASC"];

        if (!self::psf_isCacheEnabled() || $p_cacheTime === 0) {
            if (Loader::includeModule('iblock')) {
                $arRet = self::psf_searchElements_query($iblockId, $p_query, $p_arSort);
            }

        } else {
            $cacheId = 'iblock-' . $iblockId . '-' . $p_iblockCode
                    . '_search_query-' . $p_query . '_sort-' . serialize($p_arSort);
            $cacheTtl = ($p_cacheTime !== false ? $p_cacheTime : \Baza23\Site::psf_getCacheTime("search"));
            $cachePath = '/iblock_' . $iblockId;

            $cache = Cache::createInstance();
            if ($cache->initCache($cacheTtl, $cacheId, $cachePath)) {
                $arRet = $cache->getVars();

            } elseif ($cache->startDataCache() && Loader::includeModule('search')) {
                $arRet = self::psf_searchElements_query($iblockId, $p_query, $p_arSort);
                $cache->endDataCache($arRet);
            }
        }
		return $arRet;
	}

    /*
     * $p_sort - DATE_FROM, RANK, TITLE, ITEM_ID
     */
	protected static function psf_searchElements_query(
            $p_iblockId, $p_query, $p_arSort) {

        $arRet = [];
        $objSearch = new \CSearch;
        // мы добавили еще этот параметр, чтобы не ругался на форматирование запроса
        $objSearch->SetOptions(['ERROR_ON_EMPTY_STEM' => false]);
        // поиск через Sphinx на сайте
        $objSearch->Search(
            [
                "QUERY"     => $p_query,
                "SITE_ID"   => SITE_ID,
                "MODULE_ID" => "iblock",
                "PARAM2"    => $p_iblockId,
            ],
            $p_arSort
        );
        // и делаем резапрос, если не найдено с морфологией
        if (!$objSearch->selectedRowsCount()) {
            $objSearch->Search(
                [
                    'QUERY'     => $p_query,
                    'SITE_ID'   => SITE_ID,
                    'MODULE_ID' => 'iblock',
                    'PARAM2'    => $p_iblockId
                ],
                $p_arSort,
                ['STEMMING' => false]
            );
        }

        // чтобы при обращении к модулю поиска поисковая фраза проиндексировалась в статистике Поиска
        $obSearch->Statistic = new \CSearchStatistic($obSearch->strQueryText, $obSearch->strTagsText);
        $obSearch->Statistic->PhraseStat($obSearch->NavRecordCount, $obSearch->NavPageNomer);

        while ($arSearch = $objSearch->Fetch()) {
            $arRet[] = $arSearch['ITEM_ID'];
        }
        return $arRet;
    }

    protected static function psf_processElement(&$p_arElement) {
        if ($p_arElement["PREVIEW_PICTURE"]) {
            $p_arElement["PREVIEW_PICTURE"] = \CFile::GetFileArray($p_arElement["PREVIEW_PICTURE"]);
        }
        if ($p_arElement["DETAIL_PICTURE"]) {
            $p_arElement["DETAIL_PICTURE"] = \CFile::GetFileArray($p_arElement["DETAIL_PICTURE"]);
        }
    }

    protected static function psf_processSection(&$p_arSection) {
        if ($p_arSection["PICTURE"]) {
            $p_arSection["PICTURE"] = \CFile::GetFileArray($p_arSection["PICTURE"]);
        }
        if ($p_arSection["DETAIL_PICTURE"]) {
            $p_arSection["DETAIL_PICTURE"] = \CFile::GetFileArray($p_arSection["DETAIL_PICTURE"]);
        }
    }

    public static function psf_isCacheEnabled() {
        $ret = true;
        if (self::DEFAULT_CACHE_ENABLED == "Y") {
            $ret = true;
        } elseif (self::DEFAULT_CACHE_ENABLED == "N") {
            $ret = false;
        } else {
            $ret = (\Bitrix\Main\Config\Option::get("main", "component_cache_on", "Y") == "Y");
        }
        return $ret;
    }
}