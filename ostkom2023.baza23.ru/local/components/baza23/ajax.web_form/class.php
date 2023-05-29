<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class PC_UTIL_Ajax_WebForm extends CBitrixComponent {
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

        $p_arParams["USE_MODAL_WINDOW"] = (trim($p_arParams["USE_MODAL_WINDOW"]) == "Y" ? "Y" : "N");

        $p_arParams["CSS_CLASSES"] = trim($p_arParams["CSS_CLASSES"]);
        $p_arParams["CSS_ID"] = trim($p_arParams["CSS_ID"]);
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
            $this->IncludeComponentTemplate();
        }
        return;
    }
}