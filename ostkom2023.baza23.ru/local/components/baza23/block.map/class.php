<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class PC_BLOCK_Map extends CBitrixComponent {
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
        $p_arParams["LAZY_LOAD"] = (trim($p_arParams["LAZY_LOAD"]) == "Y" ? "Y" : "N");

        $p_arParams["CSS_ID"] = trim($p_arParams["CSS_ID"]);
        $p_arParams["ZOOM"] = intVal($p_arParams["ZOOM"]);
        $p_arParams["CENTER"] = trim($p_arParams["CENTER"]);

        if (!is_array($p_arParams["CENTER"]) || empty($p_arParams["CENTER"])) {
            $p_arParams["CONTROLS"] = '';
        }
        $p_arParams["TYPE"] = trim($p_arParams["TYPE"]);

        $p_arParams["DEFAULT_ICON_IMAGE"] = trim($p_arParams["DEFAULT_ICON_IMAGE"]);
        if (is_int($p_arParams["DEFAULT_ICON_IMAGE"])) {
            $arFile = CFile::GetFileArray($p_arParams["DEFAULT_ICON_IMAGE"]);
            $p_arParams["DEFAULT_ICON_IMAGE"] = $arFile["RSC"];
        }
        $p_arParams["DEFAULT_ICON_IMAGE_HOVER"] = trim($p_arParams["DEFAULT_ICON_IMAGE_HOVER"]);
        if (is_int($p_arParams["DEFAULT_ICON_IMAGE_HOVER"])) {
            $arFile = CFile::GetFileArray($p_arParams["DEFAULT_ICON_IMAGE_HOVER"]);
            $p_arParams["DEFAULT_ICON_IMAGE_HOVER"] = $arFile["RSC"];
        }
        if (!is_array($p_arParams["DEFAULT_ICON_SIZE"]) || count($p_arParams["DEFAULT_ICON_SIZE"]) != 2) {
            $p_arParams["DEFAULT_ICON_SIZE"] = '';
        }
        if (!is_array($p_arParams["DEFAULT_ICON_OFFSET"]) || count($p_arParams["DEFAULT_ICON_OFFSET"]) != 2) {
            $p_arParams["DEFAULT_ICON_OFFSET"] = '';
        }

        $p_arParams["SHOW_CONTENT"] = (trim($p_arParams["SHOW_CONTENT"]) == "Y" ? "Y" : "N");
        $p_arParams["SHOW_HINT"] = (trim($p_arParams["SHOW_HINT"]) == "Y" ? "Y" : "N");

        $p_arParams["SHOW_BALLOON"] = (trim($p_arParams["SHOW_BALLOON"]) == "Y" ? "Y" : "N");
        $p_arParams["BALLOON_SHOW_TITLE"] = (trim($p_arParams["BALLOON_SHOW_TITLE"]) == "Y" ? "Y" : "N");
        $p_arParams["BALLOON_SHOW_TEXT"] = (trim($p_arParams["BALLOON_SHOW_TEXT"]) == "Y" ? "Y" : "N");
        $p_arParams["BALLOON_SHOW_ADDRESS"] = (trim($p_arParams["BALLOON_SHOW_ADDRESS"]) == "Y" ? "Y" : "N");
        $p_arParams["BALLOON_SHOW_PHONES"] = (trim($p_arParams["BALLOON_SHOW_PHONES"]) == "Y" ? "Y" : "N");
        $p_arParams["BALLOON_SHOW_MESSENGERS"] = (trim($p_arParams["BALLOON_SHOW_MESSENGERS"]) == "Y" ? "Y" : "N");
        $p_arParams["BALLOON_SHOW_EMAILS"] = (trim($p_arParams["BALLOON_SHOW_EMAILS"]) == "Y" ? "Y" : "N");
        $p_arParams["BALLOON_SHOW_WORKTIME"] = (trim($p_arParams["BALLOON_SHOW_WORKTIME"]) == "Y" ? "Y" : "N");
        $p_arParams["BALLOON_WIDTH"] = intVal($p_arParams["BALLOON_WIDTH"]);

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

        $this -> IncludeComponentTemplate();
        return $this;
    }
}