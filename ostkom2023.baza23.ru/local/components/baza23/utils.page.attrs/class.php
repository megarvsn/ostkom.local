<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Application,
    \Bitrix\Main\Loader;

class PC_UTIL_PageAttrs extends CBitrixComponent {
    /**
     * Проверка наличия модулей требуемых для работы компонента
     * @return bool
     * @throws Exception
     */
    private function _checkModules() {
        if (!Loader::includeModule('iblock')) {
            $this->arResult["ERROR"] = array('TYPE' => 'MODULE_IBLOCK_IS_NOT_LOADED');
            return false;
        }
        return true;
    }

    /**
     * Подготовка параметров компонента
     * @param $p_arParams
     * @return mixed
     */
    public function onPrepareComponentParams($p_arParams) {
        if (!isset($p_arParams["CACHE_TYPE"])) $p_arParams["CACHE_TYPE"] = "A";
        if (!isset($p_arParams["CACHE_TIME"])) $p_arParams["CACHE_TIME"] = 31536000;

        $p_arParams["SITE_ID"] = trim($p_arParams["SITE_ID"]);
        $p_arParams["IBLOCK_TYPE"] = trim($p_arParams["IBLOCK_TYPE"]);
        $p_arParams["IBLOCK_ID"] = intval($p_arParams["IBLOCK_ID"]);
        $p_arParams["IBLOCK_CODE"] = trim($p_arParams["IBLOCK_CODE"]);

        $p_arParams["SECTION_ID"] = intval($p_arParams["SECTION_ID"]);
        $p_arParams["SECTION_CODE"] = trim($p_arParams["SECTION_CODE"]);
        $p_arParams["INCLUDE_SUBSECTIONS"] = trim($p_arParams["INCLUDE_SUBSECTIONS"]);

        if (!is_array($p_arParams["ELEMENT_FIELDS"]) || empty($p_arParams["ELEMENT_FIELDS"])) {
            $p_arParams["ELEMENT_FIELDS"] = array("ID", "CODE", "NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE");
        }
        return $p_arParams;
    }

    /**
     * Точка входа в компонент
     * Должна содержать только последовательность вызовов вспомогательых ф-ий и минимум логики
     * всю логику стараемся разносить по классам и методам
     */
    public function executeComponent() {
        if (!$this->_checkModules()) return $this->arResult;

        $this->arParams = $this->onPrepareComponentParams($this->arParams);

        if ($this->StartResultCache()) {
            if ($arIBlock = $this -> pf_checkIBlock($this->arParams)) {
                $this->arParams["IBLOCK_ID"] = $arIBlock["ID"];

                $this->arResult = $this -> pf_loadSection($this->arParams);

                $cache_manager = Application::getInstance()->getTaggedCache();
                $cache_manager->startTagCache($this->getCachePath());
                $cache_manager->registerTag('iblock_id_' . $this->arParams["IBLOCK_ID"]);
                $cache_manager->endTagCache();

                $this->EndResultCache();

            } else {
                $this->AbortResultCache();
                $this->arResult["ERROR"] = array(
                        'TYPE' => 'UNDEFINED_IBLOCK',
                        'PARAMS' => array(
                                "SITE_ID" => $this->arParams["SITE_ID"],
                                "IBLOCK_TYPE" => $this->arParams["IBLOCK_TYPE"],
                                "IBLOCK_CODE" => $this->arParams["IBLOCK_CODE"],
                                "IBLOCK_ID" => $this->arParams["IBLOCK_ID"]
                        ),
                );
            }
        }
        return $this->arResult;
    }

