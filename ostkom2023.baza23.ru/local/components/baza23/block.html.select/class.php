<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class PC_BLOCK_HtmlSelect extends CBitrixComponent {
    /**
     * Проверка наличия модулей требуемых для работы компонента
     * @return bool
     * @throws Exception
     */
    private function _checkModules() {
        return true;
    }

    /**
     * Подготовка параметров компонента
     * @param $p_arParams
     * @return mixed
     */
    public function onPrepareComponentParams($p_arParams) {
        $p_arParams["CSS_CLASSES"] = trim($p_arParams["CSS_CLASSES"]);
        $p_arParams["CSS_ID"] = trim($p_arParams["CSS_ID"]);
        $p_arParams["LABEL_ID"] = trim($p_arParams["LABEL_ID"]);
        $p_arParams["HTML_SIZE"] = IntVal($p_arParams["HTML_SIZE"]);
        $p_arParams["HTML_PARAMS"] = trim($p_arParams["HTML_PARAMS"]);

        $p_arParams["MULTI_SELECT"] = (trim($p_arParams["MULTI_SELECT"]) == "Y" ? "Y" : "N");
        $p_arParams["BTN_SELECT_ALL"] = trim($p_arParams["BTN_SELECT_ALL"]);
        $p_arParams["BTN_DESELECT_ALL"] = trim($p_arParams["BTN_DESELECT_ALL"]);

        $p_arParams["ATTRIBUTE_REQUIRED"] = (trim($p_arParams["ATTRIBUTE_REQUIRED"]) == "Y" ? "Y" : "N");
        $p_arParams["ATTRIBUTE_NAME"] = trim($p_arParams["ATTRIBUTE_NAME"]);
        $p_arParams["ATTRIBUTE_PLACEHOLDER"] = trim($p_arParams["ATTRIBUTE_PLACEHOLDER"]);

        $p_arParams["USE_VALUE"] = (trim($p_arParams["USE_VALUE"]) == "Y" ? "Y" : "N");
        $p_arParams["USE_FIELD_AS_VALUE"] = trim($p_arParams["USE_FIELD_AS_VALUE"]);
        $p_arParams["USE_FIELD_AS_LABEL"] = trim($p_arParams["USE_FIELD_AS_LABEL"]);
        $p_arParams["USE_FIELD_AS_LABEL_HTML_PARAMS"] = trim($p_arParams["USE_FIELD_AS_LABEL_HTML_PARAMS"]);
        $p_arParams["USE_FIELD_AS_LABEL_CSS_ID"] = trim($p_arParams["USE_FIELD_AS_LABEL_CSS_ID"]);
        $p_arParams["USE_FIELD_AS_LABEL_CSS_CLASSES"] = trim($p_arParams["USE_FIELD_AS_LABEL_CSS_CLASSES"]);
        $p_arParams["USE_HTML_PARAMS"] = (trim($p_arParams["USE_HTML_PARAMS"]) == "Y" ? "Y" : "N");
        $p_arParams["USE_FIELD_AS_HTML_PARAMS"] = trim($p_arParams["USE_FIELD_AS_HTML_PARAMS"]);
        $p_arParams["USE_FIELD_AS_CSS_ID"] = trim($p_arParams["USE_FIELD_AS_CSS_ID"]);

        if (!is_array($p_arParams["ARRAY_OF_VALUES"])) {
            $p_arParams["ARRAY_OF_VALUES"] = [];
        }

        $p_arParams["SKIP_DEFAULT"] = (trim($p_arParams["SKIP_DEFAULT"]) == "Y" ? "Y" : "N");
        $p_arParams["SELECT_FIRST"] = (trim($p_arParams["SELECT_FIRST"]) == "Y" ? "Y" : "N");
        $p_arParams["KEY_OF_DEFAULT"] = trim($p_arParams["KEY_OF_DEFAULT"]);

        $p_arParams["DEFAULT_LABEL"] = trim($p_arParams["DEFAULT_LABEL"]);
        $p_arParams["DEFAULT_LABEL_CSS_CLASSES"] = trim($p_arParams["DEFAULT_LABEL_CSS_CLASSES"]);
        $p_arParams["DEFAULT_LABEL_HTML_PARAMS"] = trim($p_arParams["DEFAULT_LABEL_HTML_PARAMS"]);
        $p_arParams["DEFAULT_LABEL_CSS_ID"] = trim($p_arParams["DEFAULT_LABEL_CSS_ID"]);
        $p_arParams["DEFAULT_HTML_PARAMS"] = trim($p_arParams["DEFAULT_HTML_PARAMS"]);
        $p_arParams["DEFAULT_CSS_ID"] = trim($p_arParams["DEFAULT_CSS_ID"]);
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
        $this->arResult = self::psf_createOptions($this->arParams);

        $this -> IncludeComponentTemplate();
        return $this;
    }

    protected static function psf_createOptions($p_arParams) {
        $arRet = [
            "OPTIONS"  => [],
            "DEFAULT"  => self::psf_getDefault($p_arParams),
            "SELECTED_KEYS"  => [],
            "SELECTED_COUNT" => 0
        ];

        $index = 1;
        $checkSelected = true;
        $countSelected = 0;
        foreach ($p_arParams["ARRAY_OF_VALUES"] as $key => $arItem) {
            if ($p_arParams["SKIP_DEFAULT"] == "Y"
                    && $key === $arRet["DEFAULT"]["KEY"]) continue;

            $arOption = [
                "KEY"               => $key,
                "VALUE"             => self::psf_option_getValue($p_arParams, $key),
                "LABEL"             => self::psf_option_getLabel($p_arParams, $key),
                "LABEL_CSS_ID"      => self::psf_option_getLabelCssId($p_arParams, $key),
                "LABEL_CSS_CLASSES" => self::psf_option_getLabelCssClasses($p_arParams, $key),
                "LABEL_HTML_PARAMS" => self::psf_option_getLabelHtmlParams($p_arParams, $key),
                "CSS_ID"            => self::psf_option_getCssId($p_arParams, $key),
                "HTML_PARAMS"       => self::psf_option_getHtmlParams($p_arParams, $key),
                "INDEX"             => $index
            ];

            if ($key === $p_arParams["KEY_OF_DEFAULT"]) {
                $arOption["DEFAULT"] = "Y";
            }

            if ($checkSelected) {
                $isSelected = self::psf_option_isSelected($p_arParams, $arOption["VALUE"]);
                if ($isSelected) {
                    $countSelected ++;
                    $arOption["SELECTED"] = "Y";
                    if ($p_arParams["MULTI_SELECT"] != "Y") $checkSelected = false;

                    $arRet["SELECTED_KEYS"][] = $key;
                }
            }

            $arRet["OPTIONS"][$key] = $arOption;
            $index ++;
        }

        if (!empty($arRet["OPTIONS"]) && $countSelected <= 0) {
            if ($p_arParams["SKIP_DEFAULT"] == "Y"
                    && $p_arParams["SELECT_FIRST"] == "Y") {
                $arFirst = reset($arRet["OPTIONS"]);
                $arRet["OPTIONS"][$arFirst["KEY"]]["SELECTED"] = "Y";
                $arRet["SELECTED_KEYS"][] = $arFirst["KEY"];
            }
        }

        $arRet["SELECTED_COUNT"] = $countSelected;
        return $arRet;
    }

    protected static function psf_option_getValue($p_arParams, $p_key) {
        if ($p_arParams["USE_VALUE"] == "Y") {
            if ($p_arParams["USE_FIELD_AS_VALUE"]) {
                $ret = $p_arParams["ARRAY_OF_VALUES"][$p_key][$p_arParams["USE_FIELD_AS_VALUE"]];
            } else {
                $ret = $p_key;
            }
        } elseif ($p_arParams["USE_FIELD_AS_LABEL"]) {
            $ret = $p_arParams["ARRAY_OF_VALUES"][$p_key][$p_arParams["USE_FIELD_AS_LABEL"]];
        } else {
            $ret = $p_arParams["ARRAY_OF_VALUES"][$p_key];
        }
        return $ret;
    }

    protected static function psf_option_getLabel($p_arParams, $p_key) {
        if ($p_arParams["USE_FIELD_AS_LABEL"]) {
            $ret = $p_arParams["ARRAY_OF_VALUES"][$p_key][$p_arParams["USE_FIELD_AS_LABEL"]];
        } else {
            $ret = $p_arParams["ARRAY_OF_VALUES"][$p_key];
        }
        return $ret;
    }

    protected static function psf_option_getHtmlParams($p_arParams, $p_key) {
        if ($p_arParams["USE_HTML_PARAMS"] == "Y" && $p_arParams["USE_FIELD_AS_HTML_PARAMS"]) {
            $ret = $p_arParams["ARRAY_OF_VALUES"][$p_key][$p_arParams["USE_FIELD_AS_HTML_PARAMS"]];
        }
        return $ret;
    }

    protected static function psf_option_getCssId($p_arParams, $p_key) {
        if ($p_arParams["USE_FIELD_AS_CSS_ID"]) {
            $ret = $p_arParams["ARRAY_OF_VALUES"][$p_key][$p_arParams["USE_FIELD_AS_CSS_ID"]];
        }
        return $ret;
    }

    protected static function psf_option_getLabelHtmlParams($p_arParams, $p_key) {
        if ($p_arParams["USE_HTML_PARAMS"] == "Y" && $p_arParams["USE_FIELD_AS_LABEL_HTML_PARAMS"]) {
            $ret = $p_arParams["ARRAY_OF_VALUES"][$p_key][$p_arParams["USE_FIELD_AS_LABEL_HTML_PARAMS"]];
        }
        return $ret;
    }

    protected static function psf_option_getLabelCssId($p_arParams, $p_key) {
        if ($p_arParams["USE_FIELD_AS_LABEL_CSS_ID"]) {
            $ret = $p_arParams["ARRAY_OF_VALUES"][$p_key][$p_arParams["USE_FIELD_AS_LABEL_CSS_ID"]];
        }
        return $ret;
    }

    protected static function psf_option_getLabelCssClasses($p_arParams, $p_key) {
        if ($p_arParams["USE_FIELD_AS_LABEL_CSS_CLASSES"]) {
            $ret = $p_arParams["ARRAY_OF_VALUES"][$p_key][$p_arParams["USE_FIELD_AS_LABEL_CSS_CLASSES"]];
        }
        return $ret;
    }

    protected static function psf_option_isSelected($p_arParams, $p_value) {
        $ret = false;
        if (is_array($p_arParams["SELECTED_KEY"])) {
            $ret = in_array($p_value, $p_arParams["SELECTED_KEY"]);
        } elseif ($p_arParams["SELECTED_KEY"]) {
            $ret = ($p_value == $p_arParams["SELECTED_KEY"]);
        }
        return $ret;
    }

    protected static function psf_getDefault($p_arParams) {
        if (empty($p_arParams["KEY_OF_DEFAULT"])) {
            $arRet = [
                "KEY"               => false,
                "VALUE"             => $p_arParams["DEFAULT_VALUE"],
                "LABEL"             => $p_arParams["DEFAULT_LABEL"],
                "LABEL_CSS_ID"      => $p_arParams["DEFAULT_LABEL_CSS_ID"],
                "LABEL_CSS_CLASSES" => $p_arParams["DEFAULT_LABEL_CSS_CLASSES"],
                "LABEL_HTML_PARAMS" => $p_arParams["DEFAULT_LABEL_HTML_PARAMS"],
                "CSS_ID"            => $p_arParams["DEFAULT_CSS_ID"],
                "HTML_PARAMS"       => $p_arParams["DEFAULT_HTML_PARAMS"],
                "USER_DEFAULT"      => "Y"
            ];

        } elseif (isset($p_arParams["ARRAY_OF_VALUES"][$p_arParams["KEY_OF_DEFAULT"]])) {
            $arRet = [
                "KEY"          => $p_arParams["KEY_OF_DEFAULT"],
                "VALUE"        => self::psf_option_getValue($p_arParams, $p_arParams["KEY_OF_DEFAULT"]),
                "LABEL"        => self::psf_option_getLabel($p_arParams, $p_arParams["KEY_OF_DEFAULT"]),
                "HTML_PARAMS"  => self::psf_option_getHtmlParams($p_arParams, $p_arParams["KEY_OF_DEFAULT"]),
                "CSS_ID"       => self::psf_option_getCssId($p_arParams, $p_arParams["KEY_OF_DEFAULT"]),
                "USER_DEFAULT" => "N"
            ];
        }
        return $arRet;
    }
}