<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class PC_BLOCK_SaleDelivery extends CBitrixComponent {
    /**
     * Проверка наличия модулей требуемых для работы компонента
     * @return bool
     * @throws Exception
     */
    private function _checkModules() {
        if (!\Bitrix\Main\Loader::includeModule('sale')) {
            $this->arResult["ERROR"] = array('TYPE' => 'MODULE_SALE_IS_NOT_LOADED');
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

        $p_arParams["CSS_ID"] = trim($p_arParams["CSS_ID"]);
        $p_arParams["CSS_CLASSES"] = trim($p_arParams["CSS_CLASSES"]);

        $p_arParams["LAZY_LOAD_IMAGE"] = (trim($p_arParams["LAZY_LOAD_IMAGE"]) == "Y" ? "Y" : "N");

        $p_arParams["DISABLED_DELIVERY_IDS"] = trim($p_arParams["DISABLED_DELIVERY_IDS"]);

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
            $this->arResult["DELIVERY"] = self::psf_getDeliveries($this->arParams);
            $this -> IncludeComponentTemplate();
        }
        return $this;
    }

    protected static function psf_getDeliveries($p_arParams) {
        $arRet = [];
        $arDisabledDelivery = explode(',', $p_arParams["DISABLED_DELIVERY_IDS"]);

        $dbDelivery = \Bitrix\Sale\Delivery\Services\Table::getList(
            array(
                'order'   => array("SORT" => "ASC", "ID" => "ASC"),
                'filter' => array('ACTIVE' => 'Y')
            )
        );
        while ($arDelivery = $dbDelivery -> Fetch()) {
            if (in_array($arDelivery["ID"], $arDisabledDelivery)) continue;

            if ($arImage = CFile::GetFileArray($arDelivery["LOGOTIP"])) {
                $arDelivery["IMAGE"] = $arImage;
            }
            $arRet[$arDelivery["ID"]] = $arDelivery;
        }
        return $arRet;
    }
}