    public function pf_loadSection($p_arParams) {
        if ($p_arParams["INCLUDE_SUBSECTIONS"] == 'Y') {
            $arHierarchy = $this -> pf_getSectionHierarchy($p_arParams);
            if (! $arHierarchy) return array("ERROR" => "SECTION_IS_NOT_FOUND");

            $arSections = $arHierarchy["SECTION_HIERARCHY"];
            $arElements = $this -> pf_getElementsForSections($p_arParams, $arHierarchy["SECTION_IDS"]);
            if ($arHierarchy["PARENT_SECTION"]["ID"] <= 0 || $arHierarchy["PARENT_SECTION"]["DEPTH_LEVEL"] === 0) {
                $arElements[0] = $this -> pf_getRootElements($p_arParams);
            }
            if (!$arSections && !$arElements) return false;

            $arItems = array();
            if (!$arSections) {
                $arItems = $arElements;
            } else {
                $arItems = $arSections;
                $this -> pf_append($arItems, $arElements);
            }
            $arRet = $this -> pf_removeSectionAttrs($arItems);

        } else {
            $arParentSection = $this -> pf_getParentSection($p_arParams);
            if (! isset($arParentSection["ID"])) return array("ERROR" => "SECTION_IS_NOT_FOUND");

            if ($arParentSection["ID"] <= 0 || $arParentSection["DEPTH_LEVEL"] === 0) {
                $arRet = $this -> pf_getRootElements($p_arParams);
            } else {
                $arItems = $this -> pf_getElementsForSections($p_arParams, array($arParentSection["ID"]));
                if ($arItems) $arRet = $arItems[$arParentSection["ID"]];
                else $arRet = false;
            }
        }
        return $arRet;
    }

    protected function pf_createItem($p_arParams, $p_arElement) {
        $arRet = array("CODE" => $p_arElement["CODE"]);
        if (is_array($p_arParams["ELEMENT_FIELDS"])) {
            foreach ($p_arParams["ELEMENT_FIELDS"] as $field) {
                $value = $p_arElement[$field];
                if (! $value) continue;

                if ($field == "PREVIEW_PICTURE") {
                    $arRet["PREVIEW_PICTURE"] = CFile::GetFileArray($value);
                } elseif ($field == "DETAIL_PICTURE") {
                    $arRet["DETAIL_PICTURE"] = CFile::GetFileArray($value);
                } elseif ($field == "PREVIEW_TEXT") {
                    $arRet["PREVIEW_TEXT"] = $p_arElement["~PREVIEW_TEXT"];
                    $arRet["PREVIEW_TEXT_TYPE"] = $p_arElement["PREVIEW_TEXT_TYPE"];
                } elseif ($field == "DETAIL_TEXT") {
                    $arRet["DETAIL_TEXT"] = $p_arElement["~DETAIL_TEXT"];
                    $arRet["DETAIL_TEXT_TYPE"] = $p_arElement["DETAIL_TEXT_TYPE"];
                } else {
                    $arRet[$field] = $value;
                }
            }
        }
        if (is_array($p_arParams["ELEMENT_PROPERTY_CODES"])) {
            foreach ($p_arParams["ELEMENT_PROPERTY_CODES"] as $field) {
                $value = $p_arElement['~PROPERTY_' . $field . '_VALUE'];
                if ($value) $arRet[$field] = $value;
            }
        }
        return $arRet;
    }

    protected function pf_removeSectionAttrs($p_arElement) {
        if (isset($p_arElement["ITEMS"])) {
            $arRet = array();
            foreach ($p_arElement["ITEMS"] as $arItem) {
                if (is_array($arItem) && $arItem["CODE"]) {
                    $arRet[$arItem["CODE"]] = $this -> pf_removeSectionAttrs($arItem);
                }
            }
        } else {
            $arRet = $p_arElement;
        }
        return $arRet;
    }

    // LISTS
    public function pf_checkIds($p_arIds) {
        $arRet = array();
        if (is_array($p_arIds)) {
            if (!empty($p_arIds)) {
                foreach ($p_arIds as $id) {
                    $id = IntVal($id);
                    if ($id > 0) $arRet[$id] = $id;
                }
            }
        } elseif ($p_arIds) {
            $id = IntVal($p_arIds);
            if ($id > 0) $arRet[$id] = $id;
        }
        return $arRet;
    }

