<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;

class PC_BLOCK_HtmlDownload extends CBitrixComponent {
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
        if (!isset($p_arParams["CACHE_TYPE"])) $p_arParams["CACHE_TYPE"] = "N";
        if (!isset($p_arParams["CACHE_TIME"])) $p_arParams["CACHE_TIME"] = 0;

        $p_arParams["CSS_CLASSES"] = trim($p_arParams["CSS_CLASSES"]);
        $p_arParams["ICON_DOWNLOAD"] = trim($p_arParams["ICON_DOWNLOAD"]);

        $p_arParams["SHOW_NAME"] = (trim($p_arParams["SHOW_NAME"]) == 'Y' ? 'Y' :'N');
        $p_arParams["SHOW_LABEL"] = (trim($p_arParams["SHOW_LABEL"]) == 'Y' ? 'Y' :'N');
        $p_arParams["SHOW_IMAGE"] = (trim($p_arParams["SHOW_IMAGE"]) == 'Y' ? 'Y' :'N');
        $p_arParams["SHOW_DESCRIPTION"] = (trim($p_arParams["SHOW_DESCRIPTION"]) == 'Y' ? 'Y' :'N');

        $p_arParams["SITE_ID"] = trim($p_arParams["SITE_ID"]);
        $p_arParams["IBLOCK_TYPE"] = trim($p_arParams["IBLOCK_TYPE"]);
        $p_arParams["IBLOCK_ID"] = intval($p_arParams["IBLOCK_ID"]);
        $p_arParams["IBLOCK_CODE"] = trim($p_arParams["IBLOCK_CODE"]);

        $p_arParams["ELEMENT_ID"] = intval($p_arParams["ELEMENT_ID"]);
        $p_arParams["ELEMENT_CODE"] = trim($p_arParams["ELEMENT_CODE"]);

        $p_arParams["PROPERTY_FILE"] = trim($p_arParams["PROPERTY_FILE"]);
        $p_arParams["PROPERTY_IMAGE"] = trim($p_arParams["PROPERTY_IMAGE"]);
        $p_arParams["FIELD_IMAGE"] = trim($p_arParams["FIELD_IMAGE"]);
        $p_arParams["PROPERTY_LABEL"] = trim($p_arParams["PROPERTY_LABEL"]);
        $p_arParams["FIELD_LABEL"] = trim($p_arParams["FIELD_LABEL"]);
        $p_arParams["PROPERTY_DESCRIPTION"] = trim($p_arParams["PROPERTY_DESCRIPTION"]);
        $p_arParams["FIELD_DESCRIPTION"] = trim($p_arParams["FIELD_DESCRIPTION"]);

        if (!empty($p_arParams["FILE"])) {
            if (!is_array($p_arParams["FILE"])) {
                $p_arParams["FILE"] = CFile::GetFileArray($p_arParams["FILE"]);
            } elseif (!isset($p_arParams["FILE"]["SRC"])) {
                unset($p_arParams["FILE"]);
            }
        }

