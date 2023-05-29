<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Application,
    \Bitrix\Main\Loader;

class PC_UTIL_SectionMenuAttrs extends CBitrixComponent {
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

        $p_arParams["ID"] = intval($p_arParams["ID"]);

        $p_arParams["INCLUDE_SECTIONS"] = (trim($p_arParams["INCLUDE_SECTIONS"]) == "Y" ? "Y" : "N");
        $p_arParams["INCLUDE_SUBSECTIONS"] = (trim($p_arParams["INCLUDE_SUBSECTIONS"]) == "Y" ? "Y" : "N");
        $p_arParams["INCLUDE_ELEMENTS"] = (trim($p_arParams["INCLUDE_ELEMENTS"]) == "Y" ? "Y" : "N");

        if (!is_array($p_arParams["SECTION_FIELDS"]) || empty($p_arParams["SECTION_FIELDS"])) {
            $p_arParams["SECTION_FIELDS"] = array("ID", "CODE", "NAME");
        }
        if (!is_array($p_arParams["ELEMENT_FIELDS"]) || empty($p_arParams["ELEMENT_FIELDS"])) {
            $p_arParams["ELEMENT_FIELDS"] = array("ID", "CODE", "NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE");
        }

        $p_arParams["SECTION_USER_FIELD_LINK"] = trim($p_arParams["SECTION_USER_FIELD_LINK"]);
        if ($p_arParams["SECTION_USER_FIELD_LINK"]) {
            if (!is_array($p_arParams["SECTION_USER_FIELDS"]) || empty($p_arParams["SECTION_USER_FIELDS"])) {
                $p_arParams["SECTION_USER_FIELDS"] = array($p_arParams["SECTION_USER_FIELD_LINK"]);
            } elseif (!in_array($p_arParams["ELEMENT_PROPERTY_LINK"], $p_arParams["SECTION_USER_FIELDS"])) {
                $p_arParams["SECTION_USER_FIELDS"][] = $p_arParams["SECTION_USER_FIELD_LINK"];
            }
        }

        $p_arParams["ELEMENT_PROPERTY_LINK"] = trim($p_arParams["ELEMENT_PROPERTY_LINK"]);
        if ($p_arParams["ELEMENT_PROPERTY_LINK"]) {
            if (!is_array($p_arParams["ELEMENT_PROPERTY_CODES"]) || empty($p_arParams["ELEMENT_PROPERTY_CODES"])) {
                $p_arParams["ELEMENT_PROPERTY_CODES"] = array($p_arParams["ELEMENT_PROPERTY_LINK"]);
            } elseif (!in_array($p_arParams["ELEMENT_PROPERTY_LINK"], $p_arParams["ELEMENT_PROPERTY_CODES"])) {
                $p_arParams["ELEMENT_PROPERTY_CODES"][] = $p_arParams["ELEMENT_PROPERTY_LINK"];
            }
        }

        $p_arParams["SECTION_HAS_ELEMENTS"] = trim($p_arParams["SECTION_HAS_ELEMENTS"]);
        if (!in_array($p_arParams["SECTION_HAS_ELEMENTS"], array("N", "ALL", "ACTIVE", "ACTIVE_DATE", "AVAILABLE"))) {
            $p_arParams["SECTION_HAS_ELEMENTS"] = "N";
        }
        $p_arParams["SECTION_HAS_ELEMENTS_SUBSECTIONS"] = (trim($p_arParams["SECTION_HAS_ELEMENTS_SUBSECTIONS"]) == "Y" ? "Y" : "N");
        $p_arParams["SECTION_HAS_ELEMENTS_COUNT"] = (trim($p_arParams["SECTION_HAS_ELEMENTS_COUNT"]) == "Y" ? "Y" : "N");
        $p_arParams["SECTION_HAS_ELEMENTS_SKIP_EMPTY"] = (trim($p_arParams["SECTION_HAS_ELEMENTS_SKIP_EMPTY"]) == "Y" ? "Y" : "N");

        $p_arParams["REPLACE_LINK"] = (trim($p_arParams["REPLACE_LINK"]) == "Y" ? "Y" : "N");
        $p_arParams["SEARCH_TEXT_LINK"] = trim($p_arParams["SEARCH_TEXT_LINK"]);
        if (! $p_arParams["SEARCH_TEXT_LINK"]) $p_arParams["SEARCH_TEXT_LINK"] = '#LINK#';

        $p_arParams["MAX_LEVEL"] = intval($p_arParams["MAX_LEVEL"]);