    public function pf_checkCodes($p_arCodes) {
        $arRet = array();
        if (is_array($p_arCodes)) {
            if (!empty($p_arCodes)) {
                foreach ($p_arCodes as $code) {
                    $code = trim($code);
                    if ($code) $arRet[$code] = $code;
                }
            }
        } elseif ($p_arCodes) {
            $code = trim($p_arCodes);
            if ($code) $arRet[$code] = $code;
        }
        return $arRet;
    }
    // End of LISTS

    // APPEND
    protected function pf_append(&$p_arSection, $p_arElements) {
        if ($p_arSection && $p_arElements) {
            $this -> pf_appendChilds($p_arSection, $p_arElements);
        }
    }

    protected function pf_appendChilds(&$p_arItem, $p_arElements) {
        foreach ($p_arItem["ITEMS"] as $key => &$arItem) {
            $this -> pf_appendChilds($arItem, $p_arElements);
        }

        $arElements = $p_arElements[$p_arItem["ID"]];
        foreach ($arElements as $arElem) {
            $p_arItem["ITEMS"][] = $arElem;
        }
    }
    // End of APPEND

    // IBLOCK
    public function pf_checkIBlock($p_arParams) {
        $arIBlock = $this -> pf_getIBlock($p_arParams);
        return $arIBlock;
    }

    protected function pf_getIBlock($p_arParams) {
        $arFilter = array();

        if ($p_arParams["IBLOCK_ID"] > 0) {
            return array("ID" => $p_arParams["IBLOCK_ID"]);
        } elseif ($p_arParams["IBLOCK_CODE"]) {
            if ($p_arParams["IBLOCK_TYPE"]) $arFilter["TYPE"] = $p_arParams["IBLOCK_TYPE"];
            $arFilter["CODE"] = $p_arParams["IBLOCK_CODE"];
        } else {
            return false;
        }

        if ($p_arParams["SITE_ID"]) $arFilter["SITE_ID"] = $p_arParams["SITE_ID"];

        $arRet = false;
        $dbIBlock = CIBlock::GetList(array('SORT' => 'ASC', 'ID' => 'ASC'), $arFilter);
        $dbIBlock = new CIBlockResult($dbIBlock);
        if ($arIBlock = $dbIBlock -> fetch()) {
            if ($arIBlock["ACTIVE"] == "Y") {
                $arRet = $arIBlock;
            }
        }
        return $arRet;
    }
    // End of IBLOCK

    // SECTIONS
    protected function pf_getSectionHierarchy($p_arParams) {
        $arParentSection = $this -> pf_getParentSection($p_arParams);
        if (! is_array($arParentSection)) return false;

        $arParentSection["ITEMS"] = array();

        $arSectionIds = array();
        if ($arParentSection["ID"] > 0) $arSectionIds[] = $arParentSection["ID"];

        $arSectionHierarchy = $arParentSection;
        $arSectionDepth = array($arParentSection["DEPTH_LEVEL"] => &$arSectionHierarchy);

        $arFilter = $this -> pf_getSectionFilter($p_arParams, $arParentSection);
        $arSelect = $this -> pf_getSectionSelect($p_arParams);
        $dbSection = CIBlockSection::GetList(array("LEFT_MARGIN" => "ASC"), $arFilter, false, $arSelect);
        while ($arSection = $dbSection -> GetNext()) {
            if (! $arSection["IBLOCK_SECTION_ID"]) $arSection["IBLOCK_SECTION_ID"] = 0;

            $arSection["ITEMS"] = array();

            $arSectionIds[] = $arSection["ID"];

            unset($arSectionDepth[$arSection["DEPTH_LEVEL"]]);
            $arSectionDepth[$arSection["DEPTH_LEVEL"]] = $arSection;
            $arSectionDepth[$arSection["DEPTH_LEVEL"] - 1]["ITEMS"][] = &$arSectionDepth[$arSection["DEPTH_LEVEL"]];
        }
        unset($arSectionDepth);

        $arRet = array("PARENT_SECTION" => $arParentSection, "SECTION_IDS" => $arSectionIds, "SECTION_HIERARCHY" => $arSectionHierarchy);
        return $arRet;
    }

