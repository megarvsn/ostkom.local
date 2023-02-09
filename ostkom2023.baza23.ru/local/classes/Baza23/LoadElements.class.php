<?php
namespace Baza23;

use Bitrix\Main\Data\Cache,
    Bitrix\Main\Loader;

class LoadElements
{
    static private $pr_iblockValue;
    static private $pr_sectionValue;
    static private $pr_checkIblock = false;
    static private $pr_checkSection = false;

    static private function checkValue()
    {
        if (!self::$pr_checkIblock || !self::$pr_checkSection)
            return false;
        else
            return true;
    }
    static private function setIblock($value)
    {
        if (is_string($value)) {
            self::$pr_iblockValue = $value;
            self::$pr_checkIblock = 'IBLOCK_CODE';
        }
        if (is_int($value)) {
            self::$pr_iblockValue = $value;
            self::$pr_checkIblock = 'IBLOCK_ID';
        }
    }
    static private function setSection($value)
    {
        if (is_string($value)) {
            self::$pr_sectionValue = $value;
            self::$pr_checkSection = 'SECTION_CODE';
        }
        if (is_int($value)) {
            self::$pr_sectionValue = $value;
            self::$pr_checkSection = 'SECTION_ID';
        }
    }
    static private function getElement_query()
    {
        if (!\CModule::IncludeModule("iblock"))
            return;

        $arReturn = array();

        $dbElement = \CIBlockElement::GetList(
            array("SORT" => "ASC"),
            array(
                self::$pr_checkIblock => self::$pr_iblockValue,
                self::$pr_checkSection => self::$pr_sectionValue,
                "ACTIVE" => "Y"
            ),
            false,
            false,
            array("CODE", "NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_TEXT", "DETAIL_PICTURE", "PROPERTY_UP_FILE")
        );

        while ($arElement = $dbElement->GetNext()) {

            if ($arElement["CODE"])
                $elCode = $arElement["CODE"];
            else {
                return false;
            }

            if ($arElement["NAME"])
                $arReturn[$elCode]["NAME"] = $arElement["NAME"];

            if ($arElement["PREVIEW_TEXT"])
                $arReturn[$elCode]["TEXT"] = $arElement["PREVIEW_TEXT"];
            else
                $arReturn[$elCode]["TEXT"] = $arElement["DETAIL_TEXT"];

            if ($arElement["~PREVIEW_TEXT"])
                $arReturn[$elCode]["HTML"] = $arElement["~PREVIEW_TEXT"];
            else
                $arReturn[$elCode]["HTML"] = $arElement["~DETAIL_TEXT"];

            if ($arElement["PREVIEW_PICTURE"])
                $arReturn[$elCode]["PICTURE"] = \CFile::GetFileArray($arElement["PREVIEW_PICTURE"]);
            else
                $arReturn[$elCode]["PICTURE"] = \CFile::GetFileArray($arElement["DETAIL_PICTURE"]);

            if ($arElement["PROPERTY_UP_FILE_VALUE"])
                $arReturn[$elCode]["FILE"] = \CFile::GetFileArray($arElement["PROPERTY_UP_FILE_VALUE"]);

        }
        return $arReturn;
    }
    static public function getProperty($iblock, $section)
    {
        self::setIblock($iblock);
        self::setSection($section);

        if (!self::checkValue())
            return false;

        if (\Bitrix\Main\Config\Option::get("main", "component_cache_on", "Y") == "N") {
            $arReturn = self::getElement_query();

        } else {
            $cacheId = 'iblock-' . self::$pr_iblockValue . '-section-' . self::$pr_sectionValue;
            $cacheTtl = 31536000;
            $cachePath = '/iblock_' . self::$pr_iblockValue;

            $cache = Cache::createInstance();

            if ($cache->initCache($cacheTtl, $cacheId, $cachePath))
                $arReturn = $cache->getVars();
            elseif ($cache->startDataCache() && Loader::includeModule('iblock')) {
                $arReturn = self::getElement_query();
                $cache->endDataCache($arReturn);
            }
        }
        return $arReturn;
    }
}