        $p_arParams["SORT_FIELD_1"] = strtoupper(trim($p_arParams["SORT_FIELD_1"]));
        $p_arParams["SORT_ORDER_1"] = (strtoupper(trim($p_arParams["SORT_ORDER_1"])) == "ASC" ? "ASC" : "DESC");
        $p_arParams["SORT_FIELD_2"] = strtoupper(trim($p_arParams["SORT_FIELD_2"]));
        $p_arParams["SORT_ORDER_2"] = (strtoupper(trim($p_arParams["SORT_ORDER_2"])) == "ASC" ? "ASC" : "DESC");
        $p_arParams["SORT_FIELD_3"] = strtoupper(trim($p_arParams["SORT_FIELD_3"]));
        $p_arParams["SORT_ORDER_3"] = (strtoupper(trim($p_arParams["SORT_ORDER_3"])) == "ASC" ? "ASC" : "DESC");
        $p_arParams["SORT_FIRST_SECTIONS"] = trim($p_arParams["SORT_FIRST_SECTIONS"]);
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
                if ($this->arParams["IS_SEF"] == "Y") {
                    if (! $this->arParams["SEF_BASE_URL"]) $this->arParams["SEF_BASE_URL"] = "";
                    if (! $this->arParams["LIST_PAGE_URL"]) $this->arParams["LIST_PAGE_URL"] = $arIBlock['LIST_PAGE_URL'];
                    if (! $this->arParams["SECTION_PAGE_URL"]) $this->arParams["SECTION_PAGE_URL"] = $arIBlock['SECTION_PAGE_URL'];
                    if (! $this->arParams["DETAIL_PAGE_URL"]) $this->arParams["DETAIL_PAGE_URL"] = $arIBlock['DETAIL_PAGE_URL'];
                }

                $this->arResult = $this -> pf_loadMenuItems($this->arParams);

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

    protected function pf_loadMenuItems($p_arParams) {
        $arHierarchy = $this -> pf_getSectionHierarchy($p_arParams);
        if (! $arHierarchy) return false;

        $arSections = $arHierarchy["SECTION_HIERARCHY"];
        $arElements = false;
        if ($p_arParams["INCLUDE_ELEMENTS"] == 'Y') {
            $arElements = $this -> pf_getElements($arHierarchy["SECTION_IDS"], $p_arParams);
            if ($arHierarchy["PARENT_SECTION"]["DEPTH_LEVEL"] === 0) {
                $arElements[0] = $this -> pf_getRootElements($p_arParams);
            }
        }
        if (!$arSections && !$arElements) return false;

        $arItems = array();
        if (!$arElements) {
            $arItems = $arSections;
            $this -> pf_sort($arItems, $p_arParams);
        } elseif ($p_arParams["SORT_FIRST_SECTIONS"] == "Y") {
            $arItems = $arSections;
            $this -> pf_sort($arItems, $p_arParams);
            $this -> pf_append($arItems, $arElements);
        } elseif (!$arSections) {
            $arItems = $arElements;
        } else {
            $arItems = $arSections;
            $this -> pf_append($arItems, $arElements);
            $this -> pf_sort($arItems, $p_arParams);
        }

        $arMenu = array(
                "SECTIONS" => $this -> pf_createItems($arItems, $p_arParams),
                "ELEMENT_LINKS" => $this -> pf_createElementLinks($arHierarchy["SECTION_IDS"], $arHierarchy["PARENT_SECTION"]["ID"], $p_arParams)
        );

        $arRet = $this -> pf_createMenuItems($arMenu);
        return $arRet;
    }

    protected function pf_createMenuItems(&$p_arMenu) {
        $arRet = array();
        $menuIndex = 0;
        $previousDepthLevel = 1;
        foreach ($p_arMenu["SECTIONS"] as $arSection) {
            if ($menuIndex > 0) {
                $arRet[$menuIndex - 1][3]["IS_PARENT"] = $arSection["DEPTH_LEVEL"] > $previousDepthLevel;
            }
            $previousDepthLevel = $arSection["DEPTH_LEVEL"];

            $link = $arSection["LINK"];
            if (!$link) $link = $arSection["SECTION_PAGE_URL"];

            $additionalLinks = $p_arMenu["ELEMENT_LINKS"][$arSection["ID"]];
            if (empty($additionalLinks)) $additionalLinks = array();

            $arRet[$menuIndex ++] = array(
                $arSection["NAME"],
                $link,
                $additionalLinks,
                array(
                    "FROM_IBLOCK" => true,
                    "IS_PARENT" => false,
                    "DEPTH_LEVEL" => $arSection["DEPTH_LEVEL"],

                    "ATTRIBUTES" => $arSection["ATTRIBUTES"],
                    "FIELDS" => $arSection["FIELDS"],
                    "PROPERTIES" => $arSection["PROPERTIES"],
                ),
                ""
            );
        }
        return $arRet;
    }