    protected function pf_getParentSection($p_arParams) {
        if ($p_arParams["IBLOCK_ID"] <= 0 || ($p_arParams["SECTION_ID"] <= 0 && ! $p_arParams["SECTION_CODE"])) {
            return array('IBLOCK_ID' => $p_arParams["IBLOCK_ID"], "ID" => 0, 'DEPTH_LEVEL' => 0);
        }

        $arRet = false;
        $arFilter = array(
            "IBLOCK_ID"     => $p_arParams["IBLOCK_ID"],
            "GLOBAL_ACTIVE" => "Y",
            "ACTIVE"        => "Y",
        );
        if ($p_arParams["SECTION_ID"] > 0) $arFilter["ID"] = $p_arParams["SECTION_ID"];
        else if ($p_arParams["SECTION_CODE"]) $arFilter["=CODE"] = $p_arParams["SECTION_CODE"];

        $dbSection = CIBlockSection::GetList(
                array(),
                $arFilter,
                false,
                array('IBLOCK_ID', 'ID', 'NAME', 'CODE', 'LEFT_MARGIN', 'RIGHT_MARGIN', 'DEPTH_LEVEL')
        );
        if ($arSection = $dbSection -> GetNext()) {
            $arRet = $arSection;
        }
        return $arRet;
    }

    protected function pf_getSectionSelect($p_arParams) {
        $arRet = array('ID', 'NAME', "SECTION_PAGE_URL", 'CODE', 'SORT', 'IBLOCK_SECTION_ID',
                'LEFT_MARGIN', 'RIGHT_MARGIN', 'DEPTH_LEVEL');
        if ($p_arParams["SECTION_FIELDS"]) {
            $arRet = array_merge($arRet, $p_arParams["SECTION_FIELDS"]);
        }
        if ($p_arParams["SECTION_USER_FIELDS"]) {
            $arRet = array_merge($arRet, $p_arParams["SECTION_USER_FIELDS"]);
        }
        $arRet = array_unique($arRet);
        return $arRet;
    }

    protected function pf_getSectionFilter($p_arParams, $p_arParentSection) {
        $arRet = array(
                "IBLOCK_ID" => $p_arParentSection["IBLOCK_ID"],
                "ACTIVE" => "Y",
        );
        if ($p_arParentSection["ID"] > 0) {
            $arRet['LEFT_MARGIN'] = $p_arParentSection["LEFT_MARGIN"] + 1;
            $arRet['RIGHT_MARGIN'] = $p_arParentSection["RIGHT_MARGIN"] - 1;
        }
        if ($p_arParams["INCLUDE_SUBSECTIONS"] == 'N') {
            $arRet["<=" . "DEPTH_LEVEL"] = $p_arParentSection["DEPTH_LEVEL"];
        }
        return $arRet;
    }
    // End of SECTIONS


    // ELEMENTS
    protected function pf_getElementSelect($p_arParams) {
        $arRet = array("IBLOCK_ID", "IBLOCK_SECTION_ID", "ID", "CODE");
        if ($p_arParams["ELEMENT_FIELDS"]) {
            $arRet = array_merge($arRet, $p_arParams["ELEMENT_FIELDS"]);
        }
        if ($p_arParams["ELEMENT_PROPERTY_CODES"]) {
            foreach ($p_arParams["ELEMENT_PROPERTY_CODES"] as $propCode) {
                $arRet[] = "PROPERTY_" . $propCode;
            }
        }
        $arRet = array_unique($arRet);
        return $arRet;
    }

