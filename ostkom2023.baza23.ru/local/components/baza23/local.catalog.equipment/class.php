<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class PC_LOCAL_CatalogEquipment extends CBitrixComponent {
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

        $p_arParams["SHOW_TITLE"] = (trim($p_arParams["SHOW_TITLE"]) == "Y" ? "Y" : "N");
        $p_arParams["TITLE"] = trim($p_arParams["TITLE"]);

        $p_arParams["LAZY_LOAD_IMAGE"] = (trim($p_arParams["LAZY_LOAD_IMAGE"]) == "Y" ? "Y" : "N");

        $p_arParams["SHOW_INACTIVE"] = (trim($p_arParams["SHOW_INACTIVE"]) == "Y" ? "Y" : "N");

        $p_arParams["SHOW_EMPTY"] = (trim($p_arParams["SHOW_EMPTY"]) == "Y" ? "Y" : "N");
        $p_arParams["SHOW_EMPTY_TEXT"] = (trim($p_arParams["SHOW_EMPTY_TEXT"]) == "Y" ? "Y" : "N");
        $p_arParams["EMPTY_TEXT"] = trim($p_arParams["EMPTY_TEXT"]);
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

        $arAllEquipment = self::paf_getAllEquipments();

        $arEquipmentList = [];
        if ($this->arParams["SHOW_INACTIVE"] == "Y"
                && !empty($this->arParams["EQUIPMENT_INACTIVE_IDS"])) {
            foreach ($this->arParams["EQUIPMENT_INACTIVE_IDS"] as $equipmentId) {
                $arEquipment = $arAllEquipment[$equipmentId];
                if (!$arEquipment) continue;

                $key = $arEquipment["SORT"] . '-' . $arEquipment["ID"];
                $arEquipment["STATUS"] = 'INACTIVE';

                $arEquipmentList[$key] = $arEquipment;
            }
        }

        if (!empty($this->arParams["EQUIPMENT_ACTIVE_IDS"])) {
            foreach ($this->arParams["EQUIPMENT_ACTIVE_IDS"] as $equipmentId) {
                $arEquipment = $arAllEquipment[$equipmentId];
                if (!$arEquipment) continue;

                $key = $arEquipment["SORT"] . '-' . $arEquipment["ID"];
                $arEquipment["STATUS"] = 'ACTIVE';

                $arEquipmentList[$key] = $arEquipment;
            }
        }

        ksort($arEquipmentList);

        $this->arResult["EQUIPMENT"] = array_values($arEquipmentList);

        $this -> IncludeComponentTemplate();
        return $this;
    }

    public static function paf_getAllEquipments() {
        $arElements = \Baza23\DataUtils::psf_getAllElements(
                \Baza23\Site::psf_getIBlockId('equipment'),
                false, false,
                ["ID", "CODE", "NAME", "PREVIEW_PICTURE", "DETAIL_PICTURE",
                    "PROPERTY_UP_ICON_SVG", "PROPERTY_UP_TEXT",
                    "PROPERTY_UP_IMAGE_HOVER"]
        );

        $arRet = [];
        foreach ($arElements as $arItem) {
            $text = $arItem["PROPERTY_UP_TEXT_VALUE"];
            if (is_array($text)) $text = $text["TEXT"];

            $svg = $arItem["PROPERTY_UP_ICON_SVG_VALUE"];
            if (is_array($svg)) $svg = $svg["TEXT"];

            $arRet[$arItem["ID"]] = [
                "ID"                => $arItem["ID"],
                "CODE"              => $arItem["CODE"],
                "NAME"              => $arItem["NAME"],

                "ICON"              => $arItem["DETAIL_PICTURE"],
                "ICON_INACTIVE"     => $arItem["PREVIEW_PICTURE"],
                "ICON_HOVER"        => CFile::GetFileArray($arItem["PROPERTY_UP_IMAGE_HOVER_VALUE"]),

                "ICON_SVG"          => $svg,
                "TEXT"              => $text,
            ];
        }
        return $arRet;
    }
}