    protected function pf_createElementLinks($p_sectionIds, $p_parentSectionId, &$p_arParams) {
        $arRet = array();
        foreach ($p_sectionIds as $id) {
            if ($id != $p_parentSectionId) $arRet[$id] = array();
        }

        //In "SEF" mode we'll try to parse URL and get ELEMENT_ID from it
        if ($p_arParams["IS_SEF"] === "Y") {
            $engine = new CComponentEngine($this);
            $engine -> addGreedyPart("#SECTION_CODE_PATH#");
            $engine -> setResolveCallback(array("CIBlockFindTools", "resolveComponentEngine"));

            $componentPage = $engine->guessComponentPath(
                $p_arParams["SEF_BASE_URL"],
                array(
                    "section" => $p_arParams["SECTION_PAGE_URL"],
                    "detail"  => $p_arParams["DETAIL_PAGE_URL"],
                ),
                $arVariables
            );
            if ($componentPage === "detail") {
                CComponentEngine::InitComponentVariables(
                    $componentPage,
                    array("SECTION_ID", "ELEMENT_ID"),
                    array(
                        "section" => array("SECTION_ID" => "SECTION_ID"),
                        "detail"  => array("SECTION_ID" => "SECTION_ID", "ELEMENT_ID" => "ELEMENT_ID"),
                    ),
                    $arVariables
                );
                $p_arParams["ID"] = intval($arVariables["ELEMENT_ID"]);
            }
        }

        if (($p_arParams["ID"] > 0) && (intval($arVariables["SECTION_ID"]) <= 0)) {
            $arSelect = array("ID", "IBLOCK_ID", "DETAIL_PAGE_URL", "IBLOCK_SECTION_ID");
            $arFilter = array(
                "ID" => $p_arParams["ID"],
                "ACTIVE" => "Y",
                "IBLOCK_ID" => $p_arParams["IBLOCK_ID"],
            );
            $rsElements = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
            if (($p_arParams["IS_SEF"] === "Y") && (strlen($p_arParams["DETAIL_PAGE_URL"]) > 0)) {
                $rsElements -> SetUrlTemplates($p_arParams["SEF_BASE_URL"] . $p_arParams["DETAIL_PAGE_URL"]);
            }
            while ($arElement = $rsElements -> GetNext()) {
                $arRet[$arElement["IBLOCK_SECTION_ID"]][] = $arElement["DETAIL_PAGE_URL"];
            }
        }
        return $arRet;
    }

    // IBLOCK
    protected function pf_checkIBlock($p_arParams) {
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

        if ($p_arParams["INCLUDE_SECTIONS"] != "Y") {
            return $arRet = array("PARENT_SECTION" => $arParentSection, "SECTION_IDS" => $arSectionIds, "SECTION_HIERARCHY" => $arParentSection);
        }

        $arSectionHierarchy = $arParentSection;
        $arSectionDepth = array($arParentSection["DEPTH_LEVEL"] => &$arSectionHierarchy);

        $arFilter = $this -> pf_getSectionFilter($arParentSection, $p_arParams);
        $arSelect = $this -> pf_getSectionSelect($p_arParams);
        $dbSection = CIBlockSection::GetList(array("LEFT_MARGIN" => "ASC"), $arFilter, false, $arSelect);
        if ($p_arParams["IS_SEF"] !== "Y") $dbSection -> SetUrlTemplates("", $p_arParams["SECTION_URL"]);
        else $dbSection -> SetUrlTemplates("", $p_arParams["SEF_BASE_URL"] . $p_arParams["SECTION_PAGE_URL"]);
        while ($arSection = $dbSection -> GetNext()) {
            if (! $arSection["IBLOCK_SECTION_ID"]) $arSection["IBLOCK_SECTION_ID"] = 0;

            $arSection["IS_SECTION"] = "Y";
            $arSection["ITEMS"] = array();

            $arSectionIds[] = $arSection["ID"];

            unset($arSectionDepth[$arSection["DEPTH_LEVEL"]]);
            $arSectionDepth[$arSection["DEPTH_LEVEL"]] = $arSection;
            $arSectionDepth[$arSection["DEPTH_LEVEL"] - 1]["ITEMS"][$arSection["ID"]] = &$arSectionDepth[$arSection["DEPTH_LEVEL"]];
        }
        unset($arSectionDepth);

        if (!empty($arSectionIds)) {
            $arHasElementsFilter = $this -> pf_getSectionHasElementsFilter($p_arParams);
            if ($arHasElementsFilter) {
                if ($p_arParams["SECTION_HAS_ELEMENTS_COUNT"] == "Y") {
                    $arSect = $this -> pf_countElements($arSectionHierarchy, $arHasElementsFilter, $p_arParams);
                    $arSectionHierarchy["ELEMENT_CNT"] = $arSect["ELEMENT_CNT"];
                } else {
                    $arSect = $this -> pf_hasElements($arSectionHierarchy, $arHasElementsFilter, $p_arParams);
                    $arSectionHierarchy["HAS_ELEMENTS"] = $arSect["HAS_ELEMENTS"];
                }

                if ($p_arParams["SECTION_HAS_ELEMENTS_SKIP_EMPTY"] == "Y") {
                    foreach ($arSect["SKIP_SECTION_IDS"] as $id) {
                        $key = array_search($id, $arSectionIds);
                        if ($key !== false) unset($arSectionIds[$key]);
                    }
                }
            }
        }

        $arRet = array("PARENT_SECTION" => $arParentSection, "SECTION_IDS" => $arSectionIds, "SECTION_HIERARCHY" => $arSectionHierarchy);
        return $arRet;
    }

