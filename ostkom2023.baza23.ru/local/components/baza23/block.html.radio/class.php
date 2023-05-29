<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class PC_BLOCK_HtmlRadio extends CBitrixComponent {
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
        $p_arParams["HTML_PARAMS"] = trim($p_arParams["HTML_PARAMS"]);

        $p_arParams["INPUT_ID"] = trim($p_arParams["INPUT_ID"]);
        $p_arParams["INPUT_HTML_PARAMS"] = trim($p_arParams["INPUT_HTML_PARAMS"]);

        $p_arParams["VALUE"] = trim($p_arParams["VALUE"]);

        $p_arParams["ATTRIBUTE_NAME"] = trim($p_arParams["ATTRIBUTE_NAME"]);
        $p_arParams["ATTRIBUTE_CHECKED"] = (trim($p_arParams["ATTRIBUTE_CHECKED"]) == "Y" ? "Y" : "N");
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
        $this->arResult["INPUT_ID"] = ($this->arParams["INPUT_ID"] ?
                $this->arParams["INPUT_ID"] : "_radio_" . randString(8));

        $this -> IncludeComponentTemplate();
        return $this;
    }
}