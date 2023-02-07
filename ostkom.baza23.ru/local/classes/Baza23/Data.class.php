<?php

namespace Baza23;

use Bitrix\Main\Application,
    Bitrix\Main\Data\Cache,
    Bitrix\Main\Loader;

class Data {
    public static function psf_getAllPeriods() {
        $arRet = self::psf_getAllElements(34);
        return $arRet;
    }

    public static function psf_getAllTariffs($p_iblockId) {
        $arTariffs = self::psf_getAllElements($p_iblockId, false, false,
                [
                    "IBLOCK_SECTION_ID",
                    "PROPERTY_PRICE",
                    "PROPERTY_CONTRACT_PERIOD",
                    "PROPERTY_CONTRACT_GROUP"
                ]
        );

        $arRet = [
            "LIST" => [],
            "GROUPS"  => [],
        ];

        foreach ($arTariffs as $id => $arItem) {
            $arRet["LIST"][$id] = [
                "ID" => $id,
                //"CODE" => $arItem["CODE"],
                //"NAME" => $arItem["NAME"],
                //"IBLOCK_SECTION_ID" => $arItem["IBLOCK_SECTION_ID"],
                "PRICE" => $arItem["PROPERTY_PRICE_VALUE"],
                "CONTRACT_GROUP" => $arItem["PROPERTY_CONTRACT_GROUP_VALUE"],
                "CONTRACT_PERIOD" => $arItem["PROPERTY_CONTRACT_PERIOD_VALUE"],
            ];
            $arRet["GROUPS"][$arItem["PROPERTY_CONTRACT_GROUP_VALUE"]][$arItem["PROPERTY_CONTRACT_PERIOD_VALUE"]] = $id;
        }
        unset($arTariffs);
        return $arRet;
    }

	public static function psf_getAllElements($p_iblockCode, $p_cacheTime = false, $p_arFilter = false, $p_arSelect = false) {
        if (is_numeric($p_iblockCode)) $iblockId = IntVal($p_iblockCode);
        else $iblockId = '';
        if (!$iblockId) return false;

        if (\Bitrix\Main\Config\Option::get("main", "component_cache_on", "Y") == "N"
                || $p_cacheTime === 0) {
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

	protected static function psf_getAllElements_query($p_iblockId, $p_arFilter = false, $p_arSelect = false) {
        $arRet = array();

        $arFilter = array("IBLOCK_ID" => $p_iblockId, "ACTIVE" => "Y");
        if (!empty($p_arFilter)) $arFilter = array_merge($arFilter, $p_arFilter);

        $arSelect = array("IBLOCK_ID", "ID", "NAME", "CODE");
        if (!empty($p_arSelect)) $arSelect = array_merge($arSelect, $p_arSelect);

        $dbElements = \CIBlockElement::GetList(
            array("SORT" => "ASC", "NAME" => "ASC"),
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

    protected static function psf_processElement(&$p_arElement) {
        if ($p_arElement["PREVIEW_PICTURE"]) {
            $p_arElement["PREVIEW_PICTURE"] = \CFile::GetFileArray($p_arElement["PREVIEW_PICTURE"]);
        }
        if ($p_arElement["DETAIL_PICTURE"]) {
            $p_arElement["DETAIL_PICTURE"] = \CFile::GetFileArray($p_arElement["DETAIL_PICTURE"]);
        }
    }
}