        if (!empty($p_arParams["FILE_IMAGE"])) {
            if (!is_array($p_arParams["FILE_IMAGE"])) {
                $p_arParams["FILE_IMAGE"] = CFile::GetFileArray($p_arParams["FILE_IMAGE"]);
            } elseif (!isset($p_arParams["FILE_IMAGE"]["SRC"])) {
                unset($p_arParams["FILE_IMAGE"]);
            }
        }
        if (is_array($p_arParams["FILE_LABEL"])) $p_arParams["FILE_LABEL"] = trim($p_arParams["FILE_LABEL"]["TEXT"]);
        else $p_arParams["FILE_LABEL"] = trim($p_arParams["FILE_LABEL"]);
        if (is_array($p_arParams["FILE_DESCRIPTION"])) $p_arParams["FILE_DESCRIPTION"] = trim($p_arParams["FILE_DESCRIPTION"]["TEXT"]);
        else $p_arParams["FILE_DESCRIPTION"] = trim($p_arParams["FILE_DESCRIPTION"]);
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
            if (!empty($this->arParams["FILE"])) {
                $this->arResult["FILE"] = $this->arParams["FILE"];
                $this->arResult["FILE_IMAGE"] = $this->arParams["FILE_IMAGE"];
                $this->arResult["FILE_LABEL"] = $this->arParams["FILE_LABEL"];
                $this->arResult["FILE_DESCRIPTION"] = $this->arParams["FILE_DESCRIPTION"];
                $this->EndResultCache();

            } elseif (($this->arParams["ELEMENT_ID"] > 0 || $this->arParams["ELEMENT_CODE"])
                    && $arIBlock = $this -> pf_checkIBlock($this->arParams)) {
                $this->arParams["IBLOCK_ID"] = $arIBlock["ID"];

                $this->arResult = $this -> pf_loadFile($this->arParams);
                $this->EndResultCache();

            } else {
                $this->AbortResultCache();
                $this->arResult["ERROR"] = array(
                        'TYPE' => 'UNDEFINED_FILE',
                        'PARAMS' => array(
                                "SITE_ID" => $this->arParams["SITE_ID"],
                                "IBLOCK_TYPE" => $this->arParams["IBLOCK_TYPE"],
                                "IBLOCK_CODE" => $this->arParams["IBLOCK_CODE"],
                                "IBLOCK_ID" => $this->arParams["IBLOCK_ID"],
                                "ELEMENT_ID" => $this->arParams["ELEMENT_ID"],
                                "ELEMENT_CODE" => $this->arParams["ELEMENT_CODE"],
                        ),
                );
            }
        }
        $this -> IncludeComponentTemplate();
        return $this;
    }

    public function pf_loadFile($p_arParams) {
        $arRet = array();

        $arSrcFile = $this->pf_getElement($p_arParams);
        if (!empty($arSrcFile)) {
            if ($p_arParams["PROPERTY_FILE"]) {
                $srcFile = $arSrcFile["PROPERTY_" . $p_arParams["PROPERTY_FILE"] . "_VALUE"];
                if ($srcFile) $srcFile = CFile::GetFileArray($srcFile);
                if (isset($srcFile["SRC"])) $arRet["FILE"] = $srcFile;
            }

            if ($arRet["FILE"]) {
                if ($p_arParams["FILE_IMAGE"]) {
                    $arRet["FILE_IMAGE"] = $p_arParams["FILE_IMAGE"];
                } else {
                    if ($p_arParams["FIELD_IMAGE"]) {
                        $imageFile = $arSrcFile[$p_arParams["FIELD_IMAGE"]];
                        if ($imageFile) $imageFile = CFile::GetFileArray($imageFile);
                        if (isset($imageFile["SRC"])) $arRet["FILE_IMAGE"] = $imageFile;
                    }
                    if (!$arRet["FILE_IMAGE"] && $p_arParams["PROPERTY_IMAGE"]) {
                        $imageFile = $arSrcFile["PROPERTY_" . $p_arParams["PROPERTY_IMAGE"] . "_VALUE"];
                        if ($imageFile) $imageFile = CFile::GetFileArray($imageFile);
                        if (isset($imageFile["SRC"])) $arRet["FILE_IMAGE"] = $imageFile;
                    }
                }

                if ($p_arParams["FILE_LABEL"]) {
                    $arRet["FILE_LABEL"] = $p_arParams["FILE_LABEL"];
                } else {
                    if ($p_arParams["FIELD_LABEL"]) {
                        $arRet["FILE_LABEL"] = $arSrcFile[$p_arParams["FIELD_LABEL"]];
                        if (is_array($arRet["FILE_LABEL"])) $arRet["FILE_LABEL"] = $arRet["FILE_LABEL"]["TEXT"];
                    }
                    if (!$arRet["FILE_LABEL"] && $p_arParams["PROPERTY_LABEL"]) {
                        $arRet["FILE_LABEL"] = $arSrcFile["PROPERTY_" . $p_arParams["PROPERTY_LABEL"] . "_VALUE"];
                        if (is_array($arRet["FILE_LABEL"])) $arRet["FILE_LABEL"] = $arRet["FILE_LABEL"]["TEXT"];
                    }
                }

                if ($p_arParams["FILE_DESCRIPTION"]) {
                    $arRet["FILE_DESCRIPTION"] = $p_arParams["FILE_DESCRIPTION"];
                } else {
                    if ($p_arParams["FIELD_DESCRIPTION"]) {
                        $arRet["FILE_DESCRIPTION"] = $arSrcFile[$p_arParams["FIELD_DESCRIPTION"]];
                        if (is_array($arRet["FILE_DESCRIPTION"])) $arRet["FILE_DESCRIPTION"] = $arRet["FILE_DESCRIPTION"]["TEXT"];
                    }
                    if (!$arRet["FILE_DESCRIPTION"] && $p_arParams["PROPERTY_DESCRIPTION"]) {
                        $arRet["FILE_DESCRIPTION"] = $arSrcFile["PROPERTY_" . $p_arParams["PROPERTY_DESCRIPTION"] . "_VALUE"];
                        if (is_array($arRet["FILE_DESCRIPTION"])) $arRet["FILE_DESCRIPTION"] = $arRet["FILE_DESCRIPTION"]["TEXT"];
                    }
                }
            }
        }
        return $arRet;
    }

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

    // ELEMENTS
    protected function pf_getElementSelect($p_arParams) {
        $arRet = array("IBLOCK_ID", "ID");
        if ($p_arParams["FIELD_IMAGE"]) {
            $arRet[] = $p_arParams["FIELD_IMAGE"];
        }
        if ($p_arParams["FIELD_LABEL"]) {
            $arRet[] = $p_arParams["FIELD_LABEL"];
        }
        if ($p_arParams["FIELD_DESCRIPTION"]) {
            $arRet[] = $p_arParams["FIELD_DESCRIPTION"];
        }
        if ($p_arParams["PROPERTY_FILE"]) {
            $arRet[] = "PROPERTY_" . $p_arParams["PROPERTY_FILE"];
        }
        if ($p_arParams["PROPERTY_IMAGE"]) {
            $arRet[] = "PROPERTY_" . $p_arParams["PROPERTY_IMAGE"];
        }
        if ($p_arParams["PROPERTY_LABEL"]) {
            $arRet[] = "PROPERTY_" . $p_arParams["PROPERTY_LABEL"];
        }
        if ($p_arParams["PROPERTY_DESCRIPTION"]) {
            $arRet[] = "PROPERTY_" . $p_arParams["PROPERTY_DESCRIPTION"];
        }
        $arRet = array_unique($arRet);
        return $arRet;
    }

    protected function pf_getElementFilter($p_arParams) {
        $arRet = array(
                "IBLOCK_ID" => $p_arParams["IBLOCK_ID"],
                "ACTIVE" => "Y"
        );
        if ($p_arParams["ELEMENT_ID"]) {
            $arRet['ID'] = $p_arParams["ELEMENT_ID"];
        } elseif ($p_arParams["ELEMENT_CODE"]) {
            $arRet['CODE'] = $p_arParams["ELEMENT_CODE"];
        }
        return $arRet;
    }

    protected function pf_getElementOrder($p_arParams) {
        $arRet = array();
        return $arRet;
    }

    protected function pf_getElement($p_arParams) {
        $arOrder = $this -> pf_getElementOrder($p_arParams);
        $arFilter = $this -> pf_getElementFilter($p_arParams);
        $arSelect = $this -> pf_getElementSelect($p_arParams);

        $arRet = array();
        $dbElement = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        if ($arElement = $dbElement -> Fetch()) {
            $arRet = $arElement;
        }
        return $arRet;
    }
    // End of ELEMENTS
}