    protected function pf_countElements(&$p_arSection, $p_arHasElementsFilter, $p_arParams) {
        $arElementFilter = $p_arHasElementsFilter;
        $arElementFilter["SECTION_ID"] = $p_arSection["ID"];
        if ($p_arSection['RIGHT_MARGIN'] == ($p_arSection['LEFT_MARGIN'] + 1)) {
            $arElementFilter['INCLUDE_SUBSECTIONS'] = 'N';
        }

        $arRet = array("ELEMENT_CNT" => 0, "SKIP_SECTION_IDS" => array());

        if (!empty($p_arSection["ITEMS"])) {
            $arElementFilter['INCLUDE_SUBSECTIONS'] = 'N';
            $arEmptySectionIds = array();

            foreach ($p_arSection["ITEMS"] as $id => &$arItem) {
                $arSect = $this -> pf_countElements($arItem, $p_arHasElementsFilter, $p_arParams);

                if ($arSect["ELEMENT_CNT"] > 0) {
                    $arRet["ELEMENT_CNT"] += $arSect["ELEMENT_CNT"];
                } else {
                    $arEmptySectionIds[] = $arItem["ID"];
                }

                $arRet["SKIP_SECTION_IDS"] = array_merge($arRet["SKIP_SECTION_IDS"], $arSect["SKIP_SECTION_IDS"]);
            }

            $arRet["SKIP_SECTION_IDS"] = array_merge($arRet["SKIP_SECTION_IDS"], $arEmptySectionIds);

            if ($p_arParams["SECTION_HAS_ELEMENTS_SKIP_EMPTY"] == "Y") {
                if ($arRet["ELEMENT_CNT"] > 0) {
                    foreach ($arEmptySectionIds as $id) {
                        unset($p_arSection["ITEMS"][$id]);
                    }
                } else {
                    $p_arSection["ITEMS"] = array();
                }
            }
        }

        $p_arSection["ELEMENT_CNT"] = CIBlockElement::GetList(array(), $arElementFilter, array());
        $arRet["ELEMENT_CNT"] += $p_arSection["ELEMENT_CNT"];

        if ($p_arHasElementsFilter["INCLUDE_SUBSECTIONS"] == "Y") $p_arSection["ELEMENT_CNT"] = $arRet["ELEMENT_CNT"];
        return $arRet;
    }

