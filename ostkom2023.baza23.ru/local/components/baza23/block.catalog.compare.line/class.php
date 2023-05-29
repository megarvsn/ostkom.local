<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;

class PC_BLOCK_CatalogCompareLine extends CBitrixComponent {
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
        $p_arParams["CSS_ID"] = trim($p_arParams["CSS_ID"]);

        $p_arParams["COMPARE_PATH"] = trim($p_arParams["COMPARE_PATH"]);
        $p_arParams["HIDE_IF_EMPTY"] = (trim($p_arParams["HIDE_IF_EMPTY"]) == 'Y' ? 'Y' :'N');
        $p_arParams["SHOW_COUNT"] = (trim($p_arParams["SHOW_COUNT"]) == 'Y' ? 'Y' :'N');
        $p_arParams["SHOW_EMPTY_VALUES"] = (trim($p_arParams["SHOW_EMPTY_VALUES"]) == 'Y' ? 'Y' :'N');

        $p_arParams["ICON_EMPTY"] = trim($p_arParams["ICON_EMPTY"]);
        $p_arParams["ICON_FILL"] = trim($p_arParams["ICON_FILL"]);
        $p_arParams["SHOW_ICON_FILL"] = (trim($p_arParams["SHOW_ICON_FILL"]) == 'Y' ? 'Y' :'N');
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
        $this->arResult["PRODUCT_IDS"] = \Baza23\CatalogCompare::psf_getProductIds();

        $this -> IncludeComponentTemplate();
        return $this;
    }
}