<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class PC_BLOCK_CatalogCompareItem extends CBitrixComponent {
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
        $p_arParams["INPUT_CSS_ID"] = trim($p_arParams["INPUT_CSS_ID"]);

        $p_arParams["COMPARE_PATH"] = trim($p_arParams["COMPARE_PATH"]);

        $p_arParams["BUTTON_ADD"] = trim($p_arParams["BUTTON_ADD"]);
        $p_arParams["BUTTON_REMOVE"] = trim($p_arParams["BUTTON_REMOVE"]);

        $p_arParams["ICON_EMPTY"] = trim($p_arParams["ICON_EMPTY"]);
        $p_arParams["ICON_FILL"] = trim($p_arParams["ICON_FILL"]);
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

        $this->arResult["PRODUCT_ID"] = IntVal($this->arParams["PRODUCT_ID"]);
        $this->arResult["CHECKED"] = "N";

        if ($this->arResult["PRODUCT_ID"]) {
            $arProductIds = \Baza23\CatalogCompare::psf_getProductIds();
            if (!empty($arProductIds[$this->arResult["PRODUCT_ID"]])) {
                $this->arResult["CHECKED"] = "Y";
            }
        }

        $this -> IncludeComponentTemplate();
        return $this;
    }
}