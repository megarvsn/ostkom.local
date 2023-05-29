<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class PC_BLOCK_CatalogQuantityItem extends CBitrixComponent {
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
        if (!isset($p_arParams["CACHE_TYPE"])) $p_arParams["CACHE_TYPE"] = "N";
        if (!isset($p_arParams["CACHE_TIME"])) $p_arParams["CACHE_TIME"] = 0;

        $p_arParams["CSS_CLASSES"] = trim($p_arParams["CSS_CLASSES"]);
        $p_arParams["CSS_ID"] = trim($p_arParams["CSS_ID"]);
        $p_arParams["HTML_PARAMS"] = trim($p_arParams["HTML_PARAMS"]);

        $p_arParams["INPUT_CSS_ID"] = trim($p_arParams["INPUT_CSS_ID"]);
        $p_arParams["INPUT_NAME"] = trim($p_arParams["INPUT_NAME"]);

        $p_arParams["BUTTON_MINUS_CSS_ID"] = trim($p_arParams["BUTTON_MINUS_CSS_ID"]);
        $p_arParams["BUTTON_MINUS_TITLE"] = trim($p_arParams["BUTTON_MINUS_TITLE"]);

        $p_arParams["BUTTON_PLUS_CSS_ID"] = trim($p_arParams["BUTTON_PLUS_CSS_ID"]);
        $p_arParams["BUTTON_PLUS_TITLE"] = trim($p_arParams["BUTTON_PLUS_TITLE"]);

        $p_arParams["SHOW_QUANTITY_MEASURE"] = (trim($p_arParams["QUANTITY_MEASURE_CSS_ID"]) == "Y" ? "Y" : "N");
        $p_arParams["QUANTITY_MEASURE_CSS_ID"] = trim($p_arParams["QUANTITY_MEASURE_CSS_ID"]);
        $p_arParams["QUANTITY_MEASURE_TEXT"] = trim($p_arParams["QUANTITY_MEASURE_TEXT"]);

        $p_arParams["SHOW_PRICE_TOTAL"] = (trim($p_arParams["SHOW_PRICE_TOTAL"]) == "Y" ? "Y" : "N");
        $p_arParams["PRICE_TOTAL_CSS_ID"] = trim($p_arParams["PRICE_TOTAL_CSS_ID"]);
        $p_arParams["PRICE_TOTAL_TEXT"] = trim($p_arParams["PRICE_TOTAL_TEXT"]);

        $p_arParams["ICON_MINUS"] = trim($p_arParams["ICON_MINUS"]);
        $p_arParams["ICON_PLUS"] = trim($p_arParams["ICON_PLUS"]);

        $p_arParams["USE_STATUS_IN_BASKET"] = ($p_arParams["USE_STATUS_IN_BASKET"] == "Y" ? "Y" : "N");
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
        $this->arResult["VALUE"] = IntVal($this->arParams["VALUE"]);

        if ($this->arParams["USE_STATUS_IN_BASKET"] == "Y") {
            $this->arResult["CSS_CLASSES"] = 'use-in-basket';
        }

        $this -> IncludeComponentTemplate();
        return $this;
    }
}