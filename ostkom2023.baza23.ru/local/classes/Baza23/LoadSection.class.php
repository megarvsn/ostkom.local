<?php
namespace Baza23;

use Bitrix\Main\Data\Cache,
    Bitrix\Main\Loader;

class LoadSection
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
            self::$pr_checkSection = 'CODE';
        }
        if (is_int($value)) {
            self::$pr_sectionValue = $value;
            self::$pr_checkSection = 'ID';
        }
    }
    static private function getSection_query() {

        if (!\CModule::IncludeModule("iblock"))
            return;

        $arReturn = array();

        $arSect = \CIBlockSection::GetList(
            array("SORT" => "ASC"),
            array(
                self::$pr_checkIblock => self::$pr_iblockValue,
                self::$pr_checkSection => self::$pr_sectionValue,
                "ACTIVE" => "Y"
            ),
            false,
            array("CODE", "NAME", "DESCRIPTION", "PICTURE", "SECTION_PAGE_URL")
        );
        if ($arSectiom = $arSect->GetNext()) {

            if ($arSectiom["CODE"])
                $arReturn["CODE"] = $arSectiom["CODE"];

            if ($arSectiom["NAME"])
                $arReturn["NAME"] = $arSectiom["NAME"];

            if ($arSectiom["DESCRIPTION"])
                $arReturn["DESCRIPTION"] = $arSectiom["DESCRIPTION"];

            if ($arSectiom["PICTURE"])
                $arReturn["PICTURE"] = \CFile::GetFileArray($arSectiom['PICTURE']);

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
            $arReturn = self::getSection_query();
        } else {
            $cacheId = 'iblock-' . self::$pr_iblockValue . '-section-' . self::$pr_sectionValue;
            $cacheTtl = 31536000;
            $cachePath = '/iblock_' . self::$pr_iblockValue;

            $cache = Cache::createInstance();

            if ($cache->initCache($cacheTtl, $cacheId, $cachePath))
                $arReturn = $cache->getVars();
            elseif ($cache->startDataCache() && Loader::includeModule('iblock')) {
                $arReturn = self::getSection_query();
                $cache->endDataCache($arReturn);
            }
        }
        return $arReturn;
    }
}