    protected function pf_hasElements(&$p_arSection, $p_arHasElementsFilter, $p_arParams) {
        $arElementFilter = $p_arHasElementsFilter;
        $arElementFilter["SECTION_ID"] = $p_arSection["ID"];
        if ($p_arSection['RIGHT_MARGIN'] == ($p_arSection['LEFT_MARGIN'] + 1)) {
            $arElementFilter['INCLUDE_SUBSECTIONS'] = 'N';
        }

        $arRet = array("HAS_ELEMENTS" => false, "SKIP_SECTION_IDS" => array());

        if (!empty($p_arSection["ITEMS"])) {
            $arElementFilter['INCLUDE_SUBSECTIONS'] = 'N';
            $arEmptySectionIds = array();

            foreach ($p_arSection["ITEMS"] as $id => &$arItem) {
                $arSect = $this -> pf_hasElements($arItem, $p_arHasElementsFilter, $p_arParams);

                if ($arSect["HAS_ELEMENTS"]) $arRet["HAS_ELEMENTS"] = true;
                else $arEmptySectionIds[] = $arItem["ID"];

                $arRet["SKIP_SECTION_IDS"] = array_merge($arRet["SKIP_SECTION_IDS"], $arSect["SKIP_SECTION_IDS"]);
            }

            $arRet["SKIP_SECTION_IDS"] = array_merge($arRet["SKIP_SECTION_IDS"], $arEmptySectionIds);

            if ($p_arParams["SECTION_HAS_ELEMENTS_SKIP_EMPTY"] == "Y") {
                if ($arRet["HAS_ELEMENTS"]) {
                    foreach ($arEmptySectionIds as $id) {
                        unset($p_arSection["ITEMS"][$id]);
                    }
                } else {
                    $p_arSection["ITEMS"] = array();
                }
            }
        }

        if ($p_arHasElementsFilter["INCLUDE_SUBSECTIONS"] == "Y" && $arRet["HAS_ELEMENTS"]) {
            $p_arSection["HAS_ELEMENTS"] = true;
        } else {
            $dbSections = CIBlockElement::GetList(
                    array(),
                    $arElementFilter,
                    false,
                    array("nTopCount" => 1),
                    array("ID")
            );
            if ($arSection = $dbSections -> Fetch()) {
                $p_arSection["HAS_ELEMENTS"] = true;
                $arRet["HAS_ELEMENTS"] = true;
            } else {
                $p_arSection["HAS_ELEMENTS"] = false;
            }
        }

        return $arRet;
    }