    protected function pf_getElementFilter($p_arParams, $p_arSectionIds) {
        global $DB;

        $arRet = array(
                "IBLOCK_ID" => $p_arParams["IBLOCK_ID"],
                "ACTIVE" => "Y"
        );
        if (is_array($p_arSectionIds) && !empty($p_arSectionIds)) {
            $arRet["SECTION_ID"] = $p_arSectionIds;

        } else {
            $arRet["SECTION_ID"] = false;
        }

        if ($p_arParams["ELEMENT_ACTIVE_DATES"] == "Y") {
            $arRet["ACTIVE_DATE"] = "Y";
            /*
            $arRet[] = array(
                "LOGIC" => "OR",
                array(
                    "<=ACTIVE_FROM" => $DB->FormatDate(date("Y-m-d H:i:s"), "YYYY-MM-DD HH:MI:SS", \CSite::GetDateFormat("FULL")),
                    ">=ACTIVE_TO" => $DB->FormatDate(date("Y-m-d H:i:s"), "YYYY-MM-DD HH:MI:SS", \CSite::GetDateFormat("FULL"))
                ),
                array(
                    "=ACTIVE_FROM" => false,
                    ">=ACTIVE_TO" => $DB->FormatDate(date("Y-m-d H:i:s"), "YYYY-MM-DD HH:MI:SS", \CSite::GetDateFormat("FULL"))
                ),
                array(
                    "<=ACTIVE_FROM" => $DB->FormatDate(date("Y-m-d H:i:s"), "YYYY-MM-DD HH:MI:SS", \CSite::GetDateFormat("FULL")),
                    "=ACTIVE_TO" => false
                ),
                array(
                    "=ACTIVE_FROM" => false,
                    "=ACTIVE_TO" => false
                ),
            );
            */
        }

        return $arRet;
    }

    protected function pf_getElementOrder($p_arParams) {
        $arRet = array("SORT" => "ASC", "ID" => "ASC");
        return $arRet;
    }

    protected function pf_getRootElements($p_arParams) {
        $arOrder = $this -> pf_getElementOrder($p_arParams);
        $arFilter = $this -> pf_getElementFilter($p_arParams, false);
        $arSelect = $this -> pf_getElementSelect($p_arParams);

        $arRet = array();
        $dbElement = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($arElement = $dbElement -> GetNext()) {
            $arRet[$arElement["CODE"]] = $this -> pf_createItem($p_arParams, $arElement);
        }
        return $arRet;
    }

    protected function pf_getElements($p_arParams) {
        $arOrder = $this -> pf_getElementOrder($p_arParams);
        $arFilter = $this -> pf_getElementFilter($p_arParams, false);
        $arSelect = $this -> pf_getElementSelect($p_arParams);

        $arRet = array();
        $dbElement = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($arElement = $dbElement -> GetNext()) {
            $arRet[$arElement["CODE"]] = $this -> pf_createItem($p_arParams, $arElement);
        }
        return $arRet;
    }

    protected function pf_getElementsForSections($p_arParams, $p_arSectionIds) {
        if (! is_array($p_arSectionIds) || empty($p_arSectionIds)) return false;

        $arOrder = $this -> pf_getElementOrder($p_arParams);
        $arFilter = $this -> pf_getElementFilter($p_arParams, $p_arSectionIds);
        $arSelect = $this -> pf_getElementSelect($p_arParams);

        $arRet = array();
        $dbElement = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($arElement = $dbElement -> GetNext()) {
            $sectionId = IntVal($arElement["IBLOCK_SECTION_ID"]);
            if (! array_key_exists($sectionId, $arRet)) {
                $arRet[$sectionId] = array();
            }

            $arRet[$sectionId][$arElement["CODE"]] = $this -> pf_createItem($p_arParams, $arElement);
        }
        return $arRet;
    }
    // End of ELEMENTS

}