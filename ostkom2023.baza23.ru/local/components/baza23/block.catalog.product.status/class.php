<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class PC_BLOCK_CatalogProductStatus extends CBitrixComponent {
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

        $p_arParams["PRODUCT_ID"] = IntVal($p_arParams["PRODUCT_ID"]);
        $p_arParams["PRODUCT_DETAIL_PAGE_URL"] = IntVal($p_arParams["PRODUCT_DETAIL_PAGE_URL"]);
        $p_arParams["PRODUCT_STATUS_CODE"] = strtolower(trim($p_arParams["PRODUCT_STATUS_CODE"]));
        $p_arParams["PRODUCT_STATUS_EXTERNAL_CODE"] = strtoupper(trim($p_arParams["PRODUCT_STATUS_EXTERNAL_CODE"]));

        $p_arParams["USE_STATUS_IN_BASKET"] = ($p_arParams["USE_STATUS_IN_BASKET"] == "Y" ? "Y" : "N");
        $p_arParams["STATUS_IN_BASKET_CODE"] = strtolower(trim($p_arParams["STATUS_IN_BASKET_CODE"]));
        if (!$p_arParams["STATUS_IN_BASKET_CODE"]) $p_arParams["STATUS_IN_BASKET_CODE"] = "in-basket";

        $p_arParams["PRODUCT_CAN_BUY"] = ($p_arParams["PRODUCT_CAN_BUY"] == "Y" ? "Y" : "N");

        $p_arParams["USE_QUANTITY"] = ($p_arParams["USE_QUANTITY"] == "Y" ? "Y" : "N");
        $p_arParams["PRODUCT_QUANTITY"] = IntVal($p_arParams["PRODUCT_QUANTITY"]);

        $p_arParams["STATUS_AVAILABLE_CODE"] = strtolower(trim($p_arParams["STATUS_AVAILABLE_CODE"]));
        if (!$p_arParams["STATUS_AVAILABLE_CODE"]) $p_arParams["STATUS_AVAILABLE_CODE"] = "available";

        $p_arParams["STATUS_NOT_AVAILABLE_CODE"] = strtolower(trim($p_arParams["STATUS_NOT_AVAILABLE_CODE"]));
        if (!$p_arParams["STATUS_NOT_AVAILABLE_CODE"]) $p_arParams["STATUS_NOT_AVAILABLE_CODE"] = "not-available";

        $p_arParams["ACTION"] = trim($p_arParams["ACTION"]);
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

        $canBuy = ($this->arParams["PRODUCT_CAN_BUY"] == "Y");
        if ($this->arParams["CHECK_QUANTITY"] == "Y") $canBuy &= ($this->arParams["PRODUCT_QUANTITY"] > 0);
        $this->arResult["PRODUCT_CAN_BUY"] = ($canBuy ? "Y" : "N");

        $curStatusCode = false;
        if ($this->arParams["PRODUCT_STATUS_EXTERNAL_CODE"]) {
            foreach ($this->arParams["STATUS_LIST"] as $status => $arItem) {
                if ($arItem["external-code"] == $this->arParams["PRODUCT_STATUS_EXTERNAL_CODE"]) {
                    $curStatusCode = $status;
                    break;
                }
            }
        }
        if (!$curStatusCode) $curStatusCode = $this->arParams["PRODUCT_STATUS_CODE"];

        if (!$curStatusCode || !isset($this->arParams["STATUS_LIST"][$curStatusCode])) {
            if ($canBuy) {
                $curStatusCode = $this->arParams["STATUS_AVAILABLE_CODE"];
            } else {
                $curStatusCode = $this->arParams["STATUS_NOT_AVAILABLE_CODE"];
            }
        }

        if ($curStatusCode) {
            $curStatus = $this->arParams["STATUS_LIST"][$curStatusCode];
            while ($curStatus["goto-status-code"]) {
                $curStatus = $this->arParams["STATUS_LIST"][$curStatusCode];
                if (!$curStatus) break;

                $curStatusCode = $curStatus["goto-status-code"];
            }

            if ($curStatusCode != $this->arParams["STATUS_AVAILABLE_CODE"]) {
                $this->arResult["PRODUCT_CAN_BUY"] = "N";

            } elseif (!$canBuy) {
                $curStatusCode = $this->arParams["STATUS_NOT_AVAILABLE_CODE"];
            }
        }

        $this->arResult["CURRENT_STATUS_CODE"] = $curStatusCode;

        if ($this->arParams["ACTION"] == "GET_STATUS") {
            return $curStatusCode;
        }

        if ($this->arParams["USE_STATUS_IN_BASKET"] == "Y"
                && $curStatusCode == $this->arParams["STATUS_AVAILABLE_CODE"]) {
            $this->arResult["CSS_CLASSES"] = 'use-in-basket';
        }

        $this -> IncludeComponentTemplate();
        return $this;
    }
}