    protected function pf_getParentSection($p_arParams) {
        if ($p_arParams["IBLOCK_ID"] <= 0 || ($p_arParams["SECTION_ID"] <= 0 && ! $p_arParams["SECTION_CODE"])) {
            return array('IBLOCK_ID' => $p_arParams["IBLOCK_ID"], "ID" => 0, 'DEPTH_LEVEL' => 0, "IS_SECTION" => "Y");
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
        if ($arSection = $dbSection -> Fetch()) {
            $arSection["IS_SECTION"] = "Y";
            $arRet = $arSection;
        }
        return $arRet;
    }

    protected function pf_getSectionSelect($p_arParams) {
        $arRet = array('IBLOCK_ID', 'ID', 'CODE', 'NAME', 'SORT', "SECTION_PAGE_URL",
                'IBLOCK_SECTION_ID', 'LEFT_MARGIN', 'RIGHT_MARGIN', 'DEPTH_LEVEL');
        if ($p_arParams["SECTION_FIELDS"]) {
            $arRet = array_merge($arRet, $p_arParams["SECTION_FIELDS"]);
        }
        if ($p_arParams["SECTION_USER_FIELDS"]) {
            $arRet = array_merge($arRet, $p_arParams["SECTION_USER_FIELDS"]);
        }
        $arRet = array_unique($arRet);
        return $arRet;
    }

    protected function pf_getSectionFilter($p_arParentSection, $p_arParams) {
        $arRet = array(
                "IBLOCK_ID" => $p_arParentSection["IBLOCK_ID"],
                "ACTIVE" => "Y",
        );
        if ($p_arParentSection["ID"] > 0) {
            $arRet['LEFT_MARGIN'] = $p_arParentSection["LEFT_MARGIN"] + 1;
            $arRet['RIGHT_MARGIN'] = $p_arParentSection["RIGHT_MARGIN"] - 1;
        }
        if ($p_arParams["MAX_LEVEL"] > 0) {
            $arRet["<=" . "DEPTH_LEVEL"] = $p_arParams["MAX_LEVEL"];
        } elseif ($p_arParams["INCLUDE_SUBSECTIONS"] == 'N') {
            $maxLevel = ($p_arParentSection["DEPTH_LEVEL"] > 0 ? $p_arParentSection["DEPTH_LEVEL"] + 1 : 1);
            $arRet["<=" . "DEPTH_LEVEL"] = $maxLevel;
        }
        return $arRet;
    }

    protected function pf_getSectionHasElementsFilter($p_arParams) {
        if ($p_arParams["SECTION_HAS_ELEMENTS"] == "N") return false;

        $arRet = array(
                "IBLOCK_ID" => $p_arParams["IBLOCK_ID"],
                "CHECK_PERMISSIONS" => "Y",
                "MIN_PERMISSION" => "R",
                "INCLUDE_SUBSECTIONS" => ($p_arParams["SECTION_HAS_ELEMENTS_SUBSECTIONS"] == "Y" ? "Y" : "N")
        );

        switch ($p_arParams["SECTION_HAS_ELEMENTS"]) {
                case "ALL":
                        break;
                case "ACTIVE":
                        $arRet["ACTIVE"] = "Y";
                        break;
                case "ACTIVE_DATE":
                        $arRet["ACTIVE"] = "Y";
                        $arRet["ACTIVE_DATE"] = "Y";
                        break;
                case "AVAILABLE":
                        $arRet["ACTIVE"] = "Y";
                        $arRet["ACTIVE_DATE"] = "Y";
                        $arRet["AVAILABLE"] = "Y";
                        break;
        }
        return $arRet;
    }
    // End of SECTIONS

    // ELEMENTS
    protected function pf_getElementSelect($p_arParams) {
        $arRet = array('IBLOCK_ID', 'ID', 'CODE', 'NAME', 'SORT',
            'IBLOCK_SECTION_ID', 'DETAIL_PAGE_URL', 'LIST_PAGE_URL');
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

    protected function pf_getElementFilter($p_arSectionIds, $p_arParams) {
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
        $arRet = array();
        if ($p_arParams["SORT_FIELD_1"] && $p_arParams["SORT_ORDER_1"]) {
            $arRet[$p_arParams["SORT_FIELD_1"]] = $p_arParams["SORT_ORDER_1"];
        }
        if ($p_arParams["SORT_FIELD_2"] && $p_arParams["SORT_ORDER_2"]) {
            $arRet[$p_arParams["SORT_FIELD_2"]] = $p_arParams["SORT_ORDER_2"];
        }
        if ($p_arParams["SORT_FIELD_3"] && $p_arParams["SORT_ORDER_3"]) {
            $arRet[$p_arParams["SORT_FIELD_3"]] = $p_arParams["SORT_ORDER_3"];
        }
        if (empty($arRet)) {
            $arRet["SORT"] = "ASC";
            $arRet["ID"] = "ASC";
        }
        return $arRet;
    }

    protected function pf_getRootElements($p_arParams) {
        $arOrder = $this -> pf_getElementOrder($p_arParams);
        $arFilter = $this -> pf_getElementFilter(false, $p_arParams);
        $arSelect = $this -> pf_getElementSelect($p_arParams);

        $arRet = array();
        $dbElement = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($arElement = $dbElement -> GetNext()) {
            $arElement["IS_ELEMENT"] = "Y";
            $arRet[$arElement["ID"]] = $arElement;
        }
        return $arRet;
    }

    protected function pf_getElements($p_arSectionIds, $p_arParams) {
        if (! is_array($p_arSectionIds) || empty($p_arSectionIds)) return false;

        $arOrder = $this -> pf_getElementOrder($p_arParams);
        $arFilter = $this -> pf_getElementFilter($p_arSectionIds, $p_arParams);
        $arSelect = $this -> pf_getElementSelect($p_arParams);

        $arRet = array();
        $dbElement = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($arElement = $dbElement -> GetNext()) {
            $sectionId = IntVal($arElement["IBLOCK_SECTION_ID"]);
            if (! array_key_exists($sectionId, $arRet)) {
                $arRet[$sectionId] = array();
            }

            $arElement["IS_ELEMENT"] = "Y";
            $arRet[$sectionId][] = $arElement;
        }
        return $arRet;
    }
    // End of ELEMENTS

    // SORTING
    private $i_sort1 = false;
    private $i_sort1Asc = true;
    private $i_sort2 = false;
    private $i_sort2Asc = true;

    protected function pf_setSortParams($p_arParams) {
        $this -> i_sort1 = false;
        $this -> i_sort1Asc = true;
        $this -> i_sort2 = false;
        $this -> i_sort2Asc = true;

        if ($p_arParams["SORT_FIELD_1"]) {
            $this -> i_sort1 = $p_arParams["SORT_FIELD_1"];
            $this -> i_sort1Asc = ($p_arParams["SORT_ORDER_1"] == "ASC");
            if ($p_arParams["SORT_FIELD_2"]) {
                $this -> i_sort2 = $p_arParams["SORT_FIELD_2"];
                $this -> i_sort2Asc = ($p_arParams["SORT_ORDER_2"] == "ASC");
            }
        } elseif ($p_arParams["SORT_FIELD_2"]) {
            $this -> i_sort1 = $p_arParams["SORT_FIELD_2"];
            $this -> i_sort1Asc = ($p_arParams["SORT_ORDER_2"] == "ASC");
        }
    }

    protected function pf_compareByField($p_ar1, $p_ar2, $p_sortField, $p_asc) {
        if (!$p_sortField) return 0;

        $ret = 0;
        if (!isset($p_ar1[$p_sortField])) {
            if (isset($p_ar2[$p_sortField])) {
                $ret = ($p_asc ? -1 : 1);
            } else {
                $ret = 0;
            }
        } elseif (!isset($p_ar2[$p_sortField])) {
            $ret = ($p_asc ? 1 : -1);
        } else {
            $arg1 = $p_ar1[$p_sortField];
            $arg2 = $p_ar2[$p_sortField];
            if ($arg1 > $arg2) {
                $ret = ($p_asc ? 1 : -1);
            } elseif ($arg1 < $arg2) {
                $ret = ($p_asc ? -1 : 1);
            }
        }
        return $ret;
    }

    protected function pf_compare($p_ar1, $p_ar2) {
        $ret = $this -> pf_compareByField($p_ar1, $p_ar2, $this -> i_sort1, $this -> i_sort1Asc);
        if ($ret === 0) $ret = $this -> pf_compareByField($p_ar1, $p_ar2, $this -> i_sort2, $this -> i_sort2Asc);
        return $ret;
    }

    protected function pf_sort(&$p_arItem, $p_arParams) {
        if (!$p_arParams["SORT_FIELD_1"] && !$p_arParams["SORT_FIELD_2"]) return;
        $this -> pf_setSortParams($p_arParams);

        $this -> pf_sortChilds($p_arItem);
    }

    protected function pf_sortChilds(&$p_arItem) {
        if (!is_array($p_arItem["ITEMS"]) || empty($p_arItem["ITEMS"])) return;
        if (!usort($p_arItem["ITEMS"], array($this, "pf_compare"))) {
            AddMessage2Log("Array of ITEMS = " . print_r($p_arItem["ITEMS"], true), 'Error during usort(Array of ITEMS, "pf_compare")');
        }

        foreach ($p_arItem["ITEMS"] as $key => &$arItem) {
            $this -> pf_sortChilds($arItem);
        }
    }
    // End of SORTING

    // APPEND
    protected function pf_append(&$p_arSection, $p_arElements) {
        $this -> pf_appendChilds($p_arSection, $p_arElements);
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

    // CREATE ITEMS
    protected function pf_createItems($p_arItems, $p_arParams) {
        $arRet = array();
        foreach ($p_arItems["ITEMS"] as $arItem) {
            $this -> pf_createChilds($arItem, $p_arParams, IntVal($p_arItems["DEPTH_LEVEL"]), IntVal($p_arItems["DEPTH_LEVEL"]), $arRet);
        }
        return $arRet;
    }

    protected function pf_createChilds($p_arItem, $p_arParams, $p_rootDepthLevel, $p_parentDepthLevel, &$p_arRet) {
        if ($p_arItem["IS_SECTION"] == "Y") {
            $newItem = $this -> pf_createSection($p_arItem, $p_arParams, $p_rootDepthLevel);
            $p_arRet[] = $newItem;
            foreach ($p_arItem["ITEMS"] as $arItem) {
                $this -> pf_createChilds($arItem, $p_arParams, $p_rootDepthLevel, IntVal($newItem["ATTRIBUTES"]["REAL_DEPTH_LEVEL"]), $p_arRet);
            }
        } elseif ($p_arItem["IS_ELEMENT"] == "Y") {
            $p_arRet[] = $this -> pf_createElement($p_arItem, $p_arParams, $p_rootDepthLevel, $p_parentDepthLevel);
        }
    }

    protected function pf_createSection($p_arSection, $p_arParams, $p_rootDepthLevel) {
        $arAttrs = array(
                "IS_SECTION" => "Y",
                "REAL_DEPTH_LEVEL" => $p_arSection["DEPTH_LEVEL"],

                "ID"   => $p_arSection["ID"],
                "NAME" => $p_arSection["~NAME"],
                "CODE" => $p_arSection["CODE"],
                "SORT" => $p_arSection["SORT"],
                "IBLOCK_SECTION_ID" => $p_arSection["IBLOCK_SECTION_ID"],
        );

        if (isset($p_arSection["ELEMENT_CNT"])) $arAttrs["ELEMENT_CNT"] = $p_arSection["ELEMENT_CNT"];
        if (isset($p_arSection["HAS_ELEMENTS"])) $arAttrs["HAS_ELEMENTS"] = $p_arSection["HAS_ELEMENTS"];

        $arFields = array();
        if ($p_arParams["SECTION_FIELDS"]) {
            foreach ($p_arParams["SECTION_FIELDS"] as $fieldName) {
                if ($fieldName == "PICTURE" || $fieldName == "DETAIL_PICTURE") {
                    $arFields[$fieldName] = CFile::GetFileArray($p_arSection[$fieldName]);
                } elseif ($p_arSection['~' . $fieldName]) {
                    $arFields[$fieldName] = $p_arSection['~' . $fieldName];
                } else {
                    $arFields[$fieldName] = $p_arSection[$fieldName];
                }
            }
        }

        $arUserFields = array();
        if ($p_arParams["SECTION_USER_FIELDS"]) {
            foreach ($p_arParams["SECTION_USER_FIELDS"] as $propName) {
                $arUserFields[$propName] = $p_arSection['~' . $propName];
            }
        }

        $arRet = array(
            "ID" => $p_arSection["ID"],
            "DEPTH_LEVEL" => IntVal($p_arSection["DEPTH_LEVEL"]) - $p_rootDepthLevel,
            "NAME" => $p_arSection["~NAME"],
            "SECTION_PAGE_URL" => $p_arSection["SECTION_PAGE_URL"],

            "ATTRIBUTES" => $arAttrs,
            "FIELDS" => $arFields,
            "PROPERTIES" => $arUserFields,
        );
        if ($p_arParams["SECTION_USER_FIELD_LINK"]) {
            $arRet["LINK"] = $arUserFields[$p_arParams["SECTION_USER_FIELD_LINK"]];
        }
        return $arRet;
    }

    protected function pf_createElement($p_arElement, $p_arParams, $p_rootDepthLevel, $p_parentDepthLevel) {
        if ($p_arParams['REPLACE_LINK'] == 'Y' && $p_arParams['SEARCH_TEXT_LINK']) {
            $search = $p_arParams['SEARCH_TEXT_LINK'];

            if ($p_arParams['ELEMENT_PROPERTY_LINK']) {
                $replace = $p_arElement["PROPERTY_" . $p_arParams['ELEMENT_PROPERTY_LINK'] . "_VALUE"];
            } elseif ($p_arElement["DETAIL_PAGE_URL"]) {
                $replace = $p_arElement["DETAIL_PAGE_URL"];
            }
            if (!$replace) $replace = '#';

            $p_arElement['~PREVIEW_TEXT'] = str_replace($search, $replace, $p_arElement['~PREVIEW_TEXT']);
        }

        $arAttrs = array(
                "IS_ELEMENT" => "Y",
                "REAL_DEPTH_LEVEL" => $p_parentDepthLevel + 1,

                "ID"   => $p_arElement["ID"],
                "NAME" => $p_arElement["~NAME"],
                "CODE" => $p_arElement["CODE"],
                "SORT" => $p_arElement["SORT"],
                "IBLOCK_SECTION_ID" => $p_arElement["IBLOCK_SECTION_ID"],
                'DETAIL_PAGE_URL' => $p_arElement["DETAIL_PAGE_URL"],
                'LIST_PAGE_URL' => $p_arElement["LIST_PAGE_URL"],
        );

        $arFields = array();
        if ($p_arParams["ELEMENT_FIELDS"]) {
            foreach ($p_arParams["ELEMENT_FIELDS"] as $fieldName) {
                if ($fieldName == "PREVIEW_PICTURE" || $fieldName == "DETAIL_PICTURE") {
                    $arFields[$fieldName] = CFile::GetFileArray($p_arElement[$fieldName]);
                } elseif ($p_arElement['~' . $fieldName]) {
                    $arFields[$fieldName] = $p_arElement['~' . $fieldName];
                } else {
                    $arFields[$fieldName] = $p_arElement[$fieldName];
                }
            }
        }

        $arProperties = array();
        if ($p_arParams["ELEMENT_PROPERTY_CODES"]) {
            foreach ($p_arParams["ELEMENT_PROPERTY_CODES"] as $propName) {
                if ($p_arElement["~PROPERTY_" . $propName . "_VALUE"]) {
                    $arProperties[$propName] = $p_arElement["~PROPERTY_" . $propName . "_VALUE"];
                } else {
                    $arProperties[$propName] = $p_arElement["PROPERTY_" . $propName . "_VALUE"];
                }
            }
        }

        $arRet = array(
            "ID" => $p_arElement["ID"],
            "DEPTH_LEVEL" => $p_parentDepthLevel + 1 - $p_rootDepthLevel,
            "NAME" => $p_arElement["~NAME"],
            "SECTION_PAGE_URL" => $p_arElement["DETAIL_PAGE_URL"],

            "ATTRIBUTES" => $arAttrs,
            "FIELDS" => $arFields,
            "PROPERTIES" => $arProperties,
        );
        if ($p_arParams["ELEMENT_PROPERTY_LINK"]) {
            $arRet["LINK"] = $arProperties[$p_arParams["ELEMENT_PROPERTY_LINK"]];
        }
        return $arRet;
    }
    // End of CREATE